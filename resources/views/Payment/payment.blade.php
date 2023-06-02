<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ config('app.name') }} </title>
    <style>
        .wpwl-control {
            height: 50px !important;
        }

        .wpwl-form-card {
            min-height: 150px !important;
            min-width: 300px !important;
            margin-top: 15% !important;
        }
    </style>
</head>

<body>
<!-- VISA MASTER AMEX -->
<form action="/api/payment/status/{{$paymethod}}" class="paymentWidgets"
      data-brands="@if($paymethod=='mada') MADA  @else VISA MASTER AMEX @endif"></form>


<script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$id}}"></script>
</body>
</html>
