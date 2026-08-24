<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateSubmission extends Model
{
    protected $fillable = [
        'assessment_id',
        'candidate_id',
        'started_at',
        'submitted_at',
        'is_time_out',
        'answers_payload',
        'disc_scores',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_time_out' => 'boolean',
        'answers_payload' => 'array',
        'disc_scores' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
