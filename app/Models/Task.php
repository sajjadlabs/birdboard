<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function Symfony\Component\String\s;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $touches = ['project'];
    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($task) {
            $task->project->recordActivity('created_task');
        });
    }

    public function complete(): void
    {
        $this->update(['completed' => true]);

        $this->project->recordActivity('completed_task');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function path(): string
    {
        return $this->project->path() . "/tasks/" . $this->id;
    }
}
