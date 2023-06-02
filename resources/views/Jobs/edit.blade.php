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
                <form class="card" action="{{ route('job.update',$job->job_id) }}" method="post">
                    @csrf
                    <div class="card-body">
                        <h3 class="card-title">@lang('home.update_job')</h3>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_ar')</label>
                                    <input type="text" class="form-control"
                                           name="job_name_ar" id="job_name_ar" oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                           value="{{ $job->job_name_ar }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_en')</label>
                                    <input type="text" class="form-control"
                                           name="job_name_en" id="job_name_en" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                           value="{{ $job->job_name_en }}">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.job_code')</label>
                                    <input type="text" class="form-control"
                                           name="job_code" id="job_code"
                                           value="{{ $job->job_code }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.job_status')</label>
                                    <select class="form-control" name="job_status">
                                        <option value="1" @if($job->job_status) selected @endif>on</option>
                                        <option value="0" @if(!$job->job_status) selected @endif>off</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.companies')</label>
                                    <select class="form-control" name="company_id[]" id="company_id">
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
                        <button type="submit" class="btn btn-primary">@lang('home.update_job')</button>
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
            $('#job_name_ar').keyup(function () {
                if ($('#job_name_ar').val().length < 3) {
                    $('#job_name_ar').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#job_name_ar').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#job_name_en').keyup(function () {
                if ($('#job_name_en').val().length < 3) {
                    $('#job_name_en').addClass('is-invalid');
                    $('#submit').attr('disabled', 'disabled');
                } else {
                    $('#job_name_en').removeClass('is-invalid');
                    $('#submit').removeAttr('disabled');
                }
            });

            $('#job_code').keyup(function () {
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
