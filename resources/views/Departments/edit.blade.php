@extends('Layouts.master')

@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">
                @include('Includes.form-errors')
                <form class="card" action="{{ route('department.update',$department->department_id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <h3 class="card-title">@lang('home.update_department')</h3>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_ar')</label>
                                    <input type="text" class="form-control"
                                           name="department_name_ar" oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                           id="department_name_ar"
                                           value="{{ $department->department_name_ar }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_en')</label>
                                    <input type="text" class="form-control"
                                           name="department_name_en" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                           id="department_name_en"
                                           value="{{ $department->department_name_en }}">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.department_code')</label>
                                    <input type="text" class="form-control"
                                           name="department_code"
                                           id="department_code"
                                           value="{{ $department->department_code }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.companies')</label>
                                    <select class="form-control" name="company_id[]">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->company_id }}">
                                                @if(app()->getLocale()== 'ar')
                                                    {{ $company->company_name_ar }}
                                                @else
                                                    {{ $company->company_name_en }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" id="submit"
                                class="btn btn-primary">@lang('home.update_department')</button>
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
            $('#department_name_en').keyup(function () {
                if ($('#department_name_en').val().length < 3) {
                    $('#department_name_en').addClass('is-invalid');
                    $('#create_department').attr('disabled', 'disabled');
                } else {
                    $('#department_name_en').removeClass('is-invalid');
                    $('#create_department').removeAttr('disabled');
                }
            });

            $('#department_name_ar').keyup(function () {
                if ($('#department_name_ar').val().length < 3) {
                    $('#department_name_ar').addClass('is-invalid');
                    $('#create_department').attr('disabled', 'disabled');
                } else {
                    $('#department_name_ar').removeClass('is-invalid');
                    $('#create_department').removeAttr('disabled');
                }
            });

            $('#department_code').keyup(function () {
                if ($('#department_code').val().length < 3) {
                    $('#department_code').addClass('is-invalid');
                    $('#create_department').attr('disabled', 'disabled');
                } else {
                    $('#department_code').removeClass('is-invalid');
                    $('#create_department').removeAttr('disabled');
                }
            });

        });
    </script>
@endsection
