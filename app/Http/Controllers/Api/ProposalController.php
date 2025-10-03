<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function index()
    {
        return Proposal::with(['client', 'user:id,name', 'items'])->get();
    }

    public function view($hash)
    {
        $proposal = Proposal::where('unique_hash', $hash)
            ->with(['client', 'user:id,name,email', 'items'])
            ->firstOrFail();

        return response()->json($proposal);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'valid_until' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.value' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($data, $request) {
            $total_value = array_reduce($data['items'], function ($sum, $item) {
                return $sum + $item['value'];
            }, 0);

            $proposal = Proposal::create([
                'client_id' => $data['client_id'],
                'user_id' => auth()->id(),
                'valid_until' => $data['valid_until'],
                'total_value' => $total_value,
                'status' => 'sent',
                'unique_hash' => Str::random(16),
            ]);

            $proposal->items()->createMany($data['items']);

            return response()->json($proposal->load(['items', 'user:id,name']), 201);
        });
    }

    public function show(Proposal $proposal)
    {
        return $proposal->load(['client', 'user:id,name', 'items']);
    }

    public function update(Request $request, Proposal $proposal)
    {
        $data = $request->validate([
            'valid_until' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.value' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($data, $proposal) {
            $total_value = array_reduce($data['items'], function ($sum, $item) {
                return $sum + $item['value'];
            }, 0);

            $proposal->update([
                'valid_until' => $data['valid_until'],
                'total_value' => $total_value,
            ]);

            $proposal->items()->delete();
            $proposal->items()->createMany($data['items']);

            return response()->json($proposal->load(['client', 'items']));
        });
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return response()->json(null, 204);
    }
}