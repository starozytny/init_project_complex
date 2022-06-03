<?php

namespace App\Controller\Api\Stripe;

use App\Service\ApiResponse;
use App\Service\Data\Main\DataChangelog;
use App\Service\ValidatorService;
use OpenApi\Annotations as OA;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api/stripe", name="api_stripe_")
 */
class StripeController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout", options={"expose"=true}, methods={"POST"})
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns a new object"
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="JSON empty or missing data or validation failed",
     * )
     *
     * @OA\Tag(name="Changelogs")
     *
     * @return RedirectResponse
     * @throws ApiErrorException
     */
    public function create(): RedirectResponse
    {
        \Stripe\Stripe::setApiKey('sk_test_51Kb5F2HZRd7sklEaGiVozh4jIRk65gWOCFqZqNFJCDUTkDmlZItmyvHEcm7rvdCnca7xuVNZMrIdfqNIRzEq1CiB00pLy13SpI');

        header('Content-Type: application/json');

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price' => 'price_1L6ZkEHZRd7sklEa3nYeg9Q6',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);

        return $this->redirect($checkout_session->url);
    }
}
