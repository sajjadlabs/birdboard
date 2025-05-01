<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ProjectUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('update', $this->project());
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'sometimes'],
            'description' => ['required', 'sometimes'],
            'notes' => ['nullable'],
        ];
    }

    public function project(): Project
    {
        return Project::findOrFail($this->route('project'));
    }

    public function save(): Model
    {
        return tap($this->project())->update($this->validated());
    }
}
