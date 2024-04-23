<?php

namespace App\Http\Controllers\Api\Jetons;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\JetonRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\GlobalResponse;

class ListJetonPackController extends Controller
{
    use GlobalResponse;
    public function __invoke(): JsonResponse
    {   
        try {
            $response = JetonRepository::getJetonPacks();
            return $this->GlobalResponse('jeton_pack_retrived', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('ListJetonPackController: Error listing jeton pack'. $e->getMessage());
            return $this->GlobalResponse('general_error' ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
