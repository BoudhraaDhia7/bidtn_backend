@component('mail::message')
# Completed Payment

Your payment has been completed successfully.

Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => env('FRONT_URL')])
Go to Dashboard
@endcomponent

@endcomponent
