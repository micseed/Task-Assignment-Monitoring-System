<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicClass extends Model
{
    // Override class word constraint
    protected $table = 'classes';

    protected $fillable = [
        'subject_id',
        'section',
        'school_year',
        'semester'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'class_id', 'student_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
