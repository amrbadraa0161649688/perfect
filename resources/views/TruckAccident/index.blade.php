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
    <div class="section-gray py-4">
        <div class="container-fluid">
            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.accident_number')</label>
                                    <input class="form-control" name="car_accident_code"
                                           @if(request()->car_accident_code)
                                           value="{{request()->car_accident_code}}" @endif>
                                </div>

                                {{--نوع الحادث--}}
                                <div class="col-md-4">
                                    <label class="form-label">@lang('home.accident_type')</label>
                                    <select name="car_accident_type_id[]" class="selectpicker" multiple
                                            data-live-search="true" id="car_accident_type_id" required>
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($accident_types as $accident_type)
                                            <option value="{{$accident_type->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $accident_type->system_code_name_ar
                                                : $accident_type->system_code_name_en}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                {{--حاله الحادث--}}
                                <div class="col-md-4">
                                    <label class="form-label">@lang('home.accident_status')</label>
                                    <select name="car_accident_status[]" class="selectpicker" multiple
                                            data-live-search="true" id="car_accident_status">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($accident_statuses as $accident_status)
                                            <option value="{{$accident_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $accident_status->system_code_name_ar
                                                : $accident_status->system_code_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--العقد او خط السير--}}
                                <div hidden class="col-md-4">
                                    <label class="form-label">@lang('home.contract_number_or_itinerary_number')</label>
                                    <select name="contract_id[]" class="selectpicker" multiple
                                            data-live-search="true" id="contract_id">
                                        @foreach($contracts as $contract)
                                            <option value="{{$contract->contract_id}}">
                                                {{$contract->contract_code}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--شركات التامين--}}
                                <div class="col-md-4">
                                    <label class="form-label">@lang('home.insurance_company')</label>
                                    <select name="car_accident_insurance[]" class="selectpicker" multiple
                                            data-live-search="true" id="car_accident_insurance">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($companies_insurance as $company_insurance)
                                            <option value="{{$company_insurance->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $company_insurance->system_code_name_ar
                                                : $company_insurance->system_code_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--جهات التقدير--}}
                                <div hidden class="col-md-4">
                                    <label class="form-label">@lang('home.appreciation_body')</label>
                                    <select name="car_accident_appreciate[]" class="selectpicker" multiple
                                            data-live-search="true" id="car_accident_appreciate">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($appreciation_sides as $appreciation_side)
                                            <option value="{{$appreciation_side->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $appreciation_side->system_code_name_ar
                                                : $appreciation_side->system_code_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--مباشر الحادث--}}
                                <div class="col-md-4">
                                    <label class="form-label">@lang('home.live_accident')</label>
                                    <select name="emp_id[]" class="selectpicker" multiple
                                            data-live-search="true" id="">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($direct_employees as $direct_employee)
                                            <option value="{{$direct_employee->emp_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $direct_employee->emp_name_full_ar
                                                : $direct_employee->emp_name_full_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--الفروع--}}
                                <div class="col-md-4">
                                    {{-- branches  --}}
                                    <label>@lang('home.branches')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]" id="branch_id">
                                        @foreach($branches as $branch)
                                            <option @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch->branch_id == $branch_id) selected
                                                    @endif @endforeach @endif value="{{ $branch->branch_id }}">{{ app()->getLocale()=='ar' ?
                                                     $branch->branch_name_ar : $branch->branch_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--تاريخ من والي--}}
                                <div class="col-md-2">
                                    <label>@lang('home.created_date_from')</label>
                                    <input type="date" class="form-control" name="car_accident_date_from"
                                           @if(request()->car_accident_date_from) value="{{request()->car_accident_date_from}}"
                                            @endif>
                                </div>
                                <div class="col-md-2">
                                    <label>@lang('home.created_date_to')</label>
                                    <input type="date" class="form-control" name="car_accident_date_to"
                                           @if(request()->car_accident_date_to) value="{{request()->car_accident_date_to}}" @endif>
                                </div>


                                <div hidden class="col-md-4">
                                    <label>@lang('home.contract_code')</label>
                                    <input type="text" class="form-control" name="contract_code" id="contract_code"
                                           @if(request()->contract_code) value="{{request()->contract_code}}" @endif>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.al_maqqab')</label>

                                    <select name="car_accident_follower[]" class="selectpicker" multiple
                                            data-live-search="true" id="car_accident_follower">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($follower_employees as $follower_employee)
                                            <option value="{{$follower_employee->emp_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $follower_employee->emp_name_full_ar
                                                : $follower_employee->emp_name_full_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.plate_number')</label>
                                    <input type="text" class="form-control" name="plate_number" id="plate_number"
                                           @if(request()->plate_number) value="{{request()->plate_number}}" @endif>
                                </div>
                            </div>


                            <button class="btn btn-primary mt-3" type="submit">@lang('home.search')</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                                 <div class="col-md-4">
                                    <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">

                                        <a href="{{route('truck-accident.create')}}"  class="btn btn-primary" >
                                            <i class="fe fe-plus"></i>@lang('home.accident_add')
                                        </a>


                                    </button>
                                </div>


                <div class="card mt-3">
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="card bg-none">

                                <div class="card-body pt-0">
                                    <div class="table-responsive table_e2">
                                        <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>@lang('home.accident_number')</th>
                                                <th>@lang('home.date')</th>
                                                <th>@lang('home.branch')</th>
                                                
                                                <th>@lang('home.plate_number')</th>
                                                <th>@lang('home.accident_type')</th>
                                                <th>@lang('home.appreciation_body')</th>
                                                
                                                <th>@lang('home.Compensation_amount_truck')</th>
                                                
                                                <th>@lang('home.accident_status')</th>
                                                <th>@lang('home.al_maqqab')</th>
                                                <th>@lang('home.live_accident')</th>
                                                <th>@lang('home.insurance_company')</th>
                                                <th>@lang('home.add_bond')</th>
                                                <th>@lang('home.delete')</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($car_accidents as $car_accident)
                                                <tr>
                                                    <td>{{$car_accident->car_accident_code}}</td>
                                                    <td>{{$car_accident->car_accident_date}}</td>
                                                    <td>{{app()->getLocale()=='ar' ? $car_accident->branch->branch_name_ar :
                                            $car_accident->branch->branch_name_en}}</td>
                                                   
                                                    <td>{{$car_accident->cartruck->truck_name}}</td>

                                                    <td>{{app()->getLocale()=='ar' ? $car_accident->accidentType->system_code_name_ar :
                                            $car_accident->accidentType->system_code_name_en}}</td>
                                                    <td>
                                                        @if($car_accident->accidentAppreciationBody)
                                                            {{app()->getLocale()=='ar' ? $car_accident->accidentAppreciationBody->system_code_name_ar :
                                                        $car_accident->accidentAppreciationBody->system_code_name_en}}
                                                        @else
                                                            <p>لا يوجد</p>
                                                        @endif</td>
                                                  
                                                    <td>{{$car_accident->car_accident_amount}}</td>
                                                   
                                                    <td>
                                                        @if($car_accident->carAccidentStatus)
                                                            {{app()->getLocale()=='ar' ? $car_accident->carAccidentStatus->system_code_name_ar :
                                                        $car_accident->carAccidentStatus->system_code_name_en}}
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if($car_accident->carFollower)
                                                            {{app()->getLocale()=='ar' ? $car_accident->carFollower->emp_name_full_en :
                                                        $car_accident->carFollower->emp_name_full_en}}
                                                        @else
                                                            <p>لا يوجد</p>
                                                        @endif
                                                    </td>

                                                    <td>لا يوجد</td>

                                                    <td>
                                                        @if($car_accident->carInsuranceCompany)
                                                            {{app()->getLocale()=='ar' ? $car_accident->carInsuranceCompany->system_code_name_ar :
                                                        $car_accident->carInsuranceCompany->system_code_name_en}}
                                                        @else
                                                            <p>لا يوجد</p>
                                                        @endif
                                                    </td>

                                                   

                                                    <td>
                                                        @if($car_accident->car_accident_payment == 0)
                                                            <form action="{{ route('truck-accident.delete',$car_accident->car_accident_id) }}"
                                                                  method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button class="btn btn-primary"
                                                                        type="submit">@lang('home.delete')</button>
                                                            </form>
                                                        @endif

                                                    </td>

                                                    <td>
                                                        <a href="{{ route('truck-accident.edit',$car_accident->car_accident_id) }}"
                                                           class="btn btn-primary btn-sm" title="show">
                                                            <i class="fa fa-eye"></i></a>



                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection
