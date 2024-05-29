<?php

namespace App\Http\Controllers\Api\Stripe;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CreateCheckoutSessionRequest;
use App\Repositories\PaymentRepository;
use App\Traits\GlobalResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class CreateCheckoutSessionController
{
    use GlobalResponse;

    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(CreateCheckoutSessionRequest $request)
    {
        $data = $this->getAttributes($request);

        try {
            $session = $this->paymentRepository->createCheckoutSession($data['packId']);
            return $this->GlobalResponse('checkout_session_created', Response::HTTP_OK, [$session]);
        } catch (\Exception $e) {
            Log::error('CreateCheckoutSessionController: Failed_to_create_session ' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }

    private function getAttributes(CreateCheckoutSessionRequest $request): array
    {
        return [
            'packId' => $request->input('packId'),
        ];
    }
}
