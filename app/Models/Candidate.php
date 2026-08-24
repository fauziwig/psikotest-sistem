<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'whatsapp_number',
        'applied_position',
        'source_platform',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(CandidateSubmission::class);
    }
}
