<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration_minutes',
        'is_published',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'is_published' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order_index')->orderBy('question_number');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(CandidateSubmission::class);
    }
}
