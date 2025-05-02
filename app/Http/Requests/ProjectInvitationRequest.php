<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProjectInvitationRequest extends FormRequest
{
    protected $errorBag = 'invitation';

    public function authorize(): bool
    {
        return Gate::allows('manage', $this->route('project'));
    }

    public function rules(): array
    {
        return [
            'email' => ['required', Rule::exists('users', 'email')]
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'The user must have a birdboard account'
        ];
    }
}
