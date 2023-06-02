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
            <div class="row clearfix">

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>  @lang('maintenanceType.m_cards') </h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_1}}</span></h3>
                            <span><span class="text-success mr-2"><i
                                            class="fa fa-car"></i> {{$maintenance_cards_all_1 }} </span> </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>@lang('maintenanceType.mntns_card_q_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_2}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> {{$maintenance_cards_all_2}} </span> </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_o_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_3}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> {{$maintenance_cards_all_3}}</span>  </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_c_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_4}}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-car"></i> {{$maintenance_cards_all_4}}</span> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--الفلاتر--}}
        <div class="card">
            <form action="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="recipient-name" class="col-form-label "> المحطه</label>

                            <select class="selectpicker" multiple data-live-search="true"
                                    name="branch_id[]" data-actions-box="true">
                                @foreach($branches as $branch)
                                    <option value="{{$branch->branch_id}}"
                                            @if(!request()->branch_id) @if(session('branch')['branch_id'] == $branch->branch_id)
                                            selected @endif @endif
                                            @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch_id == $branch->branch_id)
                                            selected @endif @endforeach @endif>
                                        {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                        $branch->branch_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="recipient-name" class="col-form-label "> نوع كرت
                                الصيانة </label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="mntns_cards_type[]" data-actions-box="true">

                                <option value=""> choose</option>
                                @foreach($mntns_cards_type as $mct)
                                    <option value="{{$mct->system_code_id}}"
                                            @if(request()->mntns_cards_type) @foreach(request()->mntns_cards_type as
                                                     $mntns_card_type) @if($mntns_card_type == $mct->system_code_id)
                                            selected @endif @endforeach @endif>
                                        {{ $mct->system_code_name_ar
                                        }}-{{ $mct->system_code_name_en }}
                                        - {{ $mct->system_code }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        {{--تاريخ اانشاء من والي--}}
                        <div class="col-md-4">
                            <label>@lang('home.created_date_from')</label>
                            <input type="date" class="form-control" name="created_date_from"
                                   @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                    @endif>
                        </div>

                        <div class="col-md-4">
                            <label>@lang('home.created_date_to')</label>
                            <input type="date" class="form-control" name="created_date_to"
                                   @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                        </div>


                        <div class="col-md-4">
                            <label for="recipient-name" class="col-form-label"> نوع العمل </label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="mntns_cards_item_id[]" data-actions-box="true">
                                <option value=""> choose</option>
                                @foreach($maintenance_types as $maintenance_type)
                                    <option value="{{$maintenance_type->mntns_type_id}}">
                                        {{app()->getLocale() == 'ar' ? $maintenance_type->mntns_type_name_ar :
                                        $maintenance_type->mntns_type_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-2">
                            <label for="recipient-name" class="col-form-label"> الحاله </label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="mntns_cards_status[]" data-actions-box="true">
                                <option value=""> choose</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status->system_code_id}}"
                                            @if(request()->mntns_cards_status)
                                            @foreach(request()->mntns_cards_status as $mntns_card_status)
                                            @if($mntns_card_status == $status->system_code_id) selected @endif @endforeach
                                            @endif>
                                        {{app()->getLocale() == 'ar' ? $status->system_code_name_ar :
                                        $status->system_code_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <br>
                            <br>
                            <button type="submit" class="btn btn-primary">@lang('home.filter')</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{--/////////////////////////////////////////--}}


        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <div class="card-options">

                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="header-action">

                            <div class="header-action">
                                <a href="{{route('maintenanceCardCheck.create')}}"
                                   class="btn btn-primary btn-lg">{{__('Add New Card')}}</a>
                            </div>


                            <div class="col-md-3">
                                <h4 class="modal-title" style="text-align:right">
                                    @lang('maintenanceType.m_cards')
                                </h4>
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped   card_table">
                                <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>@lang('maintenanceType.mntns_card_no')</th>
                                    <th>@lang('maintenanceType.mntns_card_types')</th>
                                    <th>@lang('maintenanceType.mntns_card_customer')</th>
                                    <th>@lang('maintenanceType.mntns_card_mobile')</th>
                                    <th>@lang('maintenanceType.mntns_card_plate')</th>
                                    <th>@lang('maintenanceType.mntns_card_status')</th>
                                    <th>@lang('maintenanceType.mntns_card_paid')</th>
                                    <th>@lang('maintenanceType.mntns_card_amount')</th>


                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($maintenance_cards as $k=>$maintenance_card)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{$maintenance_card->mntns_cards_no}}</td>
                                        <td>{{$maintenance_card->cardType ? $maintenance_card->cardType->system_code_name_ar : ''}}</td>
                                        <td>{{$maintenance_card->customer->customer_id}}</td>
                                        <td>{{$maintenance_card->customer->customer_mobile}}</td>
                                        <td>{{$maintenance_card->car->mntns_cars_plate_no}}</td>
                                        <td>{{app()->getLocale() == 'ar' ? $maintenance_card->status->system_code_name_ar : $maintenance_card->status->system_code_name_en}}</td>
                                        <td>{{$maintenance_card->mntns_cards_payment_amount}}</td>
                                        <td>{{$maintenance_card->mntns_cards_total_amount}}</td>
                                        <td>
                                            <a href="{{route('maintenanceCardCheck.edit',$maintenance_card->mntns_cards_id)}}">
                                                <i class="fa fa-edit"></i></a>

                                            @if($maintenance_card->mntns_cards_total_amount > 0 && $maintenance_card->mntns_cards_payment_amount < $maintenance_card->mntns_cards_total_amount)

                                                <a type="button" class="btn btn-primary btn-sm text-white"
                                                   data-toggle="modal"
                                                   data-target="#exampleModal{{$maintenance_card->mntns_cards_id}}"
                                                   data-whatever="@mdo">
                                                    @lang('home.add_capture_bond')
                                                </a>


                                                {{--سند القبض--}}
                                                <div class="modal fade"
                                                     id="exampleModal{{$maintenance_card->mntns_cards_id}}"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="exampleModalLabel">@lang('home.add_capture_bond')</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('maintenanceCardCheck.addBondWithJournal') }}"
                                                                      method="post">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.sub_company')</label>
                                                                                @if(session('company'))
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           disabled=""
                                                                                           value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                                                                @else
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           disabled=""
                                                                                           value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.branch')</label>
                                                                                <input type="text" class="form-control"
                                                                                       disabled=""
                                                                                       value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.created_date')</label>
                                                                                <input type="text" value="{{\Carbon\Carbon::now()}}"
                                                                                       class="form-control"
                                                                                       disabled="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.user')</label>
                                                                                <input type="text" class="form-control"
                                                                                       disabled=""
                                                                                       placeholder="Company"
                                                                                       value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                                                            </div>
                                                                        </div>

                                                                        <input type="hidden" name="transaction_id"
                                                                               value="{{$maintenance_card->mntns_cards_id}}">

                                                                        {{--النشاط--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.bonds_activity')</label>

                                                                                <input type="text" disabled=""
                                                                                       value=" كارت الصيانه"
                                                                                       class="form-control">
                                                                                <input type="hidden"
                                                                                       name="transaction_type"
                                                                                       value="58">

                                                                            </div>
                                                                        </div>

                                                                        {{--الرقم المرجعي--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.reference_number')</label>

                                                                                <input type="text" class="form-control"
                                                                                       name="bond_ref_no"
                                                                                       value="{{ $maintenance_card->mntns_cards_no }}"
                                                                                       readonly>
                                                                            </div>
                                                                        </div>

                                                                        {{--القيمه المستحقه--}}
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.deserved_value')</label>
                                                                                <input type="text" class="form-control"
                                                                                       readonly
                                                                                       id="deserved_value"
                                                                                       value="{{ $maintenance_card->mntns_cards_due_amount }}">

                                                                            </div>
                                                                        </div>

                                                                        {{--نوع الحساب--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.account_type')</label>

                                                                                <input type="text" class="form-control"
                                                                                       readonly
                                                                                       value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_en}}">
                                                                                <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56002)
                                            ->first()->system_code_id}}" name="account_type">
                                                                            </div>
                                                                        </div>


                                                                        {{--نوع العميل--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <input type="hidden"
                                                                                       name="customer_type"
                                                                                       value="customer">

                                                                                <label class="form-label">@lang('home.customer')</label>

                                                                                <input type="text" readonly
                                                                                       class="form-control"
                                                                                       value="{{app()->getLocale()=='ar' ? $maintenance_card->customer->customer_name_full_ar :
                                                              $maintenance_card->customer->customer_name_full_en  }}">
                                                                                <input type="hidden" name="customer_id"
                                                                                       value="{{ $maintenance_card->customer->customer_id }}">
                                                                            </div>
                                                                        </div>

                                                                        {{-- قم الحساب للعميل--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.account_code')</label>
                                                                                <input type="hidden"
                                                                                       class="form-control"
                                                                                       name="bond_acc_id"
                                                                                       value="{{ $maintenance_card->customer->customer_account_id}}">
                                                                                <input type="text" readonly
                                                                                       class="form-control"
                                                                                       value="{{app()->getLocale() == 'ar' ?
                                                               $maintenance_card->customer->account->acc_name_ar :
                                                               $maintenance_card->customer->account->acc_name_en}} . {{ $maintenance_card->customer->account->acc_code }}">

                                                                            </div>
                                                                        </div>

                                                                        {{--انواع الايرادات--}}
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.revenue_types')</label>
                                                                                <select class="selectpicker"
                                                                                        data-live-search="true"
                                                                                        name="bond_doc_type" required>
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    @foreach($system_code_types as $system_code)
                                                                                        <option value="{{$system_code->system_code_id}}">
                                                                                            {{ app()->getLocale()=='ar' ?
                                                                                        $system_code->system_code_name_ar :  $system_code->system_code_name_en }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--طرق الدفع--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.payment_method')</label>
                                                                                <select class="form-control"
                                                                                        onchange="validInputs($(this))"
                                                                                        name="bond_method_type"
                                                                                        required>
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    @foreach($payment_methods as $payment_method)
                                                                                        <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--رقم العمليه--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.process_number')</label>
                                                                                <input type="text" class="form-control"
                                                                                       name="process_number" disabled>
                                                                            </div>
                                                                        </div>

                                                                        {{--البنك--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.bank')</label>
                                                                                <select class="form-control"
                                                                                        name="bond_bank_id" disabled>
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    @foreach($banks as $bank)
                                                                                        <option value="{{ $bank->system_code_id }}">
                                                                                            {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                                                                             $bank->system_code_name_en }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        {{--القيمه--}}
                                                                        <div class="col-sm-6 col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="form-label">@lang('home.value')</label>
                                                                                <input type="text" class="form-control"
                                                                                       value="{{$maintenance_card->mntns_cards_due_amount }}"
                                                                                       name="bond_amount_credit"
                                                                                       required
                                                                                       onkeyup="validPaid($(this))">
                                                                                <small class="text-danger">
                                                                                </small>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6 col-md-6">
                                                                            <label class="form-label">@lang('home.notes')</label>
                                                                            <textarea class="form-control"
                                                                                      name="bond_notes"
                                                                                      placeholder="@lang('home.notes')"></textarea>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                    class="btn btn-primary btn-sm">
                                                                                @lang('home.add_bond')
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @endif


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


        <div class="row w-100 mt-3">
            <div class="col-12">
                {{ $maintenance_cards->appends($data)->links() }}
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        function validInputs(el) {
            console.log(el.val())
            //فيزا
            if (el.val() == 57002 || el.val() == 57003 || el.val() == 57004) {
                el.parent().parent().next().children().children().removeAttr('disabled')
                el.parent().parent().next().next().children().children().attr('disabled', 'disabled')
                el.parent().parent().next().next().children().children().val('')
            }
            //بنك
            if (el.val() == 57005) {
                el.parent().parent().next().next().children().children().removeAttr('disabled')
                el.parent().parent().next().children().children().removeAttr('disabled')
            }

            if (el.val() == 57001) {
                el.parent().parent().next().next().children().children().attr('disabled', 'disabled')
                el.parent().parent().next().next().children().children().val('')
                el.parent().parent().next().children().children().attr('disabled', 'disabled')
                el.parent().parent().next().children().children().val('')
            }
        }

        function validPaid(el) {
            var deserved_value = el.parent().parent().prev().prev().prev().prev().prev().prev().prev()
                .prev().children().children().eq(1).val()

            //     console.log(deserved_value)
            if (parseFloat(el.val()) > parseFloat(deserved_value)) {
                el.parent().parent().next().next().children().attr('disabled', 'disabled')
            } else {
                el.parent().parent().next().next().children().removeAttr('disabled')
            }
        }
    </script>
@endsection