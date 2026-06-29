<?php

namespace App\Services;


class VerifiedPriceService
{
    public function getVerifiedPrice(array $bookingData): float
    {
        $verifiedPrice = $bookingData['price_changed']  ? $bookingData['verified_price'] : $bookingData['original_price'];
        if($bookingData['price_changed'] && $bookingData['verified_price'] < $bookingData['original_price']){
            $verifiedPrice = $bookingData['original_price'];
        }

        return $verifiedPrice;
    }

    public function getGroupTotalPrice(array $flightData): float
    {
        $groupTotal = 0;
         $isNgnCurrency = isset($flightData['currency']) && strtoupper($flightData['currency']) === 'NGN';
         $groupedDetails = collect($flightData['travelerPricings'])->groupBy('travelerType');
          foreach($groupedDetails as $group){
                                 
            $totalGroupPrice = $group->sum(function ($traveler) {
                return $traveler['price']['base'];
            });                  

            $groupTotal += $totalGroupPrice;
          }
    
        return $groupTotal;
    }

}