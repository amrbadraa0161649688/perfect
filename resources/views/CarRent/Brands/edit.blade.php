@extends('Layouts.master')
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
                    <form class="card" id="validate-form" action="{{ route('brands.update', $record->brand_id) }}"
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
                                    {{--brand_name_ar--}}
                                    <div class="col-md-6 form-group">
                                        <label for="brand_name_ar"
                                               class="col-form-label"> @lang('home.brand_name_ar') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('brand_name_ar') is-invalid @enderror"
                                               name="brand_name_ar" id="brand_name_ar"
                                               placeholder="@lang('home.brand_name_ar')"
                                               value="{{old('brand_name_ar')?old('brand_name_ar'):$record->brand_name_ar}}"
                                               required>
                                        @error('brand_name_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--brand_name_ar--}}
                                    <div class="col-md-6 form-group">
                                        <label for="brand_name_en"
                                               class="col-form-label"> @lang('home.brand_name_en') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('brand_name_en') is-invalid @enderror"
                                               name="brand_name_en" id="brand_name_en"
                                               placeholder="@lang('home.brand_name_en')"
                                               value="{{old('brand_name_en')?old('brand_name_en'):$record->brand_name_en}}"
                                               required>
                                        @error('brand_name_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--brand_logo_url--}}
                                    <div class="col-md-6 form-group">
                                        <div class="mb-3">
                                            <div class="card">
                                                <div class="card-header">
                                                </div>
                                                <div class="card-body">
                                                    <input type="file" id="dropify-event" accept="image/*"
                                                           class="@error('brand_logo_url') is-invalid @enderror"
                                                           name="brand_logo_url" value="{{$record->brand_logo_url}}">
                                                    @error('brand_logo_url')
                                                    <div class="invalid-feedback">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    {{--brand_logo_url--}}
                                    <div class="col-md-6 form-group">
                                        <img src="{{$record->brand_logo_url}}" width="100" height="100"
                                             class="img-thumbnail">
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
    <script>
        $('#validate-form').validate({
            rules: {
                brand_name_en: {
                    required: true,
                    maxlength: 150
                },
                brand_name_ar: {
                    required: true,
                    maxlength: 150
                },
            },
            messages: {
                brand_name_en: {
                    required: '{{__('messages.required' ,['attr' => __('home.brand_name_en')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 150 ,'attr' => __('home.brand_name_en')])}}'
                },
                brand_name_ar: {
                    required: '{{__('messages.required' ,['attr' => __('home.brand_name_ar')])}}',
                    maxlength: '{{__('messages.maxlength', ['num' => 150 ,'attr' => __('home.brand_name_ar')])}}'
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


