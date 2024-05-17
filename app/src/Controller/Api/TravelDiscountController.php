<?php

namespace App\Controller\Api;

use App\Model\TravelDiscountModel;
use App\Service\TravelDiscountService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TravelDiscountController
 */
#[Route('/api/travel_discount', name: 'api_travel_discount')]
class TravelDiscountController extends AbstractController
{
    /**
     * @param TravelDiscountService $travelDiscountService
     */
    public function __construct(private readonly TravelDiscountService $travelDiscountService) {}

    /**
     * @param Request            $request
     * @param ValidatorInterface $validator
     *
     * @return Response
     *
     * @throws Exception
     */
    #[Route('', name: '_calculate', methods: ['POST'])]
    public function calculate(Request $request, ValidatorInterface $validator): Response
    {
        $discountModel =  TravelDiscountModel::fromArray(json_decode($request->getContent(), true));
        $errors = $validator->validate($discountModel);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $this->violationsToArray($errors)], Response::HTTP_BAD_REQUEST);
        }

        $priceWithDiscounts = $this->travelDiscountService->calculatePriceWithDiscount($discountModel);

        return new JsonResponse([
            'data' => [
                'price' => $priceWithDiscounts,
            ]
        ]);
    }

    private function violationsToArray(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}