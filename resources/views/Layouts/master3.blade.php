<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="cache-control" content="no-store" />
    <meta http-equiv="cache-control" content="must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{--<link rel="icon" href="" type="image/x-icon"/>--}}

    <title>@yield('title') - {{ config('app.name') }} </title>

    <!-- Bootstrap Core and vandor -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}"/>

    <!-- Plugins css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/charts-c3/c3.min.css') }}"/>

    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">

    <style>
        td.details-control {
            background: url('{{ asset('assets/images/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url('{{ asset('assets/images/details_close.png') }}') no-repeat center center;
        }
        .hidden{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/theme1.css') }}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}"/>

    @yield('style')
</head>

<body class="font-montserrat sidebar_dark @if(app()->getLocale() == 'ar')) rtl @else '' @endif">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">

    </div>

</div>


<div id="main_content">


    <div class="row">

        <div class="card">

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                            aria-expanded="false">{{ app()->getLocale() }}</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('locale','en') }}"><img class="w20 mr-2"
                                                                                                src="{{ asset('assets/images/flags/us.svg') }}">English</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('locale','ar') }}"><img class="w20 mr-2"
                                                                                                src="{{ asset('assets/images/flags/sa.svg') }}">عربي</a>
                            </div>
                        </li>




        </div>

        <div class="col-md-4">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="dropdown-item">
                    <i class="dropdown-icon fe fe-log-out"></i>@lang('home.sign_out')</button>
            </form>
        </div>


     </div>


    <div class="page">

        <div class="row">
            <div class="col-12 col-md-12 col-xl-9">
                <div class="card">
                    <div class="row">

                                    @if(session('company'))

                                <div class="avatar avatar-blue" data-toggle="tooltip"
                                    data-placement="top" style="position:absolute;left:50px;"
                                    title="" data-original-title="Avatar Name">
                                    <img class="avatar avatar-blue" src="{{session('company')['company_logo']}}">
                                </div>
                                <p style="font-size:15px;font-weight: bold;margin-left:5px;position:absolute;left:120px;">
                                    @if(app()->getLocale()=='ar'){{ session('company')['company_name_ar'] }}
                                    @else{{ session('company')['company_name_en'] }} @endif
                                </p>
                            @else
                                <div class="avatar avatar-blue" data-toggle="tooltip"
                                    data-placement="top" style="position:absolute;right:50px;"
                                    title="" data-original-title="Avatar Name">
                                    <img class="avatar avatar-blue" src="{{auth()->user()->company->company_logo}}">
                                </div>
                                <p style="font-size:20px;font-weight: bold;margin-right:10px;position:absolute;right:120px;">
                                    @if(app()->getLocale()=='ar'){{ auth()->user()->company->company_name_ar }}
                                    @else{{ auth()->user()->company->company_name_en }} @endif
                                </p>
                            @endif



                        </div>
                    </div>
            </div>


        </div>


        <div class="row">
            <div class="mt-5">
                <div class="line color-red">


                </div>
            </div>

        </div>

        <div class="row">


            <div class="col-md-8">


                @if(auth()->user()->user_type_id != 1)
                <p style="font-size:15px;font-weight: bold;position:absolute;left:200px">
                    @if(app()->getLocale()=='ar'){{ session('branch')['branch_name_ar'] }}
                    @else {{ session('branch')['branch_name_en']  }} @endif
                </p>
            @else
                <p style="font-size:15px;font-weight: bold;position:absolute">
                    @if(app()->getLocale()=='ar'){{ session('company_group')['company_group_ar'] }}
                    @else {{ session('company_group')['company_group_en']  }} @endif
                </p>
            @endif
            </div>


        </div>


        <div class="row">


            <div class="col-md-8">


                @if(auth()->user()->user_type_id != 1)
                <p style="font-size:15px;font-weight: bold;position:absolute;left:200px">
                    @if(app()->getLocale()=='ar'){{ session('branch')['branch_name_ar'] }}
                    @else {{ session('branch')['branch_name_en']  }} @endif
                </p>
            @else
                <p style="font-size:15px;font-weight: bold;position:absolute">
                    @if(app()->getLocale()=='ar'){{ session('company_group')['company_group_ar'] }}
                    @else {{ session('company_group')['company_group_en']  }} @endif
                </p>
            @endif
            </div>


        </div>


        <div class="row">
            <div class="mt-4">
                <p style="font-size:15px;font-weight: bold;position:absolute;left:200px">
                    @if(app()->getLocale()=='ar'){{ auth()->user()->user_name_ar }}
                    @else{{ auth()->user()->user_name_en }} @endif
                </p>


                </div>
            </div>

        </div>

        <div class="row">
            <div class="mt-5">
                <div class="line color-red">


                </div>
            </div>

        </div>



        <div id="page_top" class="section-body ">
            <div class="container-fluid">
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 mr-auto ml-auto">
                @include('Includes.flash-messages')
            </div>

        </div>




                @yield('content')

    </div>


</div>


<script src="{{ asset('assets/bundles/lib.vendor.bundle.js') }}"></script>

<script src="{{ asset('assets/bundles/apexcharts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/counterup.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/knobjs.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/c3.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/dataTables.bundle.js') }}"></script>
<script src="{{ asset('assets/js/core.js') }}"></script>
<script src="{{ asset('assets/js/datatable.js') }}"></script>
<script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-multiselect/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
@yield('scripts')
<script src="{{ asset('assets/js/index.js') }}"></script>
</body>
</html>

