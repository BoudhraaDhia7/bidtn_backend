<?php

namespace App\Http\Controllers\Api\Auction;

use App\Http\Controllers\Controller;
use App\Http\Requests\BidRequest;
use App\Models\Auction;
use App\Repositories\AuctionRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\Response;

class BidOnAuctionController extends Controller
{       
    use GlobalResponse;

    public function __invoke(BidRequest $request , $id)
    {
        $auction = Auction::findOrFail($id);
        $params = $this->getAttributes($request);
        $this->checkAuthrization($auction , $params['bidAmount']);
        
        try{
            AuctionRepository::bidOnAuction($auction, $params['bidAmount'], auth()->user());
            return $this->GlobalResponse('success_bid', Response::HTTP_OK);
        }
        catch(\Exception $e){
            return $this->GlobalResponse('fail_bid', Response::HTTP_BAD_REQUEST);
        }
    }

    private function getAttributes(BidRequest $request): array
    {
        return [
            'bidAmount' => $request->bidAmount,
        ];
    }

    private function checkAuthrization($auction,$bidAmount )
    {   

        $user = auth()->user();
        if ($user->cannot('bidOnAuction', [$auction , $bidAmount])) {
            abort($this->GlobalResponse('fail_bid', Response::HTTP_UNAUTHORIZED));
        }
    }
}
