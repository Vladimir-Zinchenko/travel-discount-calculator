<?php

namespace App\UseCase\Discount\Dto;

use DateTime;

/**
 * Class EarlyBookingPaymentRangeDto
 */
readonly class EarlyBookingPaymentRangeDto
{
    /**
     * @param DateTime|null $begin
     * @param DateTime|null $end
     *
     * @param int $percentage
     */
    public function __construct(
        public ?DateTime $begin,
        public ?DateTime $end,
        public int $percentage
    ) {}
}