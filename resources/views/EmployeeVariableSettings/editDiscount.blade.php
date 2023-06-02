@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <form action="{{ route('employees-variables-setting.updateDiscount',$variable_discount->emp_variables_type_id) }}"
                              method="post">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.add_ons_type')</label>
                                    <select class="form-control"
                                            name="emp_variables_type_code">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($system_codes_discounts as $system_code_discount)
                                            <option value="{{ $system_code_discount->system_code_id }}"
                                                    @if($variable_discount->emp_variables_type_code == $system_code_discount->system_code_id)
                                                    selected @endif>
                                                {{ $system_code_discount->system_code_name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">@lang('home.salary_type')</label>
                                    <select class="form-control"
                                            name="emp_variables_salary_type">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($system_codes_salary_types as $system_codes_type_adds)
                                            <option value="{{ $system_codes_type_adds->system_code_id }}"
                                                    @if($variable_discount->emp_variables_salary_type == $system_codes_type_adds->system_code_id)
                                                    selected @endif>
                                                {{ $system_codes_type_adds->system_code_name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">@lang('home.calculation_method')</label>
                                    <select class="form-control"
                                            name="emp_variables_method">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($system_codes_methods as $system_codes_method_adds)
                                            <option value="{{ $system_codes_method_adds->system_code_id }}"
                                                    @if($variable_discount->emp_variables_method == $system_codes_method_adds->system_code_id) selected @endif>
                                                {{ $system_codes_method_adds->system_code_name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="number" step=any
                                           class="form-control"
                                           value="{{ $variable_discount->emp_variables_factor }}"
                                           name="emp_variables_factor"
                                           style="width:60px">
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">@lang('home.update')</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
