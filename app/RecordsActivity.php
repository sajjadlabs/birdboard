<?php

namespace App;

use App\Models\Activity;
use Arr;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordsActivity
{
    protected array $old = [];

    protected static function bootRecordsActivity(): void
    {
        foreach (static::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->old = $model->getOriginal();
                });
            }
        }
    }

    protected static function recordableEvents(): array
    {
        return static::$recordableEvents ?? ['created', 'updated'];
    }

    protected function activityDescription($description): string
    {
        return "{$description}_" . strtolower(class_basename($this));
    }

    protected function recordActivity(string $description): void
    {
        $this->activities()->create([
            'user_id'     => ($this->project ?? $this)->owner_id,
            'description' => $description,
            'project_id'  => class_basename($this) === 'Project' ? $this->id : $this->project_id,
            'changes'     => $this->activityChanges()
        ]);
    }

    protected function activityChanges(): ?array
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), ['updated_at']),
                'after' => Arr::except($this->getChanges(), ['updated_at'])
            ];
        }
        return null;
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
