<?php

namespace App\UseCase\Discount;

use DateTime;

/**
 * Class ChildDiscount
 */
readonly class ChildDiscount implements DiscountStrategyInterface
{
    public function __construct(private DateTime $participantBirthday) {}

    /**
     * @param int $price
     *
     * @return int
     */
    public function calculate(int $price): int
    {
        $currentDate = new DateTime();
        $age = $currentDate->diff($this->participantBirthday)->y;
        $percentage = $this->percentageByAge($age);
        $discount = $price * ($percentage / 100);

        if ($discount > 4500 && $age >= 6 && $age <= 12) {
            $discount = 4500;
        }

        return $discount;
    }

    /**
     * @param int $age
     *
     * @return int
     */
    private function percentageByAge(int $age): int
    {
        return match (true) {
            $age >= 3 && $age <= 6 => 80,
            $age >= 6 && $age <= 12 => 30,
            $age >= 12 && $age <= 18 => 10,
            default => 0
        };
    }
}