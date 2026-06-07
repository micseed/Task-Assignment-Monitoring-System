<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_url',
        'code_content',
        'code_language',
        'status',
        'submitted_at',
        'is_late',
        'points_earned',
        'feedback',
        'graded_at',
        'graded_by'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_late' => 'boolean',
        'points_earned' => 'decimal:2',
        'graded_at' => 'datetime'
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SubmissionHistory::class, 'submission_id');
    }
}
