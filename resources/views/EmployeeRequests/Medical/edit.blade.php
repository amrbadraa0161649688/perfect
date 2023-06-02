@extends('Layouts.master')

@section('content')
    <form action="{{ route('employee-requests-update-medical-insurance',$employee_request->emp_request_id) }}"
          method="post" id="medical_request">
        @csrf
        @method('put')


        {{----------------medical request------------------------------------------------------------------------------------}}
        <div id="medical-insurance-form">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.request_code')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           name="emp_request_code"
                                           value="{{$employee_request->emp_request_code}}" readonly>

                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.user_name')</label>
                                <div class="form-group multiselect_div">
                                    <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                $employee_request->user->user_name_ar : $employee_request->user->user_name_en}}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.employee_name')</label>
                                    <div class="form-group multiselect_div">
                                        <div class="form-group multiselect_div">
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                $employee_request->employee->emp_name_full_ar :
                                                 $employee_request->employee->emp_name_full_en}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--نوع الطلب--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.request_type')</label>
                                    {{--@if($employee_request->requestType)--}}
                                    {{--<input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?--}}
                                    {{--$employee_request->requestType->system_code_name_ar :--}}
                                    {{--$employee_request->requestType->system_code_name_en}}" readonly>--}}
                                    {{--@endif--}}
                                    @if($employee_request->emp_request_status == 2)
                                        <select name="item_type" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($insuranceTypes as $insurance_type)
                                                <option value="{{$insurance_type->system_code_id}}"
                                                        @if($employee_request->requestDetails->first()->item_type ==
                                                        $insurance_type->system_code_id) selected @endif>
                                                    {{app()->getLocale() == 'en'  ?
                                                    $insurance_type->system_code_name_en :
                                                     $insurance_type->system_code_name_ar}}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                        $employee_request->requestType->system_code_name_ar :
                                        $employee_request->requestType->system_code_name_en}}" readonly>
                                    @endif

                                </div>
                            </div>

                            {{--فئه التامين--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.insurance_category')</label>

                                    @if($employee_request->emp_request_status == 2)
                                        <select name="item_category" class="form-control">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($insuranceCategories as $insurance_category)
                                                <option value="{{$insurance_category->system_code_id}}"
                                                        @if($employee_request->requestDetails->first()->item_category ==
                                                                $insurance_category->system_code_id) selected @endif>
                                                    {{app()->getLocale() == 'en'  ?
                                                    $insurance_category->system_code_name_en :
                                                     $insurance_category->system_code_name_ar}}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                        $employee_request->requestDetails->first()->category->system_code_name_ar :
                                        $employee_request->requestDetails->first()->category->system_code_name_en}}"
                                               readonly>
                                    @endif
                                </div>
                            </div>

                            {{--الملاحظات--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.notes')</label>
                                    <textarea class="form-control" name="emp_request_notes"
                                              @if($employee_request->emp_request_status != 2)
                                              readonly @endif>
                                      {{$employee_request->emp_request_notes}}
                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{--بيانات الموظف--}}
            <x-employees.employee-data
                    :employeeRequest="$employee_request">

            </x-employees.employee-data>

            <div class="card">
                <div class="card-body demo-card">
                    <div class="card-header">
                        @lang('home.persons_added_to_insurance')
                    </div>
                    <div class="row clearfix">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.birth_date')</th>
                                        <th>@lang('home.item_relation')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee_request->requestDetails as $k=>$request_detail)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>
                                                <input type="text" name="item_name_ar"
                                                       class="form-control" readonly
                                                       value="{{ $request_detail->item_name_ar }}">
                                            </td>
                                            <td>
                                                <input type="text" name="item_name_ar"
                                                       class="form-control" readonly
                                                       value="{{ $request_detail->item_date }}">
                                            </td>
                                            <td>
                                                <input type="text" name="item_name_ar"
                                                       class="form-control" readonly
                                                       value="{{ $request_detail->item_relation }}">
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <label>@lang('home.approved')</label>
                            <div class="form-group">
                                @if($employee_request->emp_request_status == 2)
                                    <select name="emp_request_status" class="form-control" required>
                                        <option value="">@lang('home.choose')</option>
                                        <option value="0">@lang('home.refuse_request')</option>
                                        <option value="1">@lang('home.accept_request')</option>
                                    </select>
                                @else
                                    <input name="emp_request_approved" class="form-control"
                                           value="@if($employee_request->emp_request_status == 0)
                                           @lang('home.refuse_request')
                                           @elseif($employee_request->emp_request_status == 1)
                                           @lang('home.accept_request')
                                           @endif" readonly>

                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <label>موافقه الموارد البشريه </label>
                            <div class="form-group">
                                @if($employee_request->emp_request_hr_approver == 2)
                                    <select name="emp_request_hr_approver" class="form-control" required>
                                        <option value="">@lang('home.choose')</option>
                                        <option value="0">@lang('home.refuse_request')</option>
                                        <option value="1">@lang('home.accept_request')</option>
                                    </select>
                                @else
                                    <input name="emp_request_hr_approver" class="form-control"
                                           value="@if($employee_request->emp_request_hr_approver == 0)
                                           @lang('home.refuse_request')
                                           @elseif($employee_request->emp_request_hr_approver == 1)
                                           @lang('home.accept_request')
                                           @endif" readonly>

                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    @if($employee_request->emp_request_status == 2)
                        <button class="btn btn-primary" type="submit"
                                id="submit_medical_insurance">@lang('home.save')</button>
                    @endif
                </div>
            </div>


        </div>
    </form>
@endsection