@component('mail::message')
# New Auction

New operatunity to participate in an auction. Check it out! 

{{ $title }} has ben created and is waiting for you to join.

{{ config('app.name') }}

@component('mail::button', ['url' => env('FRONT_URL')])
Go to Dashboard
@endcomponent

@endcomponent
