<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $guarded = [];
    protected $casts = [
        'changes' => 'array'
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function username()
    {
        if ($this->user->name === auth()->user()->name) {
            return 'You';
        }

        return $this->user->name;
    }
}
