<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['first_name', 'last_name', 'email', 'password_hash', 'role', 'department_id', 'profile_picture', 'is_active', 'email_notifications', 'calendar_notifications'])]
#[Hidden(['password_hash', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'      => 'datetime',
            'password_hash'          => 'hashed',
            'is_active'              => 'boolean',
            'email_notifications'    => 'boolean',
            'calendar_notifications' => 'boolean',
        ];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /* ---------- Name Accessor ---------- */

    /**
     * Dynamic attribute to return first and last name combined.
     * Allows code expecting $user->name to work seamlessly.
     */
    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /* ---------- Role helpers ---------- */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /* ---------- Relationships ---------- */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // A teacher can teach multiple subjects
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    // A student has multiple enrollments in classes
    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class, 'student_id');
    }

    // Classes student is enrolled in
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(AcademicClass::class, 'class_enrollments', 'student_id', 'class_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Submissions sent by this student
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    // Notifications received by this user
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'recipient_id');
    }
}
