<?php

namespace App\Http\Controllers\Api\Auction;



use OpenApi\Attributes as OA;

use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;

class ShowAuctionController
{
    use GlobalResponse;
    use PaginationParams;

    /**
     * Get all auctions from the database.
        * @param Request $request
        * @return JsonResponse
            
     */
    #[
        OA\Get(
            path: '/api/auction',
            tags: ['Auction'],
            summary: 'List auctions',
            description: 'Retrieves a list of auctions based on filter, sort, and pagination parameters. Allows for extensive customization via query parameters.',
            parameters: [new OA\Parameter(name: 'filters', in: 'query', description: 'JSON string of filter conditions for fetching auctions, such as status or date ranges.', required: false, schema: new OA\Schema(type: 'string')), new OA\Parameter(name: 'order_by', in: 'query', description: 'Field to order the auctions by', required: false, schema: new OA\Schema(type: 'string')), new OA\Parameter(name: 'direction', in: 'query', description: 'Direction of the sort order (asc or desc)', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'], default: 'asc')), new OA\Parameter(name: 'page', in: 'query', description: 'Page number for pagination (required if pagination is enabled)', required: false, schema: new OA\Schema(type: 'integer')), new OA\Parameter(name: 'per_page', in: 'query', description: 'Number of auctions per page (required if pagination is enabled)', required: false, schema: new OA\Schema(type: 'integer', default: 10))],
            responses: [
                new OA\Response(response: '200', description: 'Auctions retrieved successfully', content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(type: 'object', properties: [new OA\Property(property: 'data', type: 'array', description: 'An array of auctions', items: new OA\Items(type: 'object', properties: [new OA\Property(property: 'id', type: 'integer', description: 'The unique identifier for the auction'), new OA\Property(property: 'title', type: 'string', description: 'Title of the auction'), new OA\Property(property: 'description', type: 'string', description: 'Description of the auction')]))]))),
                new OA\Response(
                    response: '400',
                    description: 'Invalid input, unable to process request',
                    content: new OA\MediaType(
                        mediaType: 'application/json',
                        schema: new OA\Schema(
                            type: 'object',
                            properties: [new OA\Property(property: 'error', type: 'string', description: 'Error message indicating what was wrong with the input')],
                            example: [
                                'error' => 'Invalid parameters',
                            ],
                        ),
                    ),
                ),
            ],
        ),
    ]

    public function __invoke($id): JsonResponse
    {
        $auction = Auction::with(['product.media', 'product.categories'])->findOrFail($id);
        return $this->GlobalResponse('auction_retrieved', Response::HTTP_OK, [$auction]);
    }
  

}
