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
            'admin_email' => 'required|email|max:255',
            'notify_admin_new_reservation' => 'nullable|boolean',
            'notify_admin_stripe_no_ticket' => 'nullable|boolean',
            'notify_admin_247_api_down' => 'nullable|boolean',
            'notify_admin_simlesspay_api_down' => 'nullable|boolean',
        ];
    }

    /**
     * Prepare the data for validation (checkboxes send nothing if unchecked).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'notify_admin_new_reservation' => $this->has('notify_admin_new_reservation') ? 1 : 0,
            'notify_admin_stripe_no_ticket' => $this->has('notify_admin_stripe_no_ticket') ? 1 : 0,
            'notify_admin_247_api_down' => $this->has('notify_admin_247_api_down') ? 1 : 0,
            'notify_admin_simlesspay_api_down' => $this->has('notify_admin_simlesspay_api_down') ? 1 : 0,
        ]);
    }
}
