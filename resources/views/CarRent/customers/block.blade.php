@extends('Layouts.master')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>

@endsection
@section('content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    {{-- Form To Create Customer--}}
                    <form class="card" id="validate-form" action="{{route('car-rent.customers.blockStore')}}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('home.block_customer')
                            </div>


                            <div class="mb-3">
                                <div class="row">


                                    <input type="hidden" name="customer_id" value="{{$customer->customer_id}}">
                                    {{--الاسم كامل عربي--}}
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_ar') </label>
                                        <input type="text" class="form-control " name="customer_name_full_ar"
                                               value="{{$customer->customer_name_full_ar}}"
                                               id="customer_name_full_ar" readonly>

                                    </div>

                                    {{--الاسم كامل انجليزي--}}
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_en') </label>
                                        <input type="text" class="form-control "
                                               value="{{$customer->customer_name_full_en}}"
                                               id="customer_name_full_en" readonly>
                                    </div>


                                    {{--رقم الهويه--}}
                                    <div class="col-md-6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.identity') </label>
                                        <input type="number" class="form-control"
                                               name="customer_identity"
                                               value="{{$customer->customer_identity}}"
                                               id="customer_identity"
                                               readonly>
                                    </div>

                                    {{--نوع الهويه--}}
                                    <div class="col-md-6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.identity_type') </label>
                                        <input type="text" name="id_type_code" class="form-control"
                                               @if($customer->TypeCode) value="{{app()->getLocale()== 'ar'
                                        ? $customer->TypeCode->system_code_name_ar
                                        : $customer->TypeCode->system_code_name_en}}" @endif readonly>
                                    </div>


                                    {{--سبب الحظر--}}
                                    @if($customer->customerBlock)
                                        <div class="col-md-6">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('home.block_reason') </label>
                                            <textarea name="customer_block_notes" class="form-control"
                                                      @if($customer->customerBlock->customer_block_status == 0)
                                                      required @endif
                                                      @if($customer->customerBlock->customer_block_status == 1)
                                                      readonly @endif>
                                            {{ $customer->customerBlock ?  $customer->customerBlock->customer_block_notes : ''}}
                                        </textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('home.unblock_reason') </label>
                                            <textarea name="customer_unblock_notes" class="form-control"
                                                      @if($customer->customerBlock->customer_block_status == 1)
                                                      required @endif
                                                      @if($customer->customerBlock->customer_block_status == 0)
                                                      readonly @endif>
                                            {{ $customer->customerBlock ?  $customer->customerBlock->customer_unblock_notes : ''}}
                                        </textarea>
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('home.block_reason') </label>
                                            <textarea name="customer_block_notes" class="form-control"
                                                      required></textarea>
                                        </div>
                                    @endif


                                </div>


                            </div>


                            <div class="card-footer">
                                @if($customer->customerBlock && $customer->customerBlock->customer_block_status == 1)
                                    <input type="hidden" name="customer_block_status" value="0">
                                    <button type="submit" class="btn btn-primary mr-2">@lang('home.unblock')</button>

                                @elseif($customer->customerBlock && $customer->customerBlock->customer_block_status == 0)
                                    <input type="hidden" name="customer_block_status" value="1">
                                    <button type="submit" class="btn btn-primary mr-2">@lang('home.block')</button>

                                @else
                                    <input type="hidden" name="customer_block_status" value="1">
                                    <button type="submit" class="btn btn-primary mr-2">@lang('home.block')</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection



