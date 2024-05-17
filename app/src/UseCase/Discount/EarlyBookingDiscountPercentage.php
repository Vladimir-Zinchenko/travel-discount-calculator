<?php

namespace App\UseCase\Discount;

use App\UseCase\Discount\Dto\EarlyBookingPaymentRangeDto;
use DateTime;
use InvalidArgumentException;

/**
 * Class EarlyBookingDiscountPercentage
 */
class EarlyBookingDiscountPercentage
{
    /**
     * @var EarlyBookingPaymentRangeDto[]
     */
    private array $paymentRanges = [];

    private ?DateTime $travelBeginRange;

    private ?DateTime $travelEndRange;

    /**
     * @param DateTime $paymentDate
     * @param DateTime $travelDate
     */
    public function __construct(private readonly DateTime $paymentDate, private readonly DateTime $travelDate) {}

    /**
     * @param DateTime $paymentDate
     * @param DateTime $travelDat
     *
     * @return EarlyBookingDiscountPercentage
     */
    public static function factory(DateTime $paymentDate, DateTime $travelDat): EarlyBookingDiscountPercentage
    {
        return new self($paymentDate, $travelDat);
    }

    /**
     * @param EarlyBookingPaymentRangeDto $range
     *
     * @return $this
     */
    public function addPaymentRange(EarlyBookingPaymentRangeDto $range): EarlyBookingDiscountPercentage
    {
        if ($range->begin === null && $range->end === null) {
            throw new InvalidArgumentException('The begin and end of the payment range cannot be null');
        }

        $this->paymentRanges[] = $range;

        return $this;
    }

    /**
     * @param DateTime|null $begin
     * @param DateTime|null $end
     *
     * @return $this
     */
    public function setTravelRange(?DateTime $begin, ?DateTime $end): EarlyBookingDiscountPercentage
    {
        if ($begin === null && $end === null) {
            throw new InvalidArgumentException('The begin and end of the travel range cannot be null');
        }

        $this->travelBeginRange = $begin;
        $this->travelEndRange = $end;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTravelInRange(): bool
    {
        return $this->checkDateInRange($this->travelDate, $this->travelBeginRange, $this->travelEndRange);
    }

    /**
     * @return int
     */
    public function getPercentage(): int
    {
        $percentage = 0;

        if ($this->isTravelInRange()) {
            foreach ($this->paymentRanges as $paymentRange) {
                if ($this->checkDateInRange($this->paymentDate, $paymentRange->begin, $paymentRange->end)) {
                    $percentage = $paymentRange->percentage;
                    break;
                }
            }
        }

        return $percentage;
    }

    /**
     * @param DateTime      $date
     * @param DateTime|null $startDate
     * @param DateTime|null $endDate
     *
     * @return bool
     */
    private function checkDateInRange(DateTime $date, ?DateTime $startDate, ?DateTime $endDate): bool
    {
        $dateTs = $date->getTimestamp();
        $starTs = $startDate?->getTimestamp();
        $endTs = $endDate?->getTimestamp();

        if ($startDate === null && $endDate === null) {
            throw new InvalidArgumentException('Start date or end date must be set');
        }

        if ($starTs === null && $dateTs <= $endTs) {
            return true;
        }

        if ($endTs === null && $dateTs >= $starTs) {
            return true;
        }

        if ($dateTs >= $starTs && $dateTs <= $endTs) {
            return true;
        }

        return false;
    }
}