<?php

namespace App\Http\Controllers\Api\Categories;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SubCategoriesController extends Controller
{   
    use GlobalResponse;
    public function __invoke(int $parentId): JsonResponse
    {
        try {
            $response = CategoryRepository::getSubCategories($parentId);
            return $this->GlobalResponse('category_retrived', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('SubCategoriesController: Error fetching sub-categories ' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }
}