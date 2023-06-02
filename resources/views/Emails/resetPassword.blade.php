@component('mail::message')
    # Reset Password

    Your Code To reset Password is <h2>{{ $code }}</h2>


    <br>Thanks,<br>
    {{ config('app.name') }}
@endcomponent
