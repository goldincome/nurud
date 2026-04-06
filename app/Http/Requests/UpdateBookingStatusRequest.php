<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\BookingStatus;
use Illuminate\Validation\Rule;

class UpdateBookingStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Admin or superadmin check handled by middleware usually, but good to be safe if needed
        return $this->user()->type === \App\Enums\CustomerType::ADMIN || $this->user()->type === \App\Enums\CustomerType::SUPERADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([BookingStatus::CONFIRMED->value, BookingStatus::CANCELLED->value])],
        ];
    }
}
