<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];
    public array $old = [];


    public function path(): string
    {
        return "/projects/{$this->id}";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body): Task
    {
        $task = $this->tasks()->create(compact('body'));

        return $task;
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function recordActivity(string $description): void
    {
        $this->activities()->create([
            'description' => $description,
            'changes' => $description === 'updated' ? [
                'before' => array_diff($this->old, $this->getAttributes()),
                'after' => $this->getChanges()
            ] : null
        ]);
    }
}
