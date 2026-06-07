<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'dean_id'];

    public function dean(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
