<?php

namespace App\UseCase\Discount;

/**
 * Interface DiscountStrategyInterface
 */
interface DiscountStrategyInterface
{
    /**
     * @param int $price
     *
     * @return int
     */
    public function calculate(int $price): int;
}