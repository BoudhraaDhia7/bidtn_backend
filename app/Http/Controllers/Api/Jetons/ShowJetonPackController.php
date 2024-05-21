<?php

namespace App\Http\Controllers\Api\Jetons;

use App\Http\Controllers\Controller;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

use App\Models\JetonPack;

class ShowJetonPackController extends Controller
{
    public function __invoke($id): JsonResponse
    {
        $this->checkAuthrization();
        $jetonPack = JetonPack::findOrfail($id);
        return $this->GlobalResponse('jeton_pack', Response::HTTP_OK, $jetonPack);
    }
}
