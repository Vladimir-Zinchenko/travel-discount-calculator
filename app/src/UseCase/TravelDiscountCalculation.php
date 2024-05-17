<?php

namespace App\UseCase;

use App\UseCase\Discount\DiscountStrategyInterface;

/**
 * Class TravelDiscountCalculation
 */
class TravelDiscountCalculation
{
    /**
     * @var DiscountStrategyInterface[]
     */
    private array $discounts = [];

    /**
     * @param int $basicPrice
     */
    public function __construct(private readonly int $basicPrice) {}

    /**
     * @param int $basicPrice
     *
     * @return TravelDiscountCalculation
     */
    public static function factory(int $basicPrice): TravelDiscountCalculation
    {
        return new self($basicPrice);
    }

    /**
     * @param DiscountStrategyInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountStrategyInterface $discount): TravelDiscountCalculation
    {
        $this->discounts[] = $discount;

        return $this;
    }

    /**
     * @return int
     */
    public function calculate(): int
    {
        $discount = 0;

        foreach ($this->discounts as $discountStrategy) {
            $discount += $discountStrategy->calculate($this->basicPrice);
        }

        return $discount;
    }
}