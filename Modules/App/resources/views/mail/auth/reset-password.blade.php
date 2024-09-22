@component('mail::message')

<h1>Hello {{ $user->name }}</h1>

<p>You are receiving this email because we received a password reset request for your account.</p>

@component('mail::button', ['url' => "$url/$token"])
    Reset Password
@endcomponent

<p>If you did not request a password reset, no further action is required.</p>
<p>Thanks, {{ config('app.name') }}</p>
@endcomponent