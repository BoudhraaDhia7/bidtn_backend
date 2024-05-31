@component('mail::message')
# New User Joined

{{ $joinedUsers }} / {{ $total }} user(s) have joined your auction.

{{ config('app.name') }}

@component('mail::button', ['url' => env('FRONT_URL')])
Go to Dashboard
@endcomponent

@endcomponent
