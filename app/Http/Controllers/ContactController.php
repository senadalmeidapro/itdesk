<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\NewContactMessageNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $serviceSlug = (string) $request->input('service_slug');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'audience' => ['required', Rule::in(['particulier', 'entreprise'])],
            'service_slug' => ['nullable', Rule::in(array_column(config('public-services.services'), 'slug'))],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'form_data' => ['nullable', 'array'],
        ];

        foreach (config('service-form-fields.'.$serviceSlug, []) as $field) {
            $fieldRules = $field['required'] ? ['required'] : ['nullable'];
            $fieldRules[] = match ($field['type']) {
                'number' => 'integer',
                'select' => Rule::in(array_keys($field['options'] ?? [])),
                'textarea' => 'string|max:5000',
                default => 'string|max:255',
            };
            if ($field['type'] === 'number') {
                $fieldRules[] = 'min:1';
                $fieldRules[] = 'max:999999';
            }
            $rules['form_data.'.$field['name']] = $fieldRules;
        }

        $data = $request->validate($rules);

        $schema = $serviceSlug ? config('service-form-fields.'.$serviceSlug, []) : [];
        $formData = array_intersect_key(
            $data['form_data'] ?? [],
            array_flip(array_column($schema, 'name')),
        );
        $formData = array_filter($formData, fn ($value): bool => $value !== null && $value !== '');

        $contactMessage = ContactMessage::create([
            ...Arr::except($data, ['form_data']),
            'form_data' => $formData,
        ]);

        $admins = User::permission('settings.manage')->get();
        Notification::send($admins, new NewContactMessageNotification($contactMessage));

        return back()->with('status', __('Votre demande a bien été envoyée ! Nous revenons vers vous sous 24 h.'));
    }
}
