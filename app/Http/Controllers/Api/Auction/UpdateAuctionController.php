<?php
// AuctionController.php

namespace App\Http\Controllers\Api\Auction;

use OpenApi\Attributes as OA;

use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use App\Repositories\AuctionRepository;
use App\Http\Requests\StoreAuctionRequest;

class UpdateAuctionController
{
    /**
     * Store a new auction in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    use GlobalResponse;
    #[OA\Post(
        path: "/api/auctions/{id}",
        tags: ["Auction"],
        summary: "Update a new auction",
        description: "Update a new auction with specified details. Requires user authentication and returns the created auction's data.",
        requestBody: new OA\RequestBody(
            description: "Auction details required to create a new auction",
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["title", "description", "starting_price", "start_date", "end_date", "starting_user_number"],
                        properties: [
                            new OA\Property(
                                property: "title",
                                type: "string",
                                description: "Title of the auction"
                            ),
                            new OA\Property(
                                property: "description",
                                type: "string",
                                description: "Detailed description of the auction"
                            ),
                            new OA\Property(
                                property: "starting_price",
                                type: "number",
                                format: "double",
                                description: "Starting price of the auction"
                            ),
                            new OA\Property(
                                property: "start_date",
                                type: "string",
                                format: "date-time",
                                description: "The start date and time of the auction"
                            ),
                            new OA\Property(
                                property: "end_date",
                                type: "string",
                                format: "date-time",
                                description: "The end date and time of the auction"
                            ),
                            new OA\Property(
                                property: "starting_user_number",
                                type: "integer",
                                description: "The minimum number of users required for the auction to start"
                            ),
                            new OA\Property(
                                property: "is_confirmed",
                                type: "boolean",
                                description: "Status to confirm if the auction is officially active",
                                default: false
                            ),
                            new OA\Property(
                                property: "is_finished",
                                type: "boolean",
                                description: "Status to indicate if the auction has finished",
                                default: false
                            )
                        ]
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "Auction created successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "title",
                                type: "string",
                                description: "Title of the auction"
                            ),
                        ],
                        example: [
                            "title"=> "Vintage Camera Auction",
                            "description"=> "Auctioning a collection of vintage cameras from the 1950s.",
                            "starting_price"=> 100.00,
                            "start_date"=> "2023-01-01T12:00:00Z",
                            "end_date"=> "2023-01-05T12:00:00Z",
                            "starting_user_number"=> 5,
                            "is_confirmed"=> true,
                            "is_finished"=> false
                            ]
                    )
                )
            ),
            new OA\Response(
                response: "400",
                description: "Invalid input",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message indicating the reason for the failure of auction creation"
                            )
                        ],
                        example: [
                            "error"=> "auction_creation_failed"
                            ]
                    )
                )
            )
        ]
    )]
    
    public function __invoke(StoreAuctionRequest $request , $id)
    {        
        try {
            $user = AuthHelper::currentUser();
            $validated = $this->getAttributes($request);
            $auction = AuctionRepository::updateAuction($validated['title'], $validated['description'],$validated['starting_price'],$validated['start_date'],$validated['end_date'],$validated['starting_user_number'],$validated['products'], $user , $id);
            return $this->GlobalResponse('auctions_updated', Response::HTTP_OK, $auction);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    private function getAttributes(StoreAuctionRequest $request): array
    {
        return [
            'title' => $request->title,
            'description' => $request->description,
            'starting_price' => $request->startingPrice,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'starting_user_number' => $request->startingUserNumber,
            'products' => $request->products
        ];
        
    }
}

