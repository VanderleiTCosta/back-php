<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'description',
        'value',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}