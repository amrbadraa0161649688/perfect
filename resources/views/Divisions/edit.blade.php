@extends('Layouts.master')

@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">

            @if(session()->has('message'))
                <div class="col alert alert-success m-2">
                    {{ session()->get('message') }}
                </div>
            @endif


            <div class="row clearfix">
                @include('Includes.form-errors')
                <form class="card" action="{{ route('division.update',$division->division_id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <h3 class="card-title">@lang('home.update_division')</h3>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_ar')</label>
                                    <input type="text" class="form-control"
                                           name="division_name_ar" oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                           id="division_name_ar"
                                           value="{{ $division->division_name_ar }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_en')</label>
                                    <input type="text" class="form-control"
                                           name="division_name_en" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                           id="division_name_en"
                                           value="{{ $division->division_name_en }}">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.division_code')</label>
                                    <input type="text" class="form-control"
                                           name="division_code"
                                           id="division_code"
                                           value="{{ $division->division_code }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.division_status')</label>
                                    <select class="form-control" name="division_status">
                                        <option value="1" @if($division->division_status) selected @endif>on</option>
                                        <option value="0" @if(!$division->division_status) selected @endif>off</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.companies')</label>
                                    <select class="form-control" name="company_id[]" id="company_id">
                                        <option value="">@lang('home.companies')</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->company_id }}">
                                                @if(app()->getLocale()== 'ar')
                                                    {{ $company->company_name_ar }}
                                                @else
                                                    {{ $company->company_name_en }}
                                                @endif</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" id="submit" class="btn btn-primary">@lang('home.update_division')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            //    validation to create department modal
            $('#division_name_ar').keyup(function () {
                if ($('#division_name_ar').val().length < 3) {
                    $('#division_name_ar').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#division_name_ar').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#division_name_en').keyup(function () {
                if ($('#division_name_en').val().length < 3) {
                    $('#division_name_en').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#division_name_en').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#division_code').keyup(function () {
                if ($('#division_code').val().length < 3) {
                    $('#division_code').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#division_code').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });


        });
    </script>
@endsection
