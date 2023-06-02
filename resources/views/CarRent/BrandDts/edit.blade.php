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
                    <form class="card" id="validate-form" action="{{ route('brandDts.update', $record->brand_dt_id) }}"
                          method="post"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('home.add_brand')
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    {{--brand_id--}}
                                    <div class="col-md-12 form-group">
                                        <label for="brand_id"
                                               class="col-form-label">@lang('home.brand')<span
                                                class="text-danger">*</span></label>
                                        <select class="selectpicker form-control @error('brand_id') is-invalid @enderror"
                                                name="brand_id" id="brand_id" data-live-search="true">
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->brand_id}}"
                                                        @if($brand->brand_id == $record->brand_id) selected @endif>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--brand_dt_name_ar--}}
                                    <div class="col-md-6 form-group">
                                        <label for="brand_dt_name_ar"
                                               class="col-form-label"> @lang('home.brand_dt_name_ar') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('brand_dt_name_ar') is-invalid @enderror"
                                               name="brand_dt_name_ar" id="brand_dt_name_ar"
                                               placeholder="@lang('home.brand_dt_name_ar')"
                                               value="{{old('brand_dt_name_ar')?old('brand_dt_name_ar'):$record->brand_dt_name_ar}}"
                                               required>
                                        @error('brand_dt_name_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--brand_dt_name_ar--}}
                                    <div class="col-md-6 form-group">
                                        <label for="brand_dt_name_en"
                                               class="col-form-label"> @lang('home.brand_dt_name_en') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('brand_dt_name_en') is-invalid @enderror"
                                               name="brand_dt_name_en" id="brand_dt_name_en"
                                               placeholder="@lang('home.brand_dt_name_en')"
                                               value="{{old('brand_dt_name_en')?old('brand_dt_name_en'):$record->brand_dt_name_en}}"
                                               required>
                                        @error('brand_dt_name_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2"
                                    id="create_emp">@lang('home.save')</button>
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
        $('#validate-form').validate({
            rules: {
                brand_id: {
                    required: true,
                },
                brand_dt_name_en: {
                    required: true,
                    maxlength: 150
                },
                brand_dt_name_ar: {
                    required: true,
                    maxlength: 150
                },
            },
            messages: {
                brand_id: {
                    required: '{{__('messages.required' ,['attr' => __('home.brand')])}}',
                },
                brand_dt_name_en: {
                    required: '{{__('messages.required' ,['attr' => __('home.brand_dt_name_en')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 150 ,'attr' => __('home.brand_dt_name_en')])}}'
                },
                brand_dt_name_ar: {
                    required: '{{__('messages.required' ,['attr' => __('home.brand_dt_name_ar')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 150 ,'attr' => __('home.brand_dt_name_ar')])}}'
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


