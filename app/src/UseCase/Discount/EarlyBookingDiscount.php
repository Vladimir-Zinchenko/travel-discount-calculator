<?php

namespace App\UseCase\Discount;

use App\UseCase\Discount\Dto\EarlyBookingPaymentRangeDto;
use DateTime;

/**
 * Class EarlyBookingDiscount
 */
readonly class EarlyBookingDiscount implements DiscountStrategyInterface
{
    /**
     * @param DateTime $travelStartDate
     * @param DateTime $paymentDate
     */
    public function __construct(private DateTime $travelStartDate, private DateTime $paymentDate) {}

    /**
     * @param int $price
     *
     * @return int
     */
    public function calculate(int $price): int
    {
        $discount = $price * $this->getPercentage() / 100;

        if ($discount > 1500) {
            $discount = 1500;
        }

        return $discount;
    }

    /**
     * @return int
     */
    private function getPercentage(): int
    {
        $percentage = 0;

        foreach ($this->getStrategies() as $strategy) {
            if ($strategy->isTravelInRange()) {
                $percentage = $strategy->getPercentage();

                break;
            }
        }


        return $percentage;
    }

    /**
     * @return EarlyBookingDiscountPercentage[]
     */
    private function getStrategies(): array
    {
        $paymentYear = (int)$this->paymentDate->format('Y');
        $nextPaymentYear = $paymentYear + 1;
        $strategies = [];


        // From April 1 (next year) to September 30 (next year)
        $strategies[] = EarlyBookingDiscountPercentage::factory($this->paymentDate, $this->travelStartDate)
        ->setTravelRange(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.04.' . $nextPaymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '30.09.' . $nextPaymentYear . ' 23:59:59')
        )
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            null,
            DateTime::createFromFormat('d.m.Y H:i:s', '31.03.' . $paymentYear . ' 23:59:59'),
            7
        ))
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.04.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '30.04.' . $paymentYear . ' 23:59:59'),
            5
        ))
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.05.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '31.05.' . $paymentYear . ' 23:59:59'),
            3
        ));

        // From October 1 to January 14 (next year)
        $strategies[] = EarlyBookingDiscountPercentage::factory($this->paymentDate, $this->travelStartDate)
        ->setTravelRange(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.10.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '14.01.' . $nextPaymentYear . ' 23:59:59')
        )
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            null,
            DateTime::createFromFormat('d.m.Y H:i:s', '31.03.' . $paymentYear . ' 23:59:59'),
            7
        ))
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.04.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '30.04.' . $paymentYear . ' 23:59:59'),
            5
        ))
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.05.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '31.05.' . $paymentYear . ' 23:59:59'),
            3
        ));

        // From January 15 (next year) and later.
        $strategies[] = EarlyBookingDiscountPercentage::factory($this->paymentDate, $this->travelStartDate)
        ->setTravelRange(
            DateTime::createFromFormat('d.m.Y H:i:s', '15.01.' . $nextPaymentYear . ' 00:00:00'),
            null
        )
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            null,
            DateTime::createFromFormat('d.m.Y H:i:s', '31.08.' . $paymentYear . ' 23:59:59'),
            7
        ))
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.09.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '30.09.' . $paymentYear . ' 23:59:59'),
            5
        ))
        ->addPaymentRange(new EarlyBookingPaymentRangeDto(
            DateTime::createFromFormat('d.m.Y H:i:s', '01.10.' . $paymentYear . ' 00:00:00'),
            DateTime::createFromFormat('d.m.Y H:i:s', '31.10.' . $paymentYear . ' 23:59:59'),
            3
        ));

        return $strategies;
    }
}