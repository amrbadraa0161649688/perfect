@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
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
                    <form class="card" id="validate-form"
                          action="{{ route('movements.update', $record->car_movement_id) }}"
                          method="post"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('home.edit_movement')
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    {{--car_movement_type_id--}}
                                    <div class="col-md-3 form-group">
                                        <label for="car_movement_type_id"
                                               class="col-form-label">@lang('home.car_movement_type_id')<span
                                                class="text-danger">*</span></label>
                                        <select data-live-search="true"
                                                class="form-control selectpicker @error('car_movement_type_id') is-invalid @enderror"
                                                disabled onchange="set_values()"
                                                name="car_movement_type_id" id="car_movement_type_id">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->system_code_id}}"
                                                        data-code="{{$type->system_code}}"
                                                        @if($type->system_code_id == $record->car_movement_type_id) selected @endif>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_movement_type_id')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--car_id--}}
                                    <div class="col-md-3 form-group">
                                        <label for="car_id"
                                               class="col-form-label">@lang('home.car_id')<span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('car_id') is-invalid @enderror" disabled
                                                data-live-search="true"
                                                name="car_id" id="car_id">
                                            <option value="{{$record->car_id}}">
                                                {{$record->car?$record->car->full_car_plate.'-'.$record->car->brand->name.'-'.$record->car->brandDetails->name:''}}
                                            </option>
                                        </select>
                                        @error('car_id')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--customer_id--}}
                                    <div class="col-md-3 form-group">
                                        <label for="car_movement_driver_id"
                                               class="col-form-label">@lang('home.driver')<span
                                                class="text-danger">*</span></label>
                                        <select data-live-search="true"
                                                class="form-control selectpicker @error('car_movement_driver_id') is-invalid @enderror"
                                                disabled
                                                name="car_movement_driver_id" id="car_movement_driver_id">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($drivers as $driver)
                                                <option value="{{ $driver->customer_id}}"
                                                        @if($driver->emp_id == $record->car_movement_driver_id) selected @endif>{{ $driver->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('car_movement_driver_id')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--follow_up_days_date--}}
                                    <div class="col-md-3 form-group">
                                        <label for="follow_up_days_date"
                                               class="col-form-label">@lang('home.follow_up_days_date')<span
                                                class="text-danger">*</span></label>
                                        <input type="date" disabled
                                               class="form-control @error('follow_up_days_date') is-invalid @enderror"
                                               name="follow_up_days_date"
                                               id="follow_up_days_date"
                                               value="{{date('Y-m-d', strtotime($record->follow_up_days_date))}}">
                                        @error('follow_up_days_date')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 2rem">
                                    <div class="col-md-6 col-12 row m-2"
                                         style="flex: 0 0 48% !important;padding: 1rem 0.5rem!important;border: 1px solid #07406261;border-radius: 5px;">
                                        <div class="col-12 title"
                                             style="padding:0px;color: #074062;position: absolute;top: -1.2rem;">{{__('home.start_data')}}</div>
                                        {{--car_movement_branch_open--}}
                                        <div class="col-md-6 form-group">
                                            <label for="car_movement_branch_open"
                                                   class="col-form-label">@lang('home.car_movement_branch_open')
                                                <span
                                                    class="text-danger">*</span></label>
                                            <select data-live-search="true"
                                                    class="form-control selectpicker @error('car_movement_branch_open') is-invalid @enderror"
                                                    disabled
                                                    name="car_movement_branch_open" id="car_movement_branch_open">
                                                <option selected value="{{$record->car_movement_branch_open}}">{{$record->branchOpen?$record->branchOpen->name:''}}</option>
                                            </select>
                                            @error('car_movement_branch_open')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--car_movement_start--}}
                                        <div class="col-md-6 form-group">
                                            <label for="car_movement_start"
                                                   class="col-form-label">@lang('home.car_movement_start')<span
                                                    class="text-danger">*</span></label>
                                            <input type="date" disabled
                                                   class="form-control @error('car_movement_start') is-invalid @enderror"
                                                   name="car_movement_start"
                                                   id="car_movement_start"
                                                   value="{{date('Y-m-d', strtotime($record->car_movement_start))}}">
                                            @error('car_movement_start')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--start_kilomaters--}}
                                        <div class="col-md-6 form-group">
                                            <label for="start_kilomaters"
                                                   class="col-form-label">@lang('home.start_kilomaters')<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" disabled
                                                   class="form-control @error('start_kilomaters') is-invalid @enderror"
                                                   name="start_kilomaters" value="{{$record->start_kilomaters}}"
                                                   id="start_kilomaters" min="1" step="1">
                                            @error('start_kilomaters')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--follow_up_days_start--}}
                                        <div class="col-md-6 form-group">
                                            <label for="follow_up_days_start"
                                                   class="col-form-label">@lang('home.follow_up_days_start')<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" disabled
                                                   class="form-control @error('follow_up_days_start') is-invalid @enderror"
                                                   name="follow_up_days_start"
                                                   id="follow_up_days_start" min="1" step="1"
                                                   value="{{$record->follow_up_days_start}}">
                                            @error('follow_up_days_start')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--car_movement_notes_open--}}
                                        <div class="col-md-12 form-group">
                                            <label for="car_movement_notes_open"
                                                   class="col-form-label">@lang('home.car_movement_notes_open')<span
                                                    class="text-danger">*</span></label>
                                            <textarea disabled
                                                      class="form-control @error('car_movement_notes_open') is-invalid @enderror"
                                                      name="car_movement_notes_open" rows="5"
                                                      placeholder="@lang('home.car_movement_notes_open')">{{$record->car_movement_notes_open}}</textarea>
                                            @error('car_movement_notes_open')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 row m-2"
                                         style="flex: 0 0 48% !important;padding: 1rem 0.5rem!important;border: 1px solid #07406261;border-radius: 5px;">
                                        <div class="col-12 title"
                                             style="padding:0px;color: #074062;position: absolute;top: -1.2rem;">{{__('home.end_data')}}</div>
                                        {{--car_movement_branch_close--}}
                                        <div class="col-md-6 form-group">
                                            <label for="car_movement_branch_close"
                                                   class="col-form-label">@lang('home.car_movement_branch_close')
                                                <span
                                                    class="text-danger">*</span></label>
                                            <select data-live-search="true" disabled
                                                    class="form-control selectpicker @error('car_movement_branch_close') is-invalid @enderror"
                                                    name="car_movement_branch_close" id="car_movement_branch_close">
                                                <option value="{{$record->car_movement_branch_close}}">{{$record->branchClose?$record->branchClose->name:''}}</option>
                                            </select>
                                            @error('car_movement_branch_close')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--car_movement_end--}}
                                        <div class="col-md-6 form-group">
                                            <label for="car_movement_end"
                                                   class="col-form-label">@lang('home.car_movement_end')<span
                                                    class="text-danger">*</span></label>
                                            <input type="date" disabled
                                                   class="form-control @error('car_movement_end') is-invalid @enderror"
                                                   name="car_movement_end"
                                                   id="car_movement_end" value="{{date('Y-m-d', strtotime(now()))}}"
                                                   min="{{date('Y-m-d', strtotime($record->car_movement_start))}}">
                                            @error('car_movement_end')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--close_kilomaters--}}
                                        <div class="col-md-6 form-group">
                                            <label for="close_kilomaters"
                                                   class="col-form-label">@lang('home.close_kilomaters')<span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('close_kilomaters') is-invalid @enderror"
                                                   name="close_kilomaters"
                                                   id="close_kilomaters" min="{{$record->start_kilomaters}}" value="{{old('close_kilomaters')?old('close_kilomaters'):$record->close_kilomaters}}"
                                                   step="1">
                                            @error('close_kilomaters')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--follow_up_days_end--}}
                                        <div class="col-md-6 form-group">
                                            <label for="follow_up_days_end"
                                                   class="col-form-label">@lang('home.follow_up_days_end')<span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                   class="form-control @error('follow_up_days_end') is-invalid @enderror"
                                                   name="follow_up_days_end" disabled
                                                   id="follow_up_days_end" min="1" step="1" value="{{\Carbon\Carbon::now()->diffInDays($record->car_movement_start)?\Carbon\Carbon::now()->diffInDays($record->car_movement_start):1}}">
                                            @error('follow_up_days_end')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        {{--car_movement_notes_close--}}
                                        <div class="col-md-12 form-group">
                                            <label for="car_movement_notes_close"
                                                   class="col-form-label">@lang('home.car_movement_notes_close')
                                                <span
                                                    class="text-danger">*</span></label>
                                            <textarea
                                                class="form-control @error('car_movement_notes_close') is-invalid @enderror"
                                                name="car_movement_notes_close" rows="5"
                                                placeholder="@lang('home.car_movement_notes_close')">{{$record->car_movement_notes_close}}</textarea>
                                            @error('car_movement_notes_close')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="shipping">
                                    {{--shipping_company_name--}}
                                    <div class="col-md-4 form-group">
                                        <label for="shipping_company_name"
                                               class="col-form-label">@lang('home.shipping_company_name')<span
                                                class="text-danger">*</span></label>
                                        <input type="text" disabled
                                               class="form-control @error('shipping_company_name') is-invalid @enderror"
                                               name="shipping_company_name"
                                               value="{{$record->shipping_company_name}}"
                                               id="shipping_company_name">
                                        @error('shipping_company_name')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--shipping_company_no--}}
                                    <div class="col-md-4 form-group">
                                        <label for="shipping_company_no"
                                               class="col-form-label">@lang('home.shipping_company_no')<span
                                                class="text-danger">*</span></label>
                                        <input type="text" disabled
                                               class="form-control @error('shipping_company_no') is-invalid @enderror"
                                               name="shipping_company_no" value="{{$record->shipping_company_no}}"
                                               id="shipping_company_no">
                                        @error('shipping_company_no')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--shipping_company_date--}}
                                    <div class="col-md-4 form-group">
                                        <label for="shipping_company_date"
                                               class="col-form-label">@lang('home.shipping_company_date')<span
                                                class="text-danger">*</span></label>
                                        <input type="date" disabled
                                               class="form-control @error('shipping_company_date') is-invalid @enderror"
                                               name="shipping_company_date"
                                               value="{{date('Y-m-d', strtotime($record->shipping_company_date))}}"
                                               id="shipping_company_date">
                                        @error('shipping_company_date')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        id="create_emp">@lang('home.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(function () {
            set_values();
        });

        function set_values() {
            let code = $('#car_movement_type_id').find(':selected').data('code');
            // let code = $('#car_movement_type_id').val();
            console.log(code)
            if (code == 123008 || code == 1230010 || code == 1230011) {
                $('#shipping').removeClass('d-none');
            } else {
                $('#shipping').addClass('d-none');
            }
        }

        function set_kilometers() {
            let kilo = $('#car_id').find(':selected').data('kilo');
            console.log(kilo)
            $('#start_kilomaters').val(kilo);

        }

        $('#validate-form').validate({
            rules: {
                car_movement_type_id: {
                    required: true
                },
                car_id: {
                    required: true
                },
                car_movement_start: {
                    required: true
                },
                car_movement_end: {
                    required: true
                },
                car_movement_branch_open: {
                    required: true
                },
                car_movement_branch_close: {
                    required: true
                },
                start_kilomaters: {
                    required: true
                },
                close_kilomaters: {
                    required: true
                },
                car_movement_notes_open: {
                    required: true,
                    maxlength: 500
                },
                car_movement_notes_close: {
                    required: true,
                    maxlength: 500
                },
                car_movement_driver_id: {
                    required: true
                },
                follow_up_days_start: {
                    required: true
                },
                follow_up_days_end: {
                    required: true
                },
                follow_up_days_date: {
                    required: true
                },
                shipping_company_name: {
                    required: true
                },
                shipping_company_no: {
                    required: true
                },
                shipping_company_date: {
                    required: true,
                },
            },
            messages: {
                car_movement_type_id: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_type_id')])}}',
                },
                car_id: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_id')])}}',
                },
                car_movement_start: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_start')])}}',
                },
                car_movement_end: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_end')])}}',
                },
                car_movement_branch_open: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_branch_open')])}}',
                },
                car_movement_branch_close: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_branch_close')])}}',
                },
                start_kilomaters: {
                    required: '{{__('messages.required' ,['attr' => __('home.start_kilomaters')])}}',
                },
                close_kilomaters: {
                    required: '{{__('messages.required' ,['attr' => __('home.close_kilomaters')])}}',
                },
                car_movement_notes_open: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_notes_open')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 500 ,'attr' => __('home.car_movement_notes_open')])}}'
                },
                car_movement_notes_close: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_notes_close')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 500 ,'attr' => __('home.car_movement_notes_close')])}}'
                },
                car_movement_driver_id: {
                    required: '{{__('messages.required' ,['attr' => __('home.car_movement_driver_id')])}}',
                },
                follow_up_days_start: {
                    required: '{{__('messages.required' ,['attr' => __('home.follow_up_days_start')])}}',
                },
                follow_up_days_end: {
                    required: '{{__('messages.required' ,['attr' => __('home.follow_up_days_end')])}}',
                },
                follow_up_days_date: {
                    required: '{{__('messages.required' ,['attr' => __('home.follow_up_days_date')])}}',
                },
                shipping_company_name: {
                    required: '{{__('messages.required' ,['attr' => __('home.shipping_company_name')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 500 ,'attr' => __('home.shipping_company_name')])}}'
                },
                shipping_company_no: {
                    required: '{{__('messages.required' ,['attr' => __('home.shipping_company_name')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 500 ,'attr' => __('home.shipping_company_name')])}}'
                },
                shipping_company_date: {
                    required: '{{__('messages.required' ,['attr' => __('home.shipping_company_name')])}}',
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    </script>
@endsection


