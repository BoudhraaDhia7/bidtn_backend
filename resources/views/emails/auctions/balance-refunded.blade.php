@component('mail::message')
# Balance Refunded

Your balance has been refunded.

{{ config('app.name') }}

@component('mail::button', ['url' => env('FRONT_URL')])
Go to Dashboard
@endcomponent

@endcomponent
