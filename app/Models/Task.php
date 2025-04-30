<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use function Symfony\Component\String\s;

class Task extends Model
{
    use HasFactory;
    use RecordsActivity;
    protected $guarded = [];

    protected $touches = ['project'];
    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static array $recordableEvents = ['created', 'deleted'];

    public function complete(): void
    {
        if ($this->completed) return;

        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function incomplete(): void
    {
        if (! $this->completed) return;

        $this->update(['completed' => false]);

        $this->recordActivity('uncompleted_task');
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
