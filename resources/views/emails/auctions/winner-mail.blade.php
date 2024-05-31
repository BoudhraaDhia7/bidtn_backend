@component('mail::message')
# New Winned Auction

Congratulations! You have won the auction :{{ $title }}.

{{ config('app.name') }}

@component('mail::button', ['url' => env('FRONT_URL')])
Go to Dashboard
@endcomponent

@endcomponent
