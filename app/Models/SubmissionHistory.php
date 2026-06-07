<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionHistory extends Model
{
    protected $table = 'submission_history';

    protected $fillable = [
        'submission_id',
        'file_url',
        'code_content',
        'action',
        'action_at'
    ];

    protected $casts = [
        'action_at' => 'datetime'
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
