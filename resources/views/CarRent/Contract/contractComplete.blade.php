@extends('Layouts.master')
@section('content')


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                <div class="section-wrapper section-custom section-custom-2">
                    <div class="center-block text-center margin-50 p-5">
                        <img src="{{asset('images/check.svg')}}" alt="" width="75">
                    </div>
                    <div class="center-block text-center margin-50">
                        <p style="font-size: 20px">
                            @if(app()->getLocale() == 'en')
                                The Contract has been saved to number ==> "<span class="text-capitalize"> {{$contract->contract_code}}</span> "
                                <br>
                                <br>

                                Do you want to send it to NAQL ??
                            @else
                                تم حفظ العقد برقم ==> " <span class="text-capitalize">{{$contract->contract_code}}</span> "
                                <br>
                                <br>

                                هل تريد ارسال العقد الي نقل ؟؟
                            @endif
                        </p>
                    </div>

                    <div class="center-block text-center margin-top-50 p-4">
                        <button type="submit" class="btn btn-primary m-3">
                            {{ __('home.send') }}
                        </button>

                        <a href="#" class="btn btn-primary m-3">
                            <i class="fa fa-print"></i>
                            {{ __('home.print') }}
                        </a>

                        <a href="{{route('car-rent.index')}}" class="btn btn-primary m-3" >
                            {{ __('home.go_home') }}
                        </a>

                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection