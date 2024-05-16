<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::routes(['prefix' => 'api', 'middleware' => ['user.auth']]);

Broadcast::channel('auction.{auctionId}', function ($auctionId) {
    //TODO- Add a reakl join condition 
    return true;
});

Broadcast::channel('current.{auctionId}', function ($auctionId) {
    //TODO- Add a reakl join condition 
    return true;
});
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


