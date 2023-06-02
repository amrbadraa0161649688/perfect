@extends('Layouts.Tracking.tracking-layout')
@section('content')
    <!-- Tracking-->
    <div class="container">
        <div class="stepper">

            <h1 class="section-title tracking-title">{{__('Your order will reach you soon')}}</h1>
            <div class="plate-num">{{__('Plate number') . ' '}}
                <span>{{$waybill_hd->Wdetails ? $waybill_hd->Wdetails->waybill_car_plate : ''}}</span>
            </div>
            <div class="shipping-site">{{__('Shipping site') . ' '}}<span>{{app()->getLocale() == 'ar' ? $waybill_hd->locFrom->system_code_name_ar :
            $waybill_hd->locFrom->system_code_name_en}}</span></div>
            <ul class="step-wrapper">
                <li class="active">
                    <div class="step-icon">
                        <i class="fal fa-clipboard-list"></i>
                    </div>
                    <div class="step-details">
                        <div class="step-text">{{__('Ordered')}}</div>
                        <div class="step-date">{{$waybill_hd->created_date->format('Y-m-d')}}</div>
                    </div>
                    <div class="step-shape"></div>
                </li>

                @foreach($waybill_hd->statusM as $k=>$st)
                    <li class="active @if($k+1 == $waybill_hd->statusM->count())current @endif">
                        <div class="step-icon">
                            <i class="fal {{$st->system_code_url}}"></i>
                        </div>
                        <div class="step-details">
                            <div class="step-text">@if($st->system_code == 41004) جاهزه للشحن @else
                                    {{app()->getLocale() == 'ar' ? $st->system_code_name_ar :
                            $st->system_code_name_en}} @endif</div>
                            <div class="step-date">{{Carbon\Carbon::parse(\App\Models\WaybillStatus::where('waybill_id',$waybill_hd->waybill_id)
                            ->where('status_id',$st->system_code_id)->first()->status_date)->format('Y-m-d')}}</div>
                        </div>
                        <div class="step-shape"></div>
                    </li>
                @endforeach

            </ul>
            <div class="order-detailed">{{__('Detailed order updates')}}</div>
            <div class="order-last-update">
                {{__('Last update:')}}<span>{{ $waybill_hd->updated_date->format('Y-m-d') }}</span>
            </div>
            <div class="order-status">
                {{__('Waybill Code:')}} <span>{{ $waybill_hd->waybill_code }}</span>
            </div>
        </div>
    </div>
@endsection