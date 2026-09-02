<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('ticket'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high', 'critical'])],
            'category_id' => ['sometimes', 'nullable', 'exists:categories,id'],
            'assigned_agent_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'sla_policy_id' => ['sometimes', 'nullable', 'exists:sla_policies,id'],
        ];
    }
}