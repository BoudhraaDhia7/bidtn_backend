<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRequest;
use App\Models\JetonTransaction;
use App\Repositories\UserRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ChangeJetonController extends Controller
{   
    use GlobalResponse;

    public function __invoke(ExchangeRequest $request): JsonResponse
    {   
        $user = auth()->user();
        $data = $this->getAttributes($request);
        $this->checkAuthrization($data['amount'] , $user);
        $response = UserRepository::ExchangeJeton($data['amount'] , $user);
        return $this->GlobalResponse('amount_exchanged', Response::HTTP_OK, $response);
    }

    private function checkAuthrization($amount, $user)
    {   
        if ($user->cannot('exchangeJeton', [JetonTransaction::class, $amount])) {
            abort($this->GlobalResponse('fail_exchange', Response::HTTP_UNAUTHORIZED));
        }
    }

    private function getAttributes(ExchangeRequest $request): array
    {   
        return [
            'amount' => $request->amount,
        ];
        
    }

}
