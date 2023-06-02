@extends('Layouts.master')
@section('content')

    <div class="section-body mt-3">

        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-lg-12">

                    <div class="card-body">


                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.sub_company')</label>
                                    <input type="text" class="form-control" disabled=""
                                           value="{{ app()->getLocale()=='ar' ? $bond->company->company_name_ar : $bond->company->company_name_en }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.branch')</label>
                                    <input type="text" class="form-control" disabled=""
                                           value="{{ app()->getLocale()=='ar' ? $bond->branch->branch_name_ar : $bond->branch->branch_name_en }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.created_date')</label>
                                    <input type="text" id="date" class="form-control" disabled=""
                                           value="{{$bond->bond_date}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.user')</label>
                                    <input type="text" class="form-control" disabled="" placeholder="Company"
                                           value="{{ app()->getLocale()=='ar' ? $bond->user->user_name_ar :
                                                $bond->user->user_name_en }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{--طرق الدفع--}}
                            <div class="col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.payment_method')</label>
                                    <input type="text" class="form-control" readonly value="{{app()->getLocale() == 'ar' ?
                                    \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                    ->where('company_group_id',$bond->company_group_id)->first()->system_code_name_ar :
                                    \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                    ->where('company_group_id',$bond->company_group_id)->first()->system_code_name_en}}">
                                </div>
                            </div>

                            {{--رقم العمليه--}}
                            <div class="col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.process_number')</label>
                                    <input type="text" class="form-control" readonly
                                           name="process_number"
                                           value="{{$bond->bond_check_no ? $bond->bond_check_no : ''}}">
                                </div>
                            </div>

                            {{--البنك--}}
                            <div class="col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.bank')</label>
                                    <input type="text" readonly class="form-control" @if($bond->bank) value="{{app()->getLocale()=='ar' ?
                                    $bond->bank->system_code_name_ar :
                                   $bond->bank->system_code_name_en }}" @endif>
                                </div>
                            </div>


                            {{-- ااجمالي شامل الضريبه--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.total_value')</label>
                                    <input type="text" class="form-control"
                                           value="{{$bond->bond_amount_debit}}"
                                           name="" readonly>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8 col-md-8">
                                <label class="form-label">@lang('home.notes')</label>
                                <textarea class="form-control" name="bond_notes" readonly
                                          style="font-size: 16px ;font-weight: bold"
                                          placeholder="@lang('home.notes')">{{$bond->bond_notes}}</textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th class="pl-0" style="width: 300px">@lang('home.serial')</th>
                                        <th class="pl-0" style="width: 300px">@lang('home.cash_types')</th>
                                        <th class="pl-0" style="width: 300px">@lang('home.related_account')</th>
                                        <th class="pl-0" style="width: 50px">@lang('home.account_type')</th>
                                        <th class="pl-0" style="width: 150px">@lang('home.account_name')</th>
                                        <th class="pl-0" style="width: 50px">@lang('home.invoice')</th>
                                        <th class="pl-0" style="width: 150px">@lang('home.process_number')</th>
                                        <th colspan="1" style="width: 350px">@lang('home.notes')</th>
                                        <th class="pr-0" style="width: 100px">@lang('home.total_value')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bond->bond_details as $bond_detail)
                                        <tr>
                                            <td>{{$bond_detail->bond_dt_serial}}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond_detail->bondDocType->system_code_name_ar :
                                            $bond_detail->bondDocType->system_code_name_en }}</td>
                                            <td>
                                                {{ app()->getLocale() == 'ar' ?
                                              $bond_detail->account->acc_name_ar :
                                              $bond_detail->account->acc_name_en }}
                                            </td>
                                            <td>{{$bond_detail->customer_type}} </td>
                                            <td>
                                                @if($bond_detail->employee)
                                                    @if(app()->getLocale()=='ar')
                                                        {{$bond_detail->employee->emp_name_full_ar}}
                                                    @else {{$bond_detail->employee->emp_name_full_en}} @endif
                                                @endif

                                                @if($bond_detail->customer)
                                                    @if(app()->getLocale()=='ar')
                                                        {{$bond_detail->customer->customer_name_full_ar}}
                                                    @else {{$bond_detail->customer->customer_name_full_en}} @endif
                                                @endif
                                                @if($bond_detail->branch)
                                                    @if(app()->getLocale()=='ar')
                                                        {{$bond_detail->branch->branch_name_ar}}
                                                    @else {{$bond_detail->branch->branch_name_en}} @endif
                                                @endif

                                                @if($bond_detail->car)
                                                    {{$bond_detail->car->truck_name}}
                                                @endif
                                            </td>
                                            <td>@if($bond_detail->invoice)
                                                    {{$bond_detail->invoice->invoice_no}}
                                                @else
                                                    غير مرتبط بفاتوره
                                                @endif

                                            </td>
                                            <td>{{$bond_detail->bond_check_no ?$bond_detail->bond_check_no  :'لا يوجد' }}</td>
                                            <td>{{$bond_detail->bond_notes}}</td>
                                            <td>{{$bond_detail->bond_amount_debit}}</td>
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

@endsection