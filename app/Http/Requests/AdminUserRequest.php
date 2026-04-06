<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CustomerType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only super admins can manage admins
        return $this->user()->type === CustomerType::SUPERADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $adminUser = $this->route('admin');
        $userId = ($adminUser instanceof \App\Models\User) ? $adminUser->id : $adminUser;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'phone_no' => ['nullable', 'string', 'max:20'],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        } else {
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
        }

        return $rules;
    }
}
