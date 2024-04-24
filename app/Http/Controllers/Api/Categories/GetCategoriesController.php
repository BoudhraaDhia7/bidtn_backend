<?php

namespace App\Http\Controllers\Api\Categories;

use App\Helpers\ResponseHelper;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Repositories\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;

class GetCategoriesController extends Controller
{
    use GlobalResponse;

    /**
     * Get all categories from the database.
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $response = CategoryRepository::index();
            return $this->GlobalResponse('category_retrived', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('GetCategoriesController: Error registering user' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }
}
