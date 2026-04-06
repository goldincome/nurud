<?php

namespace App\Services;

use App\Models\MarkupRule;

class MarkupService
{
    /**
     * Apply the first matching markup rule to a ticket price.
     */
    public function applyMarkup(float $ticketCost): float
    {
        $rules = MarkupRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($this->evaluateCondition($ticketCost, $rule->operator, $rule->threshold_price)) {
                return $rule->markup_type === 'percentage'
                    ? $ticketCost + ($ticketCost * ($rule->markup_value / 100))
                    : $ticketCost + $rule->markup_value;
            }
        }

        return $ticketCost;
    }

    public function getMarkupFee(float $ticketCost): float
    {
        $rules = MarkupRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($this->evaluateCondition($ticketCost, $rule->operator, $rule->threshold_price)) {
                return $rule->markup_type === 'percentage'
                    ? $ticketCost * ($rule->markup_value / 100)
                    : $rule->markup_value;
            }
        }

        return 0;
    }

    private function evaluateCondition($price, $operator, $threshold): bool
    {
        return match ($operator) {
            '>=' => $price >= $threshold,
            '<=' => $price <= $threshold,
            '>' => $price > $threshold,
            '<' => $price < $threshold,
            '==' => $price == $threshold,
            default => false,
        };
    }
}