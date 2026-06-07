<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'description',
        'type',
        'max_points',
        'due_date',
        'allow_late',
        'is_published'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'allow_late' => 'boolean',
        'is_published' => 'boolean',
        'max_points' => 'decimal:2'
    ];

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function calendarIntegrations(): HasMany
    {
        return $this->hasMany(CalendarIntegration::class);
    }
}
