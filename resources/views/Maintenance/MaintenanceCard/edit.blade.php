@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }

        .ctd {
            text-align: center;

        }
    </style>

@endsection

@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#edit-grid" data-toggle="tab"
                           class="nav-link @if(request()->qr == 'applications') active @endif">@lang('home.edit')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#bonds-cash-grid"
                                            data-toggle="tab">@lang('home.bonds_cash')</a></li>

                    <li class="nav-item"><a class="nav-link" href="#bonds-capture-grid"
                                            data-toggle="tab">@lang('home.bonds_capture')</a></li>

                    <li class="nav-item"><a class="nav-link" href="#purchases-grid"
                                            data-toggle="tab">{{__('purchases')}}</a></li>

                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">


            <div class="tab-content mt-3">

                <div class="tab-pane fade show active" id="edit-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <form id="card_data_form" enctype="multipart/form-data">
                                @csrf
                                <?php
                                $can_edit = App\Models\SystemCode::whereIn('system_code', [50001, 50002])
                                    ->where('company_id', $company->company_id)->pluck('system_code_id')->toArray();
                                $can_close = App\Models\SystemCode::where('system_code', '=', 50002)
                                    ->where('company_id', $company->company_id)->first()->system_code_id;
                                $can_inv = App\Models\SystemCode::where('system_code', '=', 50003)
                                    ->where('company_id', $company->company_id)->first()->system_code_id
                                ?>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <input type="hidden" class="form-control" name="mntns_cards_id"
                                                   id="mntns_cards_id" value="{{ $card->mntns_cards_id }}">
                                            <input type="hidden" class="form-control" name="card_uuid"
                                                   id="card_uuid"
                                                   value="{{ $card->uuid }}">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label ">  @lang('maintenanceType.mntns_card_no')
                                                </label>
                                                <input type="text" class="form-control" name="mntns_cards_no"
                                                       id="mntns_cards_no" value="{{ $card->mntns_cards_no }}"
                                                       readonly
                                                       disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label ">   @lang('maintenanceType.mntns_card_types')
                                                </label>
                                                <select class="form-select form-control" name="mntns_cards_type"
                                                        id="mntns_cards_type" required
                                                >
                                                    @foreach($mntns_cards_type as $mct)
                                                        <option value="{{$mct->system_code_id}}" {{($card->mntns_cards_type == $mct->system_code_id? 'selected': '' )}}> {{ $mct->getSysCodeName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('maintenanceType.mntns_type')
                                                </label>
                                                <select class="form-select form-control "
                                                        name="mntns_cards_category"
                                                        id="mntns_cards_category" required
                                                >

                                                    @foreach($mntns_cards_category as $mcc)
                                                        <option value="{{$mcc->system_code_id}}" {{($card->mntns_cards_category == $mcc->system_code_id? 'selected': '' )}} >  {{$mcc->getSysCodeName()}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('maintenanceType.mntns_card_cus_type')
                                                </label>
                                                <select class="form-select form-control "
                                                        name="mntns_cards_customer_type"
                                                        id="mntns_cards_customer_type"
                                                        required disabled>

                                                    @foreach($cards_customer_type as $customer_type)
                                                        <option value="{{$customer_type->system_code_id}}" {{($card->mntns_cards_customer_type == $mct->system_code_id? 'selected': '' )}}> {{ $customer_type->getSysCodeName() }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('waybill.created_date')</label>
                                                    <input type="text" class="form-control" name="created_date"
                                                           id="created_date" value="{{($card->created_date)}}" required
                                                           disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('maintenanceType.mntns_card_customer') </label>
                                                <select class="form-select form-control" name="customer_id"
                                                        id="customer_id" required disabled>
                                                    @foreach($customer_list as $customer)
                                                        <option value="{{ $customer->customer_id }}" {{($card->customer_id ==  $customer->customer_id ? 'selected': '' )}}> {{ $customer->customer_name_full_ar}}
                                                            - {{ $customer->customer_name_full_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('maintenanceType.mntns_card_plate') </label>
                                                <select class="form-select form-control" name="mntns_cars_id"
                                                        id="mntns_cars_id" data-live-search="true" required
                                                        disabled>
                                                    @foreach($car_list as $car)
                                                        <option value="{{ $car->mntns_cars_id }}" {{($card->mntns_cars_id ==  $car->mntns_cars_id ? 'selected': '' )}}> {{ $car->brand->getSysCodeName()}}
                                                            - {{ $car->mntns_cars_plate_no}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label ">  @lang('maintenanceType.mntns_card_status')
                                                </label>

                                                <select class="form-select form-control" name="mntns_cards_status"
                                                        id="mntns_cards_status" required>
                                                    @if(in_array($card->mntns_cards_status,$can_edit))
                                                        @foreach($cards_status as $cs)
                                                            <option value="{{$cs->system_code_id}}"
                                                                    {{($card->mntns_cards_status == $cs->system_code_id? 'selected': '' )}}>
                                                                {{ $cs->getSysCodeName() }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{ $card->mntns_cards_status }}"> {{ $card->status->getSysCodeName() }}</option>
                                                    @endif
                                                </select>


                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('maintenanceType.mntns_counter_car')
                                                </label>
                                                <input type="number" class="form-control" name="mntns_cars_meter"
                                                       id="mntns_cars_meter" value="{{($card->mntns_cars_meter)}}">
                                            </div>

                                            @if($card->status->system_code == 50003)
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> {{__('mntns card closed date')}}
                                                    </label>
                                                    <input type="text" readonly class="form-control"
                                                           value="{{\Carbon\Carbon::parse($card->closed_date)->format('d-m-Y')}}">

                                                </div>
                                            @endif

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('maintenanceType.mntns_notes') </label>
                                                <textarea rows="2" class="form-control" name="mntns_cards_notes"
                                                          id="mntns_cards_notes" placeholder="Here can be your note"
                                                          value="">{{($card->mntns_cards_notes)}}</textarea>
                                            </div>


                                            {{--سند القبض--}}
                                            @if($card->mntns_cards_due_amount > 0 && $card->mntns_cards_total_amount > 0)
                                                <div class="col-md-2">
                                                    <br>
                                                    <br>
                                                    <button type="button" class="btn btn-primary btn-lg"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal" data-whatever="@mdo">
                                                        @lang('home.add_capture_bond')
                                                    </button>
                                                </div>
                                            @endif

                                            {{--سند الصرف--}}
                                            {{--<div class="col-md-2">--}}
                                            {{--<br>--}}
                                            {{--<br>--}}
                                            {{--<button type="button" class="btn btn-primary btn-lg"--}}
                                            {{--data-toggle="modal"--}}
                                            {{--data-target="#exampleModal2" data-whatever="@mdo">--}}
                                            {{--@lang('home.add_cash_bond')--}}
                                            {{--</button>--}}
                                            {{--</div>--}}

                                            @if(in_array($card->mntns_cards_status,$can_edit))
                                                <div class="col-md-2">
                                                    <br>
                                                    <br>
                                                    <button type="button" onclick="updateCard()"
                                                            class="btn btn-primary btn-block"
                                                            id="update_card">
                                                        <i class="fe fe-save mr-2"></i> @lang('maintenanceType.save')
                                                    </button>
                                                </div>
                                            @endif
                                            @if($card->mntns_cards_status == $can_close)
                                                <div class="col-md-2">
                                                    <br>
                                                    <br>
                                                    <button type="button" onclick="closeCard()"
                                                            class="btn btn-warning btn-block"
                                                            id="close_card">
                                                        <i class="fa fa-close mr-2"></i> @lang('maintenanceType.mntns_closed')
                                                    </button>
                                                </div>
                                            @endif

                                            @if($card->mntns_cards_status != $can_close)
                                                <div class="col-md-2">
                                                    <br>
                                                    <br>
                                                    <button type="button" onclick="closeCard()"
                                                            class="btn btn-warning btn-block"
                                                            id="close_card2" style="display: none">
                                                        <i class="fa fa-close mr-2"></i> @lang('maintenanceType.mntns_closed')
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        {{--سند القبض--}}
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel">@lang('home.add_capture_bond')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="{{ route('maintenance-card.addBondWithJournal') }}"
                                              method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.sub_company')</label>
                                                        @if(session('company'))
                                                            <input type="text" class="form-control" disabled=""
                                                                   value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                                        @else
                                                            <input type="text" class="form-control" disabled=""
                                                                   value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.branch')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.created_date')</label>
                                                        <input type="text" id="date" class="form-control"
                                                               disabled="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.user')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               placeholder="Company"
                                                               value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="transaction_id"
                                                       value="{{$card->mntns_cards_id}}">

                                                {{--النشاط--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bonds_activity')</label>

                                                        <input type="text" disabled="" value=" كارت الصيانه"
                                                               class="form-control">
                                                        <input type="hidden" name="transaction_type" value="71">

                                                    </div>
                                                </div>

                                                {{--الرقم المرجعي--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.reference_number')</label>

                                                        <input type="text" class="form-control"
                                                               name="bond_ref_no"
                                                               value="{{ $card->mntns_cards_no }}" readonly>
                                                    </div>
                                                </div>

                                                {{--القيمه المستحقه--}}
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.deserved_value')</label>
                                                        <input type="text" class="form-control" readonly
                                                               id="deserved_value"
                                                               value="{{ $card->mntns_cards_due_amount }}">

                                                    </div>
                                                </div>

                                                {{--نوع الحساب--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_type')</label>

                                                        <input type="text" class="form-control" readonly
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
                                                        <input type="hidden" name="customer_type" value="customer">

                                                        <label class="form-label">@lang('home.customer')</label>

                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale()=='ar' ? $card->customer->customer_name_full_ar :
                                                              $card->customer->customer_name_full_en  }}">
                                                        <input type="hidden" name="customer_id"
                                                               value="{{ $card->customer->customer_id }}">
                                                    </div>
                                                </div>

                                                {{-- قم الحساب للعميل--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_code')</label>
                                                        <input type="hidden" class="form-control"
                                                               name="bond_acc_id"
                                                               value="{{ $card->customer->customer_account_id}}">
                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale() == 'ar' ?
                                                               $card->customer->account->acc_name_ar :
                                                               $card->customer->account->acc_name_en}} . {{ $card->customer->account->acc_code }}">

                                                    </div>
                                                </div>

                                                {{--انواع الايرادات--}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.revenue_types')</label>
                                                        <select class="selectpicker" data-live-search="true"
                                                                name="bond_doc_type" required>
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($system_code_types as $system_code)
                                                                <option value="{{$system_code->system_code_id}}">
                                                                    {{ app()->getLocale()=='ar' ?
                                                                $system_code->system_code_name_ar :     $system_code->system_code_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{--طرق الدفع--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.payment_method')</label>
                                                        <select class="form-control" v-model="payment_method_code"
                                                                @change="validInputs()" name="bond_method_type"
                                                                required>
                                                            <ooption value="">@lang('home.choose')</ooption>
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
                                                               :disabled="process_number_valid"
                                                               v-model="process_number" name="process_number"
                                                               :required="!process_number_valid">
                                                    </div>
                                                </div>

                                                {{--البنك--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bank')</label>
                                                        <select class="form-control" name="bond_bank_id"
                                                                v-model="bank"
                                                                :disabled="bank_valid" :required="!bank_valid">
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
                                                               id="value" value="{{$card->mntns_cards_due_amount }}"
                                                               name="bond_amount_credit" required>
                                                        <small class="text-danger" id="error_message">
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <label class="form-label">@lang('home.notes')</label>
                                                    <textarea class="form-control" name="bond_notes"
                                                              placeholder="@lang('home.notes')"></textarea>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                            id="submit_button">
                                                        @lang('home.add_bond')
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{--سند الصرف--}}
                        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel">@lang('home.add_cash_bond')</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="{{ route('maintenance-card.addBondWithJournal2') }}"
                                              method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.sub_company')</label>
                                                        @if(session('company'))
                                                            <input type="text" class="form-control" disabled=""
                                                                   value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                                        @else
                                                            <input type="text" class="form-control" disabled=""
                                                                   value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.branch')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.created_date')</label>
                                                        <input type="text" id="date2" class="form-control"
                                                               disabled="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.user')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               placeholder="Company"
                                                               value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="transaction_id"
                                                       value="{{$card->mntns_cards_id}}">
                                                {{--النشاط--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bonds_activity')</label>

                                                        <input type="text" disabled="" value=" كارت الصيانه"
                                                               class="form-control">
                                                        <input type="hidden" name="transaction_type" value="71">

                                                    </div>
                                                </div>

                                                {{--الرقم المرجعي--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.reference_number')</label>

                                                        <input type="text" class="form-control"
                                                               name="bond_ref_no"
                                                               value="{{ $card->mntns_cards_no }}" readonly>

                                                    </div>
                                                </div>

                                                {{--نوع الحساب--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_type')</label>

                                                        <input type="text" class="form-control" readonly
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
                                                        <input type="hidden" name="customer_type" value="customer">

                                                        <label class="form-label">@lang('home.suppliers')</label>

                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale()=='ar' ? $card->customer->customer_name_full_ar :
                                                              $card->customer->customer_name_full_en  }}">
                                                        <input type="hidden" name="customer_id"
                                                               value="{{ $card->customer->customer_id }}">
                                                    </div>
                                                </div>

                                                {{--{قم الحساب للعميل--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.account_code')</label>
                                                        <input type="hidden" class="form-control"
                                                               name="bond_acc_id"
                                                               value="{{ $card->customer->customer_account_id}}">
                                                        <input type="text" readonly class="form-control"
                                                               value="{{app()->getLocale() == 'ar' ?
                                                               $card->customer->account->acc_name_ar :
                                                               $card->customer->account->acc_name_en}} . {{ $card->customer->account->acc_code }}">

                                                    </div>
                                                </div>

                                                {{--انواع المصروفات--}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.cash_types')</label>
                                                        <select class="selectpicker" data-live-search="true"
                                                                name="bond_doc_type" required>
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($system_code_types_2 as $system_code)
                                                                <option value="{{$system_code->system_code_id}}">
                                                                    {{ app()->getLocale()=='ar' ?
                                                                $system_code->system_code_name_ar :     $system_code->system_code_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6"></div>

                                                {{--طرق الدفع--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.payment_method')</label>
                                                        <select class="form-control" v-model="payment_method_code2"
                                                                @change="validInputs2()" name="bond_method_type"
                                                                required>
                                                            <ooption value="">@lang('home.choose')</ooption>
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
                                                               :disabled="process_number_valid2"
                                                               v-model="process_number2" name="process_number"
                                                               :required="!process_number_valid2">
                                                    </div>
                                                </div>

                                                {{--البنك--}}
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bank')</label>
                                                        <select class="form-control" name="bond_bank_id"
                                                                v-model="bank2"
                                                                :disabled="bank_valid2" :required="!bank_valid2">
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
                                                               v-model="bond_amount_credit"
                                                               id="value2"
                                                               name="bond_amount_credit" required>
                                                        <small class="text-danger" id="error_message2">
                                                        </small>
                                                    </div>
                                                </div>

                                                {{--نسبه الضريبه--}}
                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.vat_rate')</label>
                                                        <input type="text" class="form-control"
                                                               v-model="bond_vat_rate"
                                                               name="bond_vat_rate">
                                                    </div>
                                                </div>

                                                {{--قيمه الضريبه--}}
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.vat_amount')</label>
                                                        <input type="text" class="form-control"
                                                               v-model="bond_vat_amount"
                                                               name="bond_vat_amount" readonly>
                                                    </div>
                                                </div>

                                                {{-- ااجمالي شامل الضريبه--}}
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.total_value')</label>
                                                        <input type="text" class="form-control"
                                                               v-model="bond_amount_total"
                                                               id="total_value2"
                                                               name="bond_amount_total" required readonly>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <label class="form-label">@lang('home.notes')</label>
                                                    <textarea class="form-control" name="bond_notes"
                                                              placeholder="@lang('home.notes')"></textarea>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                            id="submit_button">
                                                        @lang('home.add_bond')
                                                    </button>
                                                </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row card">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">

                                        @include('Maintenance.MaintenanceCard.form.internal_table',['card' => $card ])

                                        @include('Maintenance.MaintenanceCard.form.external_table',['card' => $card ])

                                        @include('Maintenance.MaintenanceCard.form.part_table',['card' => $card ])

                                        @include('Maintenance.MaintenanceCard.form.total_table',['card' => $card ])


                                    </div>
                                </div>
                            </div>
                            @if($card->mntns_cards_status == $can_inv)
                                <div class="card-footer row">
                                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                                        <a href="{{config('app.telerik_server')}}?rpt={{$card->report_card_inv_url->report_url}}&id={{$card->uuid}}&lang=ar&skinName=bootstrap"
                                           title="{{trans('Print')}}" class="btn btn-warning btn-block"
                                           id="showReport"
                                           target="_blank">
                                            {{trans('Print INVOICE')}}
                                        </a>

                                    </div>
                                    <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">

                                        <a href="{{config('app.telerik_server')}}?rpt={{$card->report_card_q_url->report_url}}&id={{$card->uuid}}&lang=ar&skinName=bootstrap"
                                           title="{{trans('Print')}}" class="btn btn-warning btn-block"
                                           id="showReport"
                                           target="_blank">
                                            {{trans('Print Qutation')}}

                                        </a>
                                    </div>
                                    <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                        <a href="{{ route('maintenance-card.index') }}"
                                           class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                                    </div>
                                </div>
                            @endif
                            @if(in_array($card->mntns_cards_status,$can_edit))
                                <div class="card-footer row">
                                    <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                        <a href="{{config('app.telerik_server')}}?rpt={{$card->report_card_q_url->report_url}}&id={{$card->uuid}}&lang=ar&skinName=bootstrap"
                                           title="{{trans('Print')}}" class="btn btn-warning btn-block"
                                           id="showReport"
                                           target="_blank">
                                            {{trans('Print')}}

                                        </a>
                                    </div>
                                    <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                        <a href="{{ route('maintenance-card.index') }}"
                                           class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                                    </div>
                                </div>

                            @endif

                            @if($card->mntns_cards_status == $can_close)
                                <div class="card-footer row">
                                    <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                        <a href="{{config('app.telerik_server')}}?rpt={{$card->report_card_w_url->report_url}}&id={{$card->uuid}}&lang=ar&skinName=bootstrap"
                                           title="{{trans('Print')}}" class="btn btn-warning btn-block"
                                           id="showReport"
                                           target="_blank">
                                            {{trans('Print')}}

                                        </a>
                                    </div>
                                    <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                        <a href="{{ route('maintenance-card.index') }}"
                                           class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                                    </div>
                                </div>

                            @endif

                        </div>
                    </div>
                    <br>
                </div>


                <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bonds_cash as $bond)
                                        <tr>
                                            <td>{{ $bond->bond_code }}</td>
                                            <td>{{ $bond->created_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>{{ $bond->bond_acc_id }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                                            <td>{{ $bond->bond_amount_credit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalCashMaintenanceCard)
                                                    <a href="{{ route('journal-entries.show',$bond->journalCashMaintenanceCard->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->journalCashMaintenanceCard->journal_hd_code}}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-print"></i></a>
                                                <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="bonds-capture-grid" role="tabpanel">
                    <div class="card-body">
                        <form action="{{route('maintenance-card.storeBond')}}" method="post">
                            @csrf
                            <div class="row mb-2">

                                <input type="hidden" name="bond_id" :value="bond.bond_id"
                                       v-if="Object.keys(bond).length > 0">
                                <input type="hidden" name="maintenance_card_id" value="{{$card->mntns_cards_id}}">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="bond_code_capture"
                                           placeholder="الكود.........." v-model="bond_code_capture"
                                           @change="getBond()">
                                    <small v-if="bond_message" class="text-danger">@{{ bond_message }}</small>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary"
                                            :disabled="bond_disabled">@lang('home.save')</button>
                                </div>


                            </div>
                        </form>
                        <div class="row card">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="Object.keys(bond).length > 0">
                                        <td>@{{ bond.bond_code }}</td>
                                        <td>@{{ bond.created_date }}</td>
                                        <td>@if(app()->getLocale() == 'ar')
                                                @{{ bond.company.company_name_ar }}
                                            @else
                                                @{{ bond.company.company_name_en }}
                                            @endif
                                        </td>
                                        <td>@if(app()->getLocale() == 'ar')
                                                @{{ bond.branch.branch_name_ar }}
                                            @else
                                                @{{ bond.branch.branch_name_en }}
                                            @endif
                                        </td>
                                        <td>@{{ bond.account ? bond.account.account_code : '' }}</td>
                                        <td>@if(app()->getLocale() == 'ar')
                                                @{{ bond.paymentMethod.system_code_name_ar }}
                                            @else
                                                @{{ bond.paymentMethod.system_code_name_en }}
                                            @endif
                                        </td>
                                        <td>@{{ bond.bond_amount_debit }}</td>
                                        <td>
                                            @if(app()->getLocale() == 'ar')
                                                @{{ bond.user.user_name_ar }}
                                            @else
                                                @{{ bond.user.user_name_en }}
                                            @endif
                                        </td>
                                        <td>
                                            @{{ bond.journalCapture.journal_hd_code }}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @foreach($bonds_capture as $bond)
                                        <tr>
                                            <td>{{ $bond->bond_code }}</td>
                                            <td>{{ $bond->created_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>{{ $bond->account->acc_name_ar .   $bond->account->acc_code}}</td>
                                            <td>
                                                @if($bond->bond_method_type)
                                                    {{ app()->getLocale() == 'ar' ? \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                    ->first()->system_code_name_ar :
                                                  \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                    ->first()->system_code_name_en }}
                                                @endif
                                            </td>
                                            <td>{{ $bond->bond_amount_debit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>

                                                @if($bond->maintenanceCard)
                                                    <a href="{{ route('journal-entries.show',$bond->maintenanceCard
                                                        ->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->maintenanceCard->journal_hd_code}}
                                                    </a>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_receipt->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.print')"><i
                                                            class="fa fa-print"></i></a>

                                                <a href="{{ route('Bonds-capture.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="purchases-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-striped table-lg inv_table">
                                <thead class="thead-light">
                                <tr>
                                    <th> @lang('sales.warehouse_type') </th>
                                    <th> {{__('item type')}} </th>
                                    <th> @lang('sales.inv_no') </th>
                                    <th> @lang('sales.create_date') </th>
                                    <th> @lang('sales.customer_name') </th>
                                    <th> @lang('sales.status') </th>
                                    <th> @lang('sales.total') </th>
                                    <th> @lang('home.bond') </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchases as $purchase)
                                    <tr>
                                        <td>{{$purchase->storeCategory->getSysCodeName()}}</td>
                                        <td>{{$purchase->detailsO ? $purchase->detailsO->item->item_name_a .' '. $purchase->detailsO->item->item_code_1 :''}}</td>
                                        <td>{{$purchase->store_hd_code}}</td>
                                        <td>{{$purchase->created_date}}</td>
                                        <td>{{$purchase->store_acc_name}}</td>
                                        <td>{{$purchase->status->getSysCodeName()}}</td>
                                        <td>{{$purchase->store_vou_total}}</td>
                                        <td>{{$purchase->bond ? $purchase->bond_code : 'لا يوجد سند'}}</td>
                                        <td>

                                            @foreach(session('job')->permissions as $job_permission)
                                                @if($job_permission->app_menu_id == 65 && $job_permission->permission_add)
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <a class="btn btn-icon"
                                                               href="{{ route('store-sales-inv.edit',$purchase->uuid ) }}"
                                                               title="" target="_blank">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4">
                                                            @if($purchase->status)
                                                                @if($purchase->status->system_code != 125001)
                                                                    <a class="btn btn-icon"
                                                                       href="{{config('app.telerik_server')}}?rpt={{$purchase->report_url_inv->report_url}}&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                                                       title="" target="_blank">
                                                                        <i class="fa fa-print"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($job_permission->app_menu_id == 65 && $job_permission->permission_approve)
                                                    @if($purchase->store_vou_status == App\Models\SystemCode::where('company_id',
                                        $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id)
                                                        <div class="col-md-4">
                                                            <button type="button"
                                                                    onclick="genInvoice('{{$purchase->uuid}}')"
                                                                    class="btn btn-primary btn-sm" id="export_invoice"
                                                                    style="margin-bottom: 10px;margin-right: 10px">
                                                                اصدار فاتورة
                                                            </button>
                                                        </div>
                                                    @endif
                                                    @endif


                                            @endforeach


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

    <div id="add_tech_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="width:130%">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">
                        &times;
                    </button>
                    <h4 class="modal-title" style="text-align:right">  @lang('maintenanceType.mntns_tech')</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                        <button type="button" id="btnCancel" class="btn btn-secondary btn-block"
                                data-dismiss="modal"
                                onclick="closeItemModal()"> @lang('maintenanceType.mntns_b_cance')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
                @else
        var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif
    </script>

    <script type="text/javascript">
        $mntns_cards_item_disc_type = '';
        $mntns_cards_item_disc_amount = 0;

        internal_total_disc_amount = 0;
        internal_total_vat_amount = 0;
        internal_total_amount = 0;

        external_total_vat_amount = 0;
        external_total_amount = 0;

        part_total_disc_amount = 0;
        part_total_vat_amount = 0;
        part_total_amount = 0


        // -- INTERNAL TABLE

        function genInvoice(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-item-sales-invnew.generate')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        $(document).ready(function () {

            // $('#exampleModal').modal('show')
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)
            $('#date2').val(output)


            $('#value').keyup(function () {
                console.log($('#deserved_value').val())

                if (parseInt($('#deserved_value').val()) < parseInt($('#value').val())) {
                    $('#submit_button').attr("disabled", "disabled").button('refresh');
                    $('#error_message').text('المسدد اكبر من المستحق')
                } else {
                    $('#submit_button').removeAttr("disabled").button('refresh');
                    $('#error_message').text('')
                }
            })

            lang = '{{app()->getLocale()}}';
            $('#mntns_cards_item_id').on('change', function () {
                //console.log($('#mntns_cards_item_id').val());
                //console.log($('#mntns_cards_item_id :selected').data('mntnstypehours'));
                //console.log($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                //console.log($('#mntns_cards_item_id :selected').data('mntnstypeempno'));
                $('#mntns_type_hours').val($('#mntns_cards_item_id :selected').data('mntnstypehours'));
                $('#mntns_type_value').val($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                $('#mntns_type_emp_no').val($('#mntns_cards_item_id :selected').data('mntnstypeempno'));
                $('#mntns_cards_item_disc_amount').val(0);
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                $vat_percantage = 15 / 100;
                $('#vat_value').val($value_befor_vat * $vat_percantage);
                $vat_value = $('#vat_value').val();
                $value_after_vat = $value_befor_vat + ($value_befor_vat * $vat_percantage);
                $('#total_after_vat').val($value_after_vat);
            });

            $('#mntns_cards_item_disc_type').on('change', function () {
                $mntns_cards_item_disc_type = $('#mntns_cards_item_disc_type').val();
                $('#mntns_cards_item_disc_amount').val(0);
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                $vat_percantage = 15 / 100;
                $('#vat_value').val($value_befor_vat * $vat_percantage);
                $vat_value = $('#vat_value').val();
                $value_after_vat = $value_befor_vat + ($value_befor_vat * $vat_percantage);
                $('#total_after_vat').val($value_after_vat);


            });
            $("#mntns_cards_item_disc_amount").change(function () {
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                if ($('#mntns_cards_item_disc_type').val() == '') {
                    alert('الرجاء ادخال نوع الخصم');
                }

                $mntns_cards_item_disc_type = parseFloat($('#mntns_cards_item_disc_type').val());
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                $vat_percantage = 15 / 100;
                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                //console.log($('#mntns_cards_item_disc_type').val());
                if ($mntns_cards_item_disc_type == 533) {
                    //console.log('per');
                    if ($('#mntns_cards_item_disc_amount').val() > 100) {
                        $('#mntns_cards_item_disc_amount').val(0)
                        return alert('لايمكن تطبيق خصم اكثر من 100%');
                    }
                    $discount_amount = ($value_befor_vat * parseFloat($('#mntns_cards_item_disc_amount').val()) / 100);
                    $('#mntns_cards_item_disc_value').val($discount_amount);
                    //console.log($discount_amount);
                }
                else {
                    //console.log('val');
                    if ($('#mntns_cards_item_disc_amount').val() > $value_befor_vat) {
                        $('#mntns_cards_item_disc_amount').val(0)
                        return alert(' لا يمكن تطبيق خصم اكثر من القيمة');
                    }
                    $discount_amount = $mntns_cards_item_disc_amount;
                    $('#mntns_cards_item_disc_value').val($discount_amount);
                    //console.log($discount_amount);
                }

                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue')) - $discount_amount;

                $('#vat_value').val($value_befor_vat * $vat_percantage);
                $vat_value = $('#vat_value').val();
                $value_after_vat = $value_befor_vat + ($value_befor_vat * $vat_percantage);
                $('#total_after_vat').val($value_after_vat);


            });

            // EXTERNAL ITEM

            $('#mntns_type_value_external').on('change', function () {
                $('#vat_value_external').val($('#mntns_type_value_external').val() * 15 / 100);
                $('#total_after_vat_external').val(parseFloat($('#mntns_type_value_external').val()) + parseFloat($('#vat_value_external').val()))
            });

            $('#warehouses_type_id').on('focus', function () {
                console.log('changed');
                $("#part_mntns_cards_item_id option").remove();
                var warehouses_type_id = $('#warehouses_type_id').val();
                $.ajax({
                    url: '{{ route( 'maintenanced-card.get.part.list.by.warehouses.id' ) }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "warehouses_type_id": warehouses_type_id
                    },
                    type: 'get',
                    dataType: 'json',
                    success: function (result) {
                        $('#part_mntns_cards_item_id').append($('<option>', {value: '', text: 'choose'}));
                        $.each(result.data, function (k) {
                            data = result.data;
                            if (lang == 'ar') {
                                $('#part_mntns_cards_item_id').append(
                                    $('<option>',
                                        {
                                            value: data[k].item_id,
                                            text: result.data[k].item_code + '-' + result.data[k].item_name_a + '- المتوفر عدد' + data[k].item_balance + ' ' + data[k].item_price_mntns,
                                            data: {
                                                'unit-price': data[k].item_price_mntns,
                                                'balance': data[k].item_balance,
                                                'itemname': data[k].item_name_a,
                                                'storename': data[k].item_category.system_code_name_ar
                                            }
                                        }
                                    )
                                );
                            }
                            else {
                                $('#part_mntns_cards_item_id').append(
                                    $('<option>',
                                        {
                                            value: data[k].item_id,
                                            text: result.data[k].item_code + '-' + result.data[k].item_name_e + '- المتوفر عدد ' + data[k].item_balance,
                                            data: {
                                                'unit-price': data[k].item_price_mntns,
                                                'balance': data[k].item_balance,
                                                'itemname': data[k].item_name_e,
                                                'storename': data[k].item_category.system_code_name_en
                                            }
                                        }
                                    )
                                );
                            }

                        });
                        $('#part_mntns_cards_item_id').selectpicker('refresh');
                    },
                    error: function () {
                        //handle errors
                        alert('error...');
                    }
                });
            });

            $('#part_mntns_cards_item_id').on('change', function () {
                $('#part_unit_price').val($('#part_mntns_cards_item_id :selected').data('unit-price'));
                $('#part_qty').val(1);
                $('#part_mntns_type_value').val($('#part_unit_price').val() * $('#part_qty').val());
                $('#part_vat_value').val($('#part_mntns_type_value').val() * 15 / 100);
                $('#part_total_after_vat').val(parseFloat($('#part_mntns_type_value').val()) + parseFloat($('#part_vat_value').val()));
                $("#part_mntns_cards_item_disc_amount").val(0);
                $('#part_vat_value').val($('#part_mntns_type_value').val() * 15 / 100);
                $('#part_total_after_vat').val(parseFloat($('#part_mntns_type_value').val()) + parseFloat($('#part_vat_value').val()));
            });


            $('#part_qty').on('change', function () {
                if ($('#part_unit_price').val() <= 0) {
                    $('#part_qty').val(0);
                    return toastr.warning('لابد من تحديد الصنف اولا');

                }
                if ($('#part_qty').val() <= 0) {
                    $('#part_qty').val(0);
                    return toastr.warning('الكمية لابد ان تكون اكبر من صفر');

                }
                $('#part_mntns_type_value').val($('#part_unit_price').val() * $('#part_qty').val());
                $('#part_vat_value').val($('#part_mntns_type_value').val() * 15 / 100);
                $('#part_total_after_vat').val(parseFloat($('#part_mntns_type_value').val()) + parseFloat($('#part_vat_value').val()));
                $("#part_mntns_cards_item_disc_amount").val(0);
            });

            $('#part_mntns_cards_item_disc_type').on('change', function () {
                $part_mntns_cards_item_disc_type = $('#part_mntns_cards_item_disc_type').val();
                $('#part_mntns_cards_item_disc_amount').val(0);
                $part_mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                $part_value_befor_vat = parseFloat($('#part_mntns_cards_item_id :selected').data('unit-price'));

                $('#part_mntns_type_value').val($('#part_unit_price').val() * $('#part_qty').val());
                $('#part_vat_value').val($('#part_mntns_type_value').val() * 15 / 100);
                $('#part_total_after_vat').val(parseFloat($('#part_mntns_type_value').val()) + parseFloat($('#part_vat_value').val()));

            });

            $("#part_mntns_cards_item_disc_amount").change(function () {

                $part_mntns_cards_item_disc_type = parseFloat($('#part_mntns_cards_item_disc_type').val());
                $part_mntns_cards_item_disc_amount = parseFloat($('#part_mntns_cards_item_disc_amount').val());
                $vat_percantage = 15 / 100;
                $part_value_befor_vat = parseFloat($('#part_mntns_type_value').val());

                if ($part_mntns_cards_item_disc_type == 533) {
                    if ($('#part_mntns_cards_item_disc_amount').val() > 100) {
                        $('#part_mntns_cards_item_disc_amount').val(0)
                        return alert('لايمكن تطبيق خصم اكثر من 100%');
                    }
                    $discount_amount = ($part_value_befor_vat * parseFloat($('#part_mntns_cards_item_disc_amount').val()) / 100);
                    $('#part_mntns_cards_item_disc_value').val($discount_amount);
                }
                else {
                    if ($('#part_mntns_cards_item_disc_amount').val() > $part_value_befor_vat) {
                        $('#part_mntns_cards_item_disc_amount').val(0)
                        return alert(' لا يمكن تطبيق خصم اكثر من القيمة');
                    }
                    $discount_amount = $part_mntns_cards_item_disc_amount;
                    $('#part_mntns_cards_item_disc_value').val($discount_amount);
                }

                $part_value_befor_vat = $part_value_befor_vat - $discount_amount;

                $('#part_vat_value').val($part_value_befor_vat * 15 / 100);
                $part_vat_value = parseFloat($('#part_vat_value').val());
                $part_value_after_vat = $part_value_befor_vat + $part_vat_value;
                $('#part_total_after_vat').val($part_value_after_vat);

            });

        });

        function saveTechRow() {
            if (($('#mntns_tech_emp_id').val() == '') || ($('#mntns_tech_hours').val() == '')) {
                return toastr.warning('لا يوجد بيانات لاضافتها');
            }

            rowNo = parseFloat($('#tech_row_count').val()) + 1;
            row_data = {
                'id': 'tr' + rowNo,
                'count': rowNo,
                'mntns_cards_dt': $('#mntns_cards_dt').val(),
                'mntns_cards_dt_id': $('#mntns_cards_dt_id').val(),
                'mntns_tech_emp_id': parseFloat($('#mntns_tech_emp_id').val()),
                'mntns_tech_emp_name': $('#mntns_tech_emp_id :selected').data('name'),
                'mntns_tech_hours': parseFloat($('#mntns_tech_hours').val()),

            };

            url = '{{ route('maintenance-card.save.tech') }}';
            var form = new FormData($('#tech_data_form')[0]);
            form.append('tech_table_data', JSON.stringify(row_data));
            var data = form;
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    updateTechTable(rowNo, row_data, data.uuid);
                    $('#tech_row_count').val(rowNo);
                    $("#mntns_tech_emp_id").val("").change();
                    $("#mntns_tech_hours").val("")
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function updateTechTable(rowNo, rowData, uuid) {
            tableBody = $("#tech_maintenance_table");
            uuid = "'" + uuid + "'";
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'mntns_tech_emp_name">' + rowData['mntns_tech_emp_name'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_tech_hours">' + rowData['mntns_tech_hours'] + '</td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
        }

        function saveInternalRow() {
            if (($('#mntns_cards_item_id').val() == '') || ($('#mntns_cards_item_disc_type').val() == '')) {
                return toastr.warning('لا يوجد بيانات لاضافتها');
            }
            tableBody = $("#internal_maintenance_table");
            rowNo = parseFloat($('#internal_row_count').val()) + 1; //// ;
            row_data = {
                'id': 'tr' + rowNo,
                'count': rowNo,
                'mntns_cards_id': $('#mntns_cards_id').val(),
                'mntns_cards_item_id': parseFloat($('#mntns_cards_item_id').val()),
                'mntns_cards_item': $('#mntns_cards_item_id :selected').data('mntnscardsitem'),
                'mntns_type_hours': parseFloat($('#mntns_cards_item_id :selected').data('mntnstypehours')),
                'mntns_type_emp_no': parseFloat($('#mntns_cards_item_id :selected').data('mntnstypeempno')),
                'mntns_type_value': parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue')),
                'vat_id': (15 / 100),
                'vat_per': parseFloat((15 / 100)),
                'total_befor_vat': parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue')),
                'total_afte_vat': parseFloat($('#total_after_vat').val()),
                'vat_value': parseFloat($('#vat_value').val()),
                'mntns_cards_item_disc_type': parseFloat($('#mntns_cards_item_disc_type').val()),
                'mntns_cards_item_disc_type name': $('#mntns_cards_item_disc_type :selected').data('mntnsdisctype'),
                'mntns_cards_item_disc_value': parseFloat($('#mntns_cards_item_disc_amount').val()),
                'mntns_cards_item_disc_amount': parseFloat($('#mntns_cards_item_disc_value').val()),

            };

            url = '{{ route('maintenance-card.item.store') }}';
            var form = new FormData($('#card_data_form')[0]);
            form.append('internal_table_data', JSON.stringify(row_data));
            form.append('mntns_cards_internal_repairs', internal_total_amount);
            form.append('item_type', 'internal');

            var data = form;
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    updateInternalTable(rowNo, row_data, data.uuid, data.total)
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function updateTotalAfterDel(data, total) {

            // console.log(total['total_mntns_cards_vat_amount']);
            // console.log(total['total_mntns_cards_disc_amount']);
            // console.log(total['total_mntns_cards_amount']);

            $('#internal_total_disc_amount_div').text(total['total_mntns_cards_disc_amount']);
            $('#internal_total_disc_amount').val(total['total_mntns_cards_disc_amount']);

            $('#internal_total_vat_amount_div').text(total['total_mntns_cards_vat_amount']);
            $('#internal_total_vat_amount').val(total['total_mntns_cards_vat_amount']);

            $('#internal_total_amount_div').text(total['total_mntns_cards_amount']);
            $('#internal_total_amount').val(total['total_mntns_cards_amount']);

            //update total

            $('#external_total_vat_amount_div').text(total['total_external_mntns_cards_vat_amount']);
            $('#external_total_vat_amount').val(total['total_external_mntns_cards_vat_amount']);

            $('#external_total_amount_div').text(total['total_external_mntns_cards_amount']);
            $('#external_total_amount').val(total['total_external_mntns_cards_amount']);

            //update part total

            $('#part_total_disc_amount_div').text(total['total_part_mntns_cards_disc_amount']);
            $('#part_total_disc_amount').val(total['total_part_mntns_cards_disc_amount']);

            $('#part_total_vat_amount_div').text(total['total_part_mntns_cards_vat_amount']);
            $('#part_total_vat_amount').val(total['total_part_mntns_cards_vat_amount']);

            $('#part_total_amount_div').text(total['total_part_mntns_cards_amount']);
            $('#part_total_amount').val(total['total_part_mntns_cards_amount']);

            //update all card tootal

            $('#g_total_disc_amount_div').text(total['total_cards_disc_amount']);
            $('#g_total_disc_amount').val(total['total_cards_disc_amount']);

            $('#g_total_vat_amount_div').text(total['total_cards_vat_amount']);
            $('#g_total_vat_amount').val(total['total_cards_vat_amount']);

            $('#g_total_amount_div').text(total['total_cards_amount']);
            $('#g_total_amount').val(total['total_cards_amount']);

            $('#value').val(total['mntns_cards_due_amount'])
            $('#deserved_value').val(total['mntns_cards_due_amount'])
            $('#g_total_due_div').text(total['mntns_cards_due_amount']);

        }

        function deleteItem(uuid) {
            status = 'false';
            var form = new FormData($('#card_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('maintenance-card.item.delete')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    console.log('return true');
                    console.log(data.data.uuid);
                    $('#' + data.data.uuid).remove();
                    updateTotalAfterDel(data.data, data.total);
                    return 'true';
                }
                else {
                    toastr.warning(data.msg);
                    console.log('return dalse');
                    return 'false';
                }
            });


        }

        function addTech(uuid) {
            $.ajax({
                type: 'GET',
                url: '{{route('maintenance-card.add.tech')}}',
                data: {
                    _token: "{{ csrf_token() }}",
                    uuid: uuid,
                },
            }).done(function (data) {
                if (data.success) {
                    $('#add_tech_modal').modal("show").on('shown.bs.modal', function () {
                        $('#add_tech_modal .modal-body').html(data.view);
                    });
                }
                else {
                    alert('ERORR');
                }
            });
        }


        function updateInternalTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'mntns_cards_item">' + rowData['mntns_cards_item'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_type_hours">' + rowData['mntns_type_hours'] + '</td>' +
                //'<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_emp_no">'+ rowData['mntns_type_emp_no'] +'</td>'+
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_type_value">' + rowData['mntns_type_value'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_cards_item_disc_type name">' + rowData['mntns_cards_item_disc_type name'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_cards_item_disc_amount">' + rowData['mntns_cards_item_disc_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'vat_value">' + rowData['vat_value'] + '</td>' +

                '<td  class="ctd" id="' + 'tr' + rowNo + 'total_afte_vat">' + rowData['total_afte_vat'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-primary m-btn m-btn--icon m-btn--icon-only" onclick="addTech(' + uuid + ')"><i class="fa fa-user-plus"></i></button>' +
                '<button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
            internal_total_disc_amount = parseFloat($('#internal_total_disc_amount').val()) + rowData['mntns_cards_item_disc_amount'];
            internal_total_vat_amount = parseFloat($('#internal_total_vat_amount').val()) + rowData['vat_value'];
            internal_total_amount = parseFloat($('#internal_total_amount').val()) + rowData['total_afte_vat'];


            $('#internal_total_disc_amount_div').text(total['total_mntns_cards_disc_amount']);
            $('#internal_total_disc_amount').val(total['total_mntns_cards_disc_amount']);

            $('#internal_total_vat_amount_div').text(total['total_mntns_cards_vat_amount']);
            $('#internal_total_vat_amount').val(total['total_mntns_cards_vat_amount']);

            $('#internal_total_amount_div').text(total['total_mntns_cards_amount']);
            $('#internal_total_amount').val(total['total_mntns_cards_amount']);

            //update all card tootal
            $('#g_total_disc_amount_div').text(total['total_cards_disc_amount']);
            $('#g_total_vat_amount_div').text(total['total_cards_vat_amount']);
            $('#g_total_amount_div').text(total['total_cards_amount']);
            $('#g_total_due_div').text(total['mntns_cards_due_amount']);
            $('#g_total_payment_div').text(total['mntns_cards_payment_amount']);
            $('#value').val(total['mntns_cards_due_amount'])
            $('#deserved_value').val(total['mntns_cards_due_amount'])


            //reset all input after save row
            $("#mntns_cards_item_id").val("").change();
            $("#mntns_cards_item_disc_type").val("").change();
            $('#mntns_cards_item_disc_amount').val(0);
            $('#vat_value').val(0);
            $('#total_after_vat').val(0);

        }

        // END INTERNAL TABLE

        function checkExternalInput() {

            if (
                $('#supplier_id').val() == '' ||
                $('#mntns_cards_item_hours_external').val() == '' ||
                $('#mntns_type_value_external').val() == '' ||

                $('#customer_tax_no').val() == '' ||
                $('#customer_name').val() == '' ||
                $('#customer_address').val() == '' ||
                $('#customer_phone').val() == '' ||
                $('#payment_tems').val() == '' ||
                $('#account_id').val() == '' ||

                $('#mntns_customer_cost_external').val() == '' ||
                $('#invoice_no_external').val() == '' ||
                $('#invoice_date_external').val() == '') {
                return false;
            }

            return true;

        }

        function saveExternalRow() {

            if (!checkExternalInput()) {
                return toastr.warning('الرجاء التاكد من ادخال كافة الحقول');
            }

            rowNo = parseFloat($('#external_row_count').val()) + 1;

            row_data = {
                'id': 'tr' + rowNo,
                'count': rowNo,
                'supplier_id': parseFloat($('#supplier_id').val()),
                'customer_tax_no': parseFloat($('#customer_tax_no').val()),
                'customer_name': ($('#customer_name').val()),
                'customer_address': ($('#customer_address').val()),
                'customer_phone': parseFloat($('#customer_phone').val()),
                'payment_tems': parseFloat($('#payment_tems').val()),
                'account_id': parseFloat($('#account_id').val()),
                'mntns_cards_item_id': parseFloat($('#mntns_cards_item_id_external').val()),
                'mntns_cards_item_notes': $('#mntns_cards_item_notes').val(),
                'mntns_cards_item': $('#mntns_cards_item_id_external :selected').data('mntnscardsitem'),
                'mntns_type_hours': parseFloat($('#mntns_cards_item_hours_external').val()),
                'mntns_type_emp_no': 0,
                'mntns_type_value': parseFloat($('#mntns_type_value_external').val()),
                'vat_id': (15 / 100),
                'vat_per': parseFloat($('#customer_vat_rate').val()),
                'total_befor_vat': parseFloat($('#mntns_type_value_external').val()),
                'total_afte_vat': parseFloat($('#total_after_vat_external').val()),
                'vat_value': parseFloat($('#vat_value_external').val()),

                'mntns_cards_item_disc_type': 0,
                'mntns_cards_item_disc_type name': 0,
                'mntns_cards_item_disc_value': 0,
                'mntns_cards_item_disc_amount': 0,

                'mntns_customer_cost_external': parseFloat($('#mntns_customer_cost_external').val()),
                'invoice_no_external': $('#invoice_no_external').val(),
                'invoice_date_external': $('#invoice_date_external').val(),
                //   'invoice_file_external': 0,//$('$invoice_file_external').val()

            };

            var form = new FormData($('#card_data_form')[0]);
            form.append('external_table_data', JSON.stringify(row_data));
            form.append('mntns_cards_external_repairs', external_total_amount);
            form.append('item_type', 'external');

            saveItem('external', form)
        }

        function updateExternalTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            tableBody = $("#external_maintenance_table");
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + row_data['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'mntns_cards_item">' + row_data['customer_name'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'mntns_cards_item_notes">' + row_data['mntns_cards_item_notes'] + '</td>' +
                // '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_type_hours">' + row_data['mntns_type_hours'] + '</td>' +
                // '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_emp_no">'+ row_data['mntns_type_emp_no'] +'</td>'+
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_type_value">' + row_data['mntns_type_value'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'vat_value">' + row_data['vat_value'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'total_afte_vat">' + row_data['total_afte_vat'] + '</td>' +

                // '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_customer_cost_external">' + row_data['mntns_customer_cost_external'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'invoice_no_external">' + row_data['invoice_no_external'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'invoice_date_external">' + row_data['invoice_date_external'] + '</td>' +
                //  '<td  class="ctd" id="' + 'tr' + rowNo + 'invoice_file_external">' + row_data['invoice_file_external'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);


            $('#external_total_vat_amount_div').text(total['total_mntns_cards_vat_amount']);
            $('#external_total_vat_amount').val(total['total_mntns_cards_vat_amount']);

            $('#external_total_amount_div').text(total['total_mntns_cards_amount']);
            $('#external_total_amount').val(total['total_mntns_cards_amount']);

            //update all total
            $('#g_total_disc_amount_div').text(total['total_cards_disc_amount']);
            $('#g_total_vat_amount_div').text(total['total_cards_vat_amount']);
            $('#g_total_amount_div').text(total['total_cards_amount']);
            $('#g_total_due_div').text(total['mntns_cards_due_amount']);
            $('#g_total_payment_div').text(total['mntns_cards_payment_amount']);
            $('#value').val(total['mntns_cards_due_amount'])
            $('#deserved_value').val(total['mntns_cards_due_amount'])


            //reset all input after save row
            $("#mntns_cards_item_id").val("").change();
            $("#mntns_cards_item_notes").val("").change();
            $("#mntns_cards_item_disc_type").val("").change();
            $('#mntns_cards_item_disc_amount').val(0);
            $('#vat_value').val(0);
            $('#total_after_vat').val(0);

        }


        // END EXTERNAL TABLE

        //PART TABLE

        function LoadItemList() {
            console.log('changed');
        }

        function checkPartInput() {

            if (
                $('#warehouses_type_id').val() == '' ||
                $('#part_mntns_cards_item_id').val() == '' ||
                $('#part_unit_price').val() == '' ||
                $('#part_qty').val() == 0) {
                return false;
            }

            return true;

        }

        function savePartRow() {

            if (!checkPartInput()) {
                return toastr.warning('الرجاء التاكد من ادخال كافة الحقول');
            }

            rowNo = parseFloat($('#part_row_count').val()) + 1;

            row_data = {
                'id': 'tr' + rowNo,
                'count': rowNo,
                'mntns_cards_item_id': parseFloat($('#part_mntns_cards_item_id').val()),
                'mntns_cards_item': $('#part_mntns_cards_item_id :selected').data('itemname'),
                'store_category_type': $('#part_mntns_cards_item_id :selected').data('storeid'),
                'mntns_cards_store_item': $('#part_mntns_cards_item_id :selected').data('storename'),
                'mntns_type_hours': 0,
                'mntns_type_emp_no': 0,
                'mntns_cards_item_qty': $('#part_qty').val(),
                'mntns_cards_item_price': $('#part_mntns_cards_item_id :selected').data('unit-price'),
                'mntns_type_value': parseFloat($('#part_mntns_type_value').val()),
                'vat_id': (15 / 100),
                'vat_per': parseFloat((15 / 100)),
                'total_befor_vat': parseFloat($('#part_mntns_type_value').val()),
                'total_afte_vat': parseFloat($('#part_total_after_vat').val()),
                'vat_value': parseFloat($('#part_vat_value').val()),

                'mntns_cards_item_disc_type': parseFloat($('#part_mntns_cards_item_disc_type').val()),
                'mntns_cards_item_disc_type name': $('#part_mntns_cards_item_disc_type :selected').data('mntnsdisctype'),
                'mntns_cards_item_disc_value': parseFloat($('#part_mntns_cards_item_disc_amount').val()),
                'mntns_cards_item_disc_amount': parseFloat($('#part_mntns_cards_item_disc_value').val()),

            };

            var form = new FormData($('#card_data_form')[0]);
            form.append('part_table_data', JSON.stringify(row_data));
            form.append('mntns_cards_spare_parts', part_total_amount);
            form.append('item_type', 'part');

            saveItem('part', form)
        }

        function updatePartTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            tableBody = $("#part_maintenance_table");
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'mntns_cards_store_item">' + rowData['mntns_cards_store_item'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'mntns_cards_item">' + rowData['mntns_cards_item'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_cards_item_qty">' + rowData['mntns_cards_item_qty'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_cards_item_price">' + rowData['mntns_cards_item_price'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_type_value">' + rowData['mntns_type_value'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'vat_value">' + rowData['vat_value'] + '</td>' +

                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_cards_item_disc_type name">' + rowData['mntns_cards_item_disc_type name'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'mntns_cards_item_disc_amount">' + rowData['mntns_cards_item_disc_amount'] + '</td>' +

                '<td  class="ctd" id="' + 'tr' + rowNo + 'total_afte_vat">' + rowData['total_afte_vat'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
            part_total_disc_amount = parseFloat($('#part_total_disc_amount').val()) + rowData['mntns_cards_item_disc_amount'];
            part_total_vat_amount = parseFloat($('#part_total_vat_amount').val()) + rowData['vat_value'];
            part_total_amount = parseFloat($('#part_total_amount').val()) + rowData['total_afte_vat'];


            $('#part_total_disc_amount_div').text(total['total_mntns_cards_disc_amount']);
            $('#part_total_disc_amount').val(total['total_mntns_cards_disc_amount']);

            $('#part_total_vat_amount_div').text(total['total_mntns_cards_vat_amount']);
            $('#part_total_vat_amount').val(total['total_mntns_cards_vat_amount']);

            $('#part_total_amount_div').text(total['total_mntns_cards_amount']);
            $('#part_total_amount').val(total['total_mntns_cards_amount']);

            //update all card tootal
            $('#g_total_disc_amount_div').text(total['total_cards_disc_amount']);
            $('#g_total_vat_amount_div').text(total['total_cards_vat_amount']);
            $('#g_total_amount_div').text(total['total_cards_amount']);
            $('#g_total_due_div').text(total['mntns_cards_due_amount']);
            $('#g_total_payment_div').text(total['mntns_cards_payment_amount']);
            $('#value').val(total['mntns_cards_due_amount'])
            $('#deserved_value').val(total['mntns_cards_due_amount'])


            //reset all input after save row
            // $("#mntns_cards_item_id").val("").change();
            // $("#mntns_cards_item_disc_type").val("").change();
            // $('#mntns_cards_item_disc_amount').val(0);
            // $('#vat_value').val(0);
            // $('#total_after_vat').val(0);

        }

        function saveItem(itemType, data) {
            url = '{{ route('maintenance-card.item.store') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    if (itemType == 'internal') {
                        updateInternalTable(rowNo, row_data, data.uuid, data.total)
                    }
                    if (itemType == 'external') {
                        updateExternalTable(rowNo, row_data, data.uuid, data.total)
                    }
                    if (itemType == 'part') {
                        updatePartTable(rowNo, row_data, data.uuid, data.total)
                    }

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function updateCard() {
            $('#update_card').attr('disabled', 'disabled')
            url = '{{ route('maintenance-card.update') }}';
            var form = new FormData($('#card_data_form')[0]);
            $.ajax({
                type: 'POST',
                url: url,
                data: form,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    $('#update_card').css('display', 'none')
                    $('#close_card2').css('display', 'block')
                }
                else {
                    toastr.warning(data.msg);
                    $('#update_card').removeAttr('disabled')
                }
            });
        }

        function closeCard() {
            $('#close_card').attr('disabled', 'disabled')
            $('#close_card2').attr('disabled', 'disabled')
            url = '{{ route('maintenance-card.close') }}';
            var form = new FormData($('#card_data_form')[0]);
            $.ajax({
                type: 'POST',
                url: url,
                data: form,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    $('#close_card').attr('disabled', 'disabled')
                    $('#close_card2').attr('disabled', 'disabled')
                }
                else {
                    toastr.warning(data.msg);
                    $('#close_card').removeAttr('disabled')
                    $('#close_card2').removeAttr('disabled')
                }
            });
        }


    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                card: {!! $card !!},
                payment_method_code: '',
                process_number_valid: true,
                bank_valid: true,
                bank: '',
                process_number: '',
                payment_method_code2: '',
                process_number_valid2: true,
                bank_valid2: true,
                bank2: '',
                process_number2: '',
                bond_vat_rate: 0,
                bond_amount_credit: 0,
                bond: {},
                bond_disabled: true,
                bond_message: '',
                bond_code_capture: '',
                customer_id: '{{$card->customer_id}}',

                supplier_id: '',
                customer_tax_no: '',
                customer_name: '',
                customer_phone: '',
                customer_address: '',
                customer_vat_rate: '',
            },
            mounted() {
                this.bond_vat_rate = .15
            },
            methods: {
                validInputs() {
                    //التقدي
                    if (this.payment_method_code == 57001) {
                        this.bank_valid = true
                        this.process_number_valid = true
                        this.bank = ''
                        this.process_number = ''
                    }
                    //مدي وفيزا وماستر
                    if (this.payment_method_code == 57002 || this.payment_method_code == 57003
                        || this.payment_method_code == 57004) {
                        this.process_number_valid = false
                        this.bank_valid = true
                        this.bank = ''
                    }
                    //تحويل
                    if (this.payment_method_code == 57005) {
                        this.bank_valid = false
                        this.process_number_valid = false
                    }

                },
                validInputs2() {
                    //التقدي
                    if (this.payment_method_code2 == 57001) {
                        this.bank_valid2 = true
                        this.process_number_valid2 = true
                        this.bank2 = ''
                        this.process_number2 = ''
                    }
                    //مدي وفيزا وماستر
                    if (this.payment_method_code2 == 57002 || this.payment_method_code2 == 57003
                        || this.payment_method_code2 == 57004) {
                        this.process_number_valid2 = false
                        this.bank_valid2 = true
                        this.bank2 = ''
                    }
                    //تحويل
                    if (this.payment_method_code2 == 57005) {
                        this.bank_valid2 = false
                        this.process_number_valid2 = false
                    }

                },
                getBond() {
                    this.bond = {}
                    this.bond_message = ''
                    this.bond_disabled = true

                    if (this.bond_code_capture) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id, bond_code: this.bond_code_capture},
                            url: '{{ route("Bonds-capture.GetBond") }}'
                        }).then(response => {
                            console.log(response)
                            if (response.status == "200") {
                                this.bond = response.data
                                this.bond_disabled = false
                            }

                            if (response.status == "500") {
                                this.bond_message = 'لا يوجد سند بهذا الكود وغير مرتبط بنشاط'
                            }

                        })
                    }

                },
                getSupplierType() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.supplier_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_tax_no = response.customer_tax_no
                        this.customer_address = response.customer_address
                        this.customer_name = response.customer_name
                        this.customer_phone = response.customer_mobile
                        this.customer_vat_rate = response.customer_vat_rate
                    })
                },
            },
            computed: {
                bond_vat_amount: function () {
                    if (this.bond_amount_credit || this.bond_vat_rate) {
                        return parseFloat(this.bond_vat_rate) * parseFloat(this.bond_amount_credit)
                    } else {
                        return 0;
                    }

                },
                bond_amount_total: function () {
                    return parseFloat(this.bond_amount_credit) + this.bond_vat_amount
                },
            }
        })
    </script>
@endsection

