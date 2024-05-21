<?php

namespace App\Http\Controllers\Api\Jetons;

use App\Helpers\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\JetonPack;
use App\Repositories\JetonRepository;
use App\Traits\GlobalResponse;

class DeleteJetonPackController extends Controller
{   
    use GlobalResponse;

    public function __invoke($id): JsonResponse
    {   
        $this->checkAuthrization();
        $jetonPack = JetonPack::findOrfail($id);
        try {
            JetonRepository::deleteJetonPack($jetonPack);
            return $this->GlobalResponse('jeton_pack_deleted', Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('UpdateJetonPackController: Error update jeton pack'. $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }

    private function checkAuthrization()
    {
        $user = auth()->user();

        if ($user->cannot('deleteJetonPack' , JetonPack::class)) {
            abort($this->GlobalResponse('failed_to_update_pack', Response::HTTP_UNAUTHORIZED));
        }
    }
}
