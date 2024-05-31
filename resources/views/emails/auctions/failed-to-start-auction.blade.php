@component('mail::message')
# Failed to Start Auction

Auction: {{ $title }} has failed to start because the minimum number of users required to start the auction was not met.

{{ config('app.name') }}

@component('mail::button', ['url' => env('FRONT_URL')])
Go to Dashboard
@endcomponent

@endcomponent
