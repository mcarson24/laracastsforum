@component('mail::message')
# One Last Step
## And you'll be good to go.

Hey {{$user->name}},

We just need you to confirm your email address to prove that you are hooman.

It's all good right?

@component('mail::button', ['url' => url('register/confirm?token=' . $user->confirmation_token)])
Confirm Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
