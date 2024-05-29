<?php

namespace App\Http\Controllers\Api\Stripe;

use App\Helpers\ResponseHelper;
use App\Repositories\PaymentRepository;
use App\Traits\GlobalResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Webhook;
use Stripe\Stripe;

class WebhookController
{
    protected $paymentRepository;

    use GlobalResponse;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(Request $request)
    {
        Stripe::setApiKey(config('stripe.stripe.secret_key'));

        $endpointSecret = config('stripe.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $this->paymentRepository->handleCheckoutSessionCompleted($session->id);
            }

            if ($event->type === 'payment_intent.payment_failed') {
                $paymentIntent = $event->data->object;
                $this->paymentRepository->handlePaymentFailed($paymentIntent->id);
            }

            return $this->GlobalResponse('webhook_handled', Response::HTTP_OK, $event);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }
}
