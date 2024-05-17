<?php

namespace App\Service;

use App\Model\TravelDiscountModel;
use App\UseCase\Discount\ChildDiscount;
use App\UseCase\Discount\EarlyBookingDiscount;
use App\UseCase\TravelDiscountCalculation;
use DateTime;
use Exception;

/**
 * Class TravelDiscountService
 */
class TravelDiscountService
{
    /**
     * @param TravelDiscountModel $travelDiscountModel
     * @return int
     *
     * @throws Exception
     */
    function calculatePriceWithDiscount(TravelDiscountModel $travelDiscountModel): int
    {
        $discount = TravelDiscountCalculation::factory($travelDiscountModel->basicPrice)
            ->addDiscount(new ChildDiscount(new DateTime($travelDiscountModel->participantBirthday)))
            ->addDiscount(
                new EarlyBookingDiscount(
                    new DateTime($travelDiscountModel->travelStartDate),
                    new DateTime($travelDiscountModel->paymentDate)
                ))
            ->calculate();

        return round($travelDiscountModel->basicPrice - $discount);
    }
}