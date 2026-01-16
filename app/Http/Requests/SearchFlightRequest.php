<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchFlightRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Clean up data before validation.
     * Useful for checkboxes or extracting codes from strings like "Dallas, DFW".
     */
  /**
     * Clean up data before validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'directFlightOnly' => $this->boolean('directFlightOnly'),
            'dateWindow' => $this->boolean('dateWindow'),
        ]);

        // Clean up Multi-City inputs (Extract "JFK" from "New York, JFK")
        // We loop through a reasonable max number of segments (e.g., 10)
        $cleanedData = [];
        for ($i = 1; $i <= 10; $i++) {
            if ($this->has("originLocationCode$i")) {
                $cleanedData["originLocationCode$i"] = substr($this->input("originLocationCode$i"), -3);
            }
            if ($this->has("originDestinationCode$i")) {
                $cleanedData["originDestinationCode$i"] = substr($this->input("originDestinationCode$i"), -3);
            }
        }
        
        if (!empty($cleanedData)) {
            $this->merge($cleanedData);
        }
    }

    public function rules(): array
    {
        // 1. BASE RULES
        $rules = [
            'routeModel' => ['required', 'integer', Rule::in([0, 1, 2])],
            'flightClass' => ['required', 'string', Rule::in(['ECONOMY', 'PREMIUM_ECONOMY', 'BUSINESS', 'FIRST'])],
            'directFlightOnly' => ['boolean'],
            'travelers.numberOfAdults' => ['required', 'integer', 'min:1'], 
            'travelers.numberOfChildren' => ['nullable', 'integer', 'min:0'],
            'travelers.numberOfInfants' => ['nullable', 'integer', 'min:0'],
        ];

        // 2. CONDITIONAL RULES

        // ONE WAY (0) OR ROUND TRIP (1)
        if (in_array($this->routeModel, [0, 1])) {
            $rules['originLocationCode'] = ['required', 'string', 'size:3'];
            $rules['originDestinationCode'] = ['required', 'string', 'size:3', 'different:originLocationCode'];
            $rules['departureDate'] = ['required', 'date', 'after_or_equal:today'];
        }

        // ROUND TRIP ONLY (1)
        if ($this->routeModel == 1) {
            $rules['returnDate'] = ['required', 'date', 'after_or_equal:departureDate'];
            $rules['dateWindow'] = ['boolean'];
        }

        // MULTI-CITY (2)
        if ($this->routeModel == 2) {
            // Loop to dynamically generate rules for up to 10 segments
            for ($i = 1; $i <= 10; $i++) {
                // The first 2 segments are strictly REQUIRED
                if ($i <= 2) {
                    $rules["originLocationCode$i"] = ['required', 'string', 'size:3'];
                    $rules["originDestinationCode$i"] = ['required', 'string', 'size:3', "different:originLocationCode$i"];
                    $rules["departureDate$i"] = ['required', 'date', 'after_or_equal:today'];
                } else {
                    // Segments 3+ are optional (but if one field exists, the others in that row should ideally exist)
                    $rules["originLocationCode$i"] = ['nullable', 'string', 'size:3'];
                    $rules["originDestinationCode$i"] = ['nullable', 'string', 'size:3', "different:originLocationCode$i"];
                    $rules["departureDate$i"] = ['nullable', 'date', 'after_or_equal:today'];
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'originDestinationCode.different' => 'The destination cannot be the same as the origin.',
            'returnDate.after_or_equal' => 'The return date must be after or on the same day as departure.',
            'travelers.numberOfAdults.min' => 'At least one adult passenger is required.',
            'flights.required' => 'Please add at least two flight segments for a multi-city trip.',
        ];
    }
}