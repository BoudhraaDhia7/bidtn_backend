<?php

namespace App\Http\Controllers\Api\Jetons;

use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\JetonRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\GlobalResponse;

class ListJetonPackController extends Controller
{
    use GlobalResponse;

    /**
     * Get all jeton packs from the database.
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {   
        try {
            $response = JetonRepository::getJetonPacks();
            return $this->GlobalResponse('jeton_pack_retrived', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('ListJetonPackController: Error listing jeton pack'. $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

}
