<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'notes' => ['nullable'],
        ];
    }

    public function store(): Project
    {
        return $this->user()->projects()->create($this->validated());
    }
}
