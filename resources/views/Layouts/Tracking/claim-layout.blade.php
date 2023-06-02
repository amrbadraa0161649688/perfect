<!DOCTYPE html>

{{--@php--}}
    {{--$company=session('company') ?session('company') : auth()->user()->company;--}}
{{--$phone_number=substr($company->co_mobile_number,1)--}}
{{--@endphp--}}

<html lang="{{ app()->getLocale() }}">
<head>
    
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1,shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
         
    <link rel="icon" type="image/png" sizes="32x32" src="{{ asset('assets/images/main_logo.png') }}">
    <!-- Favicon -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/tracking/fonts/fontAwesome/css/all.min.css')}}">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{asset('assets/tracking/css/owl.carousel.min.css')}}" type="text/css"><!-- Owl -->
    <link rel="stylesheet" href="{{asset('assets/tracking/css/owl.theme.default.min.css')}}" type="text/css">
    <!-- Owl -->


    @if(app()->getLocale() =='ar')
        <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css"
              integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe"
              crossorigin="anonymous">
        <link rel="stylesheet" media="all" href="{{asset('assets/tracking/css/style.css')}}">
        <link rel="stylesheet" media="all" href="{{asset('assets/tracking/css/style-rtl.css')}}">
    @else
        <link rel="stylesheet" href="{{asset('assets/tracking/css/bootstrap.min.css')}}" type="text/css">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" media="all" href="{{asset('assets/tracking/css/style.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}"/>

    @endif
</head>
<body>
<!-- Header -->
<header>
    <!-- Navbar -->
    @include('Includes.Tracking.claim')

</header>

@yield('content')

<!-- Footer -->


<!-- Scripts-->
<script src="{{asset('assets/tracking/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/tracking/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/tracking/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/tracking/fonts/fontAwesome/js/fontawesome.min.js')}}"></script>
<script src="{{asset('assets/tracking/js/wow.min.js')}}"></script>
<script src="{{asset('assets/tracking/js/main.js')}}"></script>
@yield('scripts')

<script>

    (function () {
        var options = {
            whatsapp: "+966" + '505555470', // WhatsApp number
            call_to_action: "", // Call to action
            button_color: "#FF6550", // Color of button
            position: "left", // Position may be 'right' or 'left'
        };
        var proto = 'https:', host = "getbutton.io", url = proto + '//static.' + host;
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () {
            WhWidgetSendButton.init(host, proto, options);
        };
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    })();
</script>
</body>
</html>
