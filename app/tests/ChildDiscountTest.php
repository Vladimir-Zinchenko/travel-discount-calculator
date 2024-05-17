<?php

namespace App\Tests;

use App\UseCase\Discount\ChildDiscount;
use DateTime;
use PHPUnit\Framework\TestCase;

class ChildDiscountTest extends TestCase
{
    public function testDiscountByAge(): void
    {
        $price = 1000;

        foreach ($this->ageCases($price) as $case) {
            $discount = new ChildDiscount($case['birthday']);

            $this->assertEquals(
                $discount->calculate($price),
                $case['expectedPrice'],
                'Invalid calculation on date: ' . $case['birthday']->format('Y-m-d')
            );
        }
    }

    /**
     * @return array[]
     */
    private function ageCases(int $price): array
    {
        $now = new DateTime();

        return [
            [
                'birthday' => (clone $now)->modify('-4 years'),
                'expectedPrice' => $price * 0.8,
            ],
            [
                'birthday' => (clone $now)->modify('-7 years'),
                'expectedPrice' => $price * 0.3,
            ],
            [
                'birthday' => (clone $now)->modify('-13 years'),
                'expectedPrice' => $price * 0.1,
            ],
            [
                'birthday' => (clone $now)->modify('-20 years'),
                'expectedPrice' => $price * 0,
            ],
        ];
    }
}
