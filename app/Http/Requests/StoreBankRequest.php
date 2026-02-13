<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin only, usually middleware handles this
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'deadline' => 'required|string|max:255', // e.g., '24 hours'
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
