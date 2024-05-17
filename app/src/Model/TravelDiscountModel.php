<?php

namespace App\Model;

use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TravelDiscountModel
 */
readonly class TravelDiscountModel
{
    #[Assert\NotBlank()]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public int $basicPrice;

    #[Assert\NotBlank]
    #[Assert\DateTime('d.m.Y')]
    public string $participantBirthday;

    #[Assert\NotBlank]
    #[Assert\DateTime('d.m.Y')]
    public string $travelStartDate;

    #[Assert\NotBlank]
    #[Assert\DateTime('d.m.Y')]
    public string $paymentDate;

    /**
     * @param array $data
     *
     * @return TravelDiscountModel
     *
     * @throws Exception
     */
    public static function fromArray(array $data): TravelDiscountModel
    {
        $model = new self();

        $model->basicPrice = $data['basicPrice'] ?? 0;
        $model->participantBirthday = $data['participantBirthday'] ?? '';
        $model->travelStartDate = $data['travelStartDate'] ?? '';
        $model->paymentDate = $data['paymentDate'] ?? '';

        return $model;
    }
}