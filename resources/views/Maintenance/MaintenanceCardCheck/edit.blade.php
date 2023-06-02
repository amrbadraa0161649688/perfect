@extends('Layouts.master')

@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
            font-size: 16px;
            color: #000000;
        }
    </style>
@endsection

@section('content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#data-grid" data-toggle="tab"
                           class="nav-link">@lang('home.edit')</a>
                    </li>


                    <li class="nav-item"><a class="nav-link" href="#bonds-capture-grid"
                                            data-toggle="tab">@lang('home.bonds_capture')</a></li>


                    <li class="nav-item"><a class="nav-link"
                                            href="#photos-grid"
                                            style="font-size: 18px ;font-weight: bold"
                                            data-toggle="tab">{{__('Take Photo')}}</a></li>

                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">


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
                                            <form action="{{ route('maintenanceCardCheck.addBondWithJournal') }}"
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


                            <form action="{{route('maintenanceCardCheck.update',$id)}}"
                                  enctype="multipart/form-data" method="post">
                                @csrf
                                @method('put')
                                <div class="col-md-12">

                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('maintenanceType.mntns_card_types')</label>
                                            <select class="form-select form-control"
                                                    name="mntns_cards_type" v-model="mntns_cards_type"
                                                    id="mntns_cards_type" required disabled="">
                                                <option value="" selected> choose</option>
                                                @foreach($mntns_cards_main_type as $mntns_cards_main_typ)
                                                    <option value="{{$mntns_cards_main_typ->system_code_id}}">
                                                        {{ $mntns_cards_main_typ->system_code_name_ar
                                                        }}-{{ $mntns_cards_main_typ->system_code_name_en }}
                                                        - {{ $mntns_cards_main_typ->system_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('maintenanceType.mntns_location')</label>
                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" readonly
                                                       :value="m_card.branch_name_ar">
                                            @else
                                                <input type="text" class="form-control" readonly
                                                       :value="m_card.branch_name_en">
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('maintenanceType.mntns_card_date')</label>
                                            <input type="text" class="form-control" readonly
                                                   :value="m_card.created_date">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label">  @lang('maintenanceType.mntns_card_status') </label>
                                            {{--<input type="text" readonly class="form-control"--}}
                                            {{--value="{{app()->getLocale() == 'ar' ? $status->system_code_name_ar : $status->system_code_name_en}}">--}}

                                            {{--<input type="hidden" name="mntns_cards_status"--}}
                                            {{--value="{{ $status->system_code_id }}">--}}

                                            <select name="mntns_cards_status" class="form-control"
                                                    v-model="m_card.mntns_cards_status">
                                                @foreach($statuses as $status)
                                                    <option value="{{$status->system_code_id}}">
                                                        {{app()->getLocale()=='ar' ? $status->system_code_name_ar : $status->system_code_name_en}}</option>
                                                @endforeach
                                            </select>

                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label ">{{__('customer name')}}</label>
                                            <input type="text" class="form-control" readonly
                                                   :value="m_card.customer_name_full_ar">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label ">{{__('customer phone')}}</label>
                                            <input type="text" class="form-control" name="" readonly
                                                   :value="m_card.customer_mobile">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name"
                                                   class="col-form-label ">{{__('customer tax number')}}</label>
                                            <input type="text" class="form-control" name="" readonly
                                                   :value="m_card.customer_vat_no">
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label">{{__('car plate')}}</label>
                                            <select class="form-control" name="mntns_cars_id"
                                                    v-model="mntns_cars_id"
                                                    @change="getMntnsCarsDt()"
                                                    data-live-search="true">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($mnts_cars as $mnts_car)
                                                    <option value="{{$mnts_car->mntns_cars_id}}">{{$mnts_car->mntns_cars_plate_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label">{{__('car plate')}}</label>
                                            <input type="text" name="mntns_cars_plate_no"
                                                   v-model="mnts_car.mntns_cars_plate_no"
                                                   class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">{{__('car brand')}}</label>
                                            <select class="form-control" name="mntns_cars_brand_id" v-model="brand_id"
                                                    @change="getBrandDts()">
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->brand_id}}">
                                                        {{app()->getLocale() == 'ar' ? $brand->brand_name_ar : $brand->brand_name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="">{{__('car brand dt')}}</label>
                                            <select class="form-control" name="mntns_cars_type"
                                                    v-model="mnts_car.mntns_cars_type" data-live-search="true">
                                                <option v-for="brand_dt in brand_dts" :value="brand_dt.brand_dt_id">
                                                    @{{brand_dt.brand_dt_name_ar}}
                                                </option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">{{__('color')}}</label>
                                            <select class="form-control" name="mntns_cars_color"
                                                    v-model="m_card.mntns_cars_color">
                                                @foreach($colors as $color)
                                                    <option value="{{$color->system_code_id}}">{{$color->system_code_name_ar}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">{{__('car model')}}</label>
                                            <input type="text" class="form-control" name="mntns_cars_model"
                                                   :value="m_card.mntns_cars_model">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">{{__('car walk')}}</label>
                                            <input type="number" class="form-control" name="mntns_cars_meter"
                                                   :value="m_card.mntns_cars_meter">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>{{__('notes')}}</label>
                                            <textarea class="form-control" name="mntns_cards_notes">@{{ m_card.mntns_cards_notes }}</textarea>
                                        </div>

                                        <div class="col-md-4">
                                            <label>{{__('technicals')}}</label>
                                            <select class="selectpicker" name="updated_user" data-live-search="true"
                                                    v-model="m_card.updated_user">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->user_id}}">{{$user->user_name_ar}}</option>
                                                @endforeach
                                            </select>
                                        </div>

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

                                    </div>

                                </div>


                                <table class="table table-bordered card_table" id="internal_maintenance_table">
                                    <tbody>

                                    <tr>
                                        <th colspan="10"
                                            style="text-align: center;background-color: #113f50;color: white;">
                                            {{__('mntns types')}}
                                        </th>
                                    </tr>

                                    <tr>
                                        <th colspan="10">
                                            <div class="col-md-12">
                                                <div class="mb-3">

                                                    <div class="row" v-for="card_dt,index in card_dts">
                                                        <input type="hidden" name="mntns_cards_dt_id[]"
                                                               :value="card_dts[index]['mntns_cards_dt_id']">
                                                        <div class="col-md-2">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">  @lang('maintenanceType.mntns_type')  </label>
                                                            <select class="form-control"
                                                                    v-model="card_dts[index]['mntns_cards_item_id']"
                                                                    @change="getMaintenanceType(index)"
                                                                    data-live-search="true"
                                                                    name="mntns_cards_item_id[]">
                                                                <option value="" selected> choose</option>
                                                                @foreach($mntns_cards_type as $mt)
                                                                    <option value="{{$mt->mntns_type_id}}"> {{$mt->typeCat->getSysCodeName()}}
                                                                        - {{$mt->getMaintenanceTypeName()}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">  @lang('maintenanceType.value')  </label>
                                                            <input type="text" class="form-control"
                                                                   name="mntns_type_value[]"
                                                                   @change="getVatAmount(index)"
                                                                   v-model="card_dts[index]['mntns_type_value']">
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.disc_type')  </label>
                                                            <select class="form-select form-control"
                                                                    name="mntns_cards_item_disc_type[]"
                                                                    @change="getVatAmount(index)"
                                                                    v-model="card_dts[index]['mntns_cards_item_disc_type']">
                                                                <!-- <option value="" selected> choose</option>  -->
                                                                @foreach($mntns_cards_item_disc_type as $mctdt)
                                                                    <option value="{{ $mctdt->system_code }}"> {{ $mctdt->getSysCodeName() }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.disc')   </label>
                                                            <input type="number" class="form-control"
                                                                   v-model="card_dts[index]['mntns_cards_item_disc_amount']"
                                                                   @change="getVatAmount(index)"
                                                                   name="mntns_cards_item_disc_amount[]"
                                                                   id="mntns_cards_item_disc_amount">

                                                            <input type="hidden" v-model="card_dts[index]['discount']"
                                                                   name="mntns_cards_item_disc_value[]">
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> {{__('total before vat')}} </label>
                                                            <input type="number" class="form-control"
                                                                   v-model="card_dts[index]['total_before_vat']"
                                                                   readonly>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                                            <input type="number" class="form-control"
                                                                   name="vat_rate[]"
                                                                   @change="getVatAmount(index)"
                                                                   v-model="card_dts[index]['vat_rate']">
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                                            <input type="number" class="form-control"
                                                                   name="vat_value[]"
                                                                   v-model="card_dts[index]['vat_value']"
                                                                   readonly>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label for="recipient-name"
                                                                   class="col-form-label"> @lang('maintenanceType.total')  </label>
                                                            <input type="number" class="form-control"
                                                                   v-model="card_dts[index]['total_after_vat']"
                                                                   name="total_after_vat[]"
                                                                   value="" readonly>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <br>
                                                            <br>
                                                            <button type="button" @click="addRow()"
                                                                    class="btn btn-primary d-inline-block">
                                                                <i class="fe fe-plus mr-2"></i>
                                                            </button>

                                                            <button type="button" @click="subRow(index)"
                                                                    v-if="index > 0"
                                                                    class="btn btn-primary d-inline-block">
                                                                <i class="fe fe-minus mr-2"></i>
                                                            </button>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </th>
                                    </tr>

                                    </tbody>
                                    <tfoot>

                                    <tr>
                                        <td>{{__('total before vat')}}</td>
                                        <td colspan="2">
                                            <input type="text" class="form-control" name=""
                                                   :value="total_before_vat" readonly>
                                        </td>

                                        <td>{{__('total vat')}}</td>
                                        <td colspan="2">
                                            <input type="text" class="form-control" name="mntns_cards_vat_amount"
                                                   :value="total_vat" readonly>
                                        </td>
                                        <td colspan="1">{{__('total')}}</td>
                                        <td colspan="2">
                                            <input type="text" class="form-control" name="mntns_cards_total_amount"
                                                   :value="total" readonly>
                                        </td>

                                    </tr>
                                    </tfoot>
                                </table>

                                <button type="submit" class="btn btn-primary">@lang('home.save')</button>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="bonds-capture-grid" role="tabpanel">
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
                                                    <a href="{{ route('journal-entries.show',$bond->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        {{$bond->journal_hd_code}}
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

                {{--   take photos  --}}
                <div class="tab-pane fade" id="photos-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <form action="{{route('maintenanceCardCheck.storePhoto')}}" method="post"
                                      enctype="multipart/form-data" class="m-3">
                                    @csrf
                                    <h5>{{__('Take Photo')}}</h5>
                                    <input type="hidden" name="mntns_cards_id" value="{{$card->mntns_cards_id}}">
                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">
                                    <button class="btn btn-primary m-auto" type="submit">@lang('home.save')</button>
                                </form>
                            </div>

                        </div>

                        <div class="row row-cards">

                            @foreach($photos_attachments as $photo_attachment)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card p-3">
                                        <a href="javascript:void(0)" class="mb-3">
                                            <img class="rounded"
                                                 src="{{ asset('MaintenanceCardCheck/'.$photo_attachment->attachment_file_url) }}"
                                                 alt="">
                                        </a>
                                        <div class="d-flex align-items-center px-2">
                                            <img class="avatar avatar-md mr-3"
                                                 src="{{ asset('MaintenanceCardCheck/'.$photo_attachment->attachment_file_url) }}"
                                                 alt="">
                                            <div>
                                                <div>{{$photo_attachment->userCreated->user_name_ar}}</div>
                                                <small class="d-block text-muted">{{$photo_attachment->issue_date}}</small>
                                            </div>
                                            <div class="ml-auto text-muted">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {

            // $('#exampleModal').modal('show')
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                mntns_cards_id: '',
                m_card: {},
                mntns_cards_type: '',
                mntns_cars_id: '',
                brand_dts: [],
                brand_id: '',
                mnts_car: {},
                card_dts: [],
                payment_method_code: '',
                process_number_valid: true,
                bank_valid: true,
                bank: '',
                process_number: '',
            },
            mounted() {
                this.mntns_cards_id = '{{$id}}'
                this.getMaintenanceCard()
            },
            methods: {
                getMaintenanceCard() {
                    $.ajax({
                        type: 'GET',
                        data: {mntns_cards_id: this.mntns_cards_id},
                        url: ''
                    }).then(response => {
                        this.m_card = response.data
                        this.mntns_cards_type = this.m_card.mntns_cards_type
                        this.mntns_cars_id = this.m_card.mntns_cars_id
                        this.getMntnsCarsDt()
                        this.brand_id = this.m_card.mntns_cars_brand_id
                        this.getBrandDts()
                        this.card_dts = this.m_card.card_dts
                    })
                },
                getMntnsCarsDt() {
                    if (this.mntns_cars_id) {
                        $.ajax({
                            type: 'GET',
                            data: {mntns_cars_id: this.mntns_cars_id},
                            url: '{{ route("maintenanceCardCheck.getMntnsCarDt") }}'
                        }).then(response => {
                            this.mnts_car = response.data
                        })
                    }
                },
                getBrandDts() {
                    this.brand_dts = []
                    $.ajax({
                        type: 'GET',
                        data: {brand_id: this.brand_id},
                        url: '{{ route("maintenanceCardCheck.getBrandDts") }}'
                    }).then(response => {
                        this.brand_dts = response.data
                    })
                },
                getMaintenanceType(index) {
                    this.card_dts[index]['mntns_type_value'] = 0
                    $.ajax({
                        type: 'GET',
                        data: {mntns_cards_item_id: this.card_dts[index]['mntns_cards_item_id']},
                        url: '{{ route("maintenanceCardCheck.getMaintenanceType") }}'
                    }).then(response => {
                        this.card_dts[index]['mntns_type_value'] = response.data.mntns_type_value
                    })
                },
                getVatAmount(index) {
                    this.card_dts[index]['total_after_vat'] = 0
                    this.card_dts[index]['total_before_vat'] = 0
                    this.card_dts[index]['vat_value'] = 0

                    if (this.card_dts[index]['mntns_cards_item_disc_type'] == 534) {
                        //////////قيمه
                        this.card_dts[index]['discount'] = this.card_dts[index]['mntns_cards_item_disc_amount']
                        var vat = (parseFloat(this.card_dts[index]['mntns_type_value']) - parseFloat(this.card_dts[index]['mntns_cards_item_disc_amount'])) * (this.card_dts[index]['vat_rate'] / 100)
                        this.card_dts[index]['vat_value'] = vat
                        this.card_dts[index]['total_before_vat'] = parseFloat(this.card_dts[index]['mntns_type_value']) - parseFloat(this.card_dts[index]['mntns_cards_item_disc_amount']);
                        this.card_dts[index]['total_after_vat'] = vat + parseFloat(this.card_dts[index]['mntns_type_value']) - parseFloat(this.card_dts[index]['mntns_cards_item_disc_amount']);
                    }
                    if (this.card_dts[index]['mntns_cards_item_disc_type'] == 533) {
                        //////////نسبه

                        this.card_dts[index]['discount'] = (parseFloat(this.card_dts[index]['mntns_cards_item_disc_amount']) / 100) * parseFloat(this.card_dts[index]['mntns_type_value'])
                        console.log(this.card_dts[index]['discount'])
                        var vat = (parseFloat(this.card_dts[index]['mntns_type_value']) - this.card_dts[index]['discount']) * (this.card_dts[index]['vat_rate'] / 100)
                        this.card_dts[index]['vat_value'] = vat
                        this.card_dts[index]['total_before_vat'] = parseFloat(this.card_dts[index]['mntns_type_value']) - this.card_dts[index]['discount']
                        this.card_dts[index]['total_after_vat'] = vat + parseFloat(this.card_dts[index]['mntns_type_value']) - this.card_dts[index]['discount'];
                    }
                },
                addRow() {
                    this.card_dts.push({
                        'mntns_cards_dt_id': 0,
                        'mntns_cards_item_id': '',
                        'mntns_type_value': 0,
                        'mntns_cards_item_disc_type': "",
                        'mntns_cards_item_disc_amount': 0, ////////////نسبه او قيمه الخصم
                        'discount': 0, /////////القيمه///////
                        'vat_rate': 15, /////////////النسبه
                        'vat_value': 0, ///////////القيمه
                        'total_after_vat': 0,
                        'total_before_vat': 0,
                    })
                },
                subRow(index) {
                    console.log(index)
                    if (this.card_dts[index]['mntns_cards_dt_id'] == 0) {
                        this.card_dts.splice(index, 1)
                    } else {
                        $.ajax({
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                mntns_cards_dt_id: this.card_dts[index]['mntns_cards_dt_id']
                            },
                            url: '{{ route('maintenanceCardCheck.delete') }}'
                        }).then(response => {
                            this.card_dts.splice(index, 1);
                        })
                    }
                },
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
            },
            computed: {
                total_vat: function () {
                    let total = 0;
                    Object.entries(this.card_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.vat_value))
                    });
                    return total.toFixed(2);
                },
                total: function () {
                    let total = 0;
                    Object.entries(this.card_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.total_after_vat))
                    });
                    return total.toFixed(2);
                },
                total_before_vat: function () {
                    let total = 0;
                    Object.entries(this.card_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.total_before_vat))
                    });
                    return total.toFixed(2);
                },
            }
        })
    </script>
@endsection