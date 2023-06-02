@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
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
                    <form class="card" id="validate-form" action="{{route('Trailers.update',$trailer->asset_id)}}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                {{__('edit trailer')}}
                            </div>

                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> {{__('Trailer Name')}} </label>
                                                <input type="text" class="form-control"
                                                       name="asset_name_ar" placeholder="{{__('Trailer Name')}}"
                                                       required value="{{$trailer->asset_name_ar}}">

                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> {{__('Asset Type')}}</label>
                                                <input type="text" class="form-control"
                                                       value="{{app()->getLocale() == 'ar' ? $sys_code_type->system_code_name_ar : $sys_code_type->system_code_name_en}}"
                                                       readonly>

                                                <input type="hidden" class="form-control" name="asset_type"
                                                       value="{{$sys_code_type->system_code_id}}">

                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient"
                                                       class="col-form-label"> {{__('Trailer Status')}} </label>
                                                <select class="form-select form-control" name="asset_status"
                                                        aria-label="Default select example" required>
                                                    <option value="" selected></option>
                                                    <option value="1" @if($trailer->asset_status == 1) selected @endif>
                                                        فعال
                                                    </option>
                                                    <option value="0" @if($trailer->asset_status == 0) selected @endif>
                                                        غير فعال
                                                    </option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient"
                                                       class="col-form-label">{{__('Trailer Chasi')}}</label>
                                                <input type="text" class="form-control" name="asset_serial"
                                                       placeholder="{{__('Trailer Chasi')}}"
                                                       value="{{$trailer->asset_serial}}" required>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="recipient"
                                                       class="col-form-label"> {{__('Manufactuer Company')}} </label>
                                                <select class="form-select form-control"
                                                        name="asset_manufacture" required
                                                        aria-label="Default select example">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_manufactuer as $sys_code_manufactuer)
                                                        <option value="{{$sys_code_manufactuer->system_code_id}}"
                                                                @if($trailer->asset_manufacture == $sys_code_manufactuer->system_code_id)
                                                                selected @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_manufactuer->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_manufactuer->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label">{{__('Trailer Model')}}</label>
                                                <input type="number" class="form-control" name="asset_model"
                                                       placeholder="{{__('Trailer Model')}}" required
                                                       value="{{$trailer->asset_model}}">

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="recipient"
                                                       class="col-form-label">{{__('trailer ownership status')}}</label>
                                                <select class="form-select form-control"
                                                        name="asset_owner_status"
                                                        aria-label="Default select example" required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_ownership_status as $sys_code_ownership_status)
                                                        <option value="{{$sys_code_ownership_status->system_code_id}}"
                                                                @if($trailer->asset_owner_status) selected @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_ownership_status->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_ownership_status->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_ownership') </label>
                                                <select class="form-select form-control" name="asset_owner" required>
                                                    <option value="" selected>choose</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{$supplier->customer_id }}"
                                                                @if($trailer->asset_owner == $supplier->customer_id)
                                                                selected @endif>
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $supplier->customer_name_full_ar }}
                                                            @else
                                                                {{ $supplier->customer_name_full_en }}
                                                            @endif

                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="mb-3">

                                        <div class="card">
                                            <div class="card-header">
                                                @if(isset($attachment))
                                                    <img src="{{ asset('Trailers/'.$attachment->attachment_file_url) }}">
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <input type="file" id="dropify-event"
                                                       name="image">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="submit">@lang('trucks.save')</button>
                                <div class="spinner-border" role="status" style="display: none">
                                    <span class="sr-only">Loading...</span>


                                </div>
                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection