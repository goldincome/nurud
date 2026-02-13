<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'emails' => 'nullable|string', // Comma separated emails
            'phone_number' => 'required|string|max:50',
            'support_email' => 'required|email|max:255',
            'contact_email' => 'required|email|max:255',
        ];
    }
}
