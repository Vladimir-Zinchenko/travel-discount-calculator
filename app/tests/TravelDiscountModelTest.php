<?php

namespace App\Tests;

use App\Model\TravelDiscountModel;
use Exception;
use PHPUnit\Framework\TestCase;

class TravelDiscountModelTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateModelFromArray(): void
    {
        $basicPrice = 10000;
        $participantBirthday = '08.10.1995';
        $travelStartDate = '11.02.2025';
        $paymentDate = '16.05.2024';

        $model = TravelDiscountModel::fromArray([
            'basicPrice' => $basicPrice,
            'participantBirthday' => $participantBirthday,
            'travelStartDate' => $travelStartDate,
            'paymentDate' => $paymentDate
        ]);

        $this->assertEquals($model->basicPrice, $basicPrice);
        $this->assertEquals($model->participantBirthday, $participantBirthday);
        $this->assertEquals($model->travelStartDate, $travelStartDate);
        $this->assertEquals($model->paymentDate, $paymentDate);
    }
}
