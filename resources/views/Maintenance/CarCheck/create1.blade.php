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

                <div class="header-action">

                </div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <form action="{{route('maintenanceCardCheck.store')}}" id="card_data_form"
                                  enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('maintenanceType.mntns_card_types')</label>
                                                <select class="form-select form-control"
                                                        name="mntns_cards_type" v-model="mntns_cards_type"
                                                        id="mntns_cards_type" required @change="getMaintenanceTypes()">
                                                    <option value="" selected> choose</option>
                                                    @foreach($mntns_cards_type as $mct)
                                                        <option value="{{$mct->system_code_id}}">
                                                            {{ $mct->system_code_name_ar
                                                            }}-{{ $mct->system_code_name_en }}
                                                            - {{ $mct->system_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('maintenanceType.mntns_location')</label>
                                                <input type="text" class="form-control" readonly value="{{app()->getLocale()=='ar' ?
                                                session('branch')['branch_name_ar'] : session('branch')['branch_name_en']}}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('maintenanceType.mntns_card_date')</label>
                                                <input type="text" class="form-control" readonly id="date">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('maintenanceType.mntns_card_status') </label>
                                                <input type="text" readonly class="form-control"
                                                       value="{{app()->getLocale() == 'ar' ? $status->system_code_name_ar : $status->system_code_name_en}}">

                                                <input type="hidden" name="mntns_cards_status"
                                                       value="{{ $status->system_code_id }}">

                                            </div>

                                        </div>

                                        <div class="row">


                                        <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"
                                                           style="text-decoration: underline;"> @lang('waybill.customer_name') </label>
                                                    <div class="form-group multiselect_div">
                                                        <div class="form-group multiselect_div">
                                                            <select class="selectpicker" data-live-search="true"
                                                                    name="customer_id" id="customer_id"
                                                                    @change="getcustomertype()"
                                                                    v-model="customer_id">
                                                                <option value="" selected>@lang('home.choose')</option>
                                                                @foreach($customers as $customer)
                                                                    <option value="{{$customer->customer_id }}">
                                                                        @if(app()->getLocale() == 'ar')
                                                                            {{ $customer->customer_name_full_ar }}
                                                                        @else
                                                                            {{ $customer->customer_name_full_en }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('invoice.customer_name') </label>
                                    <input type="text" class="form-control"
                                           name="customer_name"
                                           id="customer_name" :value="customer_name"
                                           placeholder="@lang('invoice.customer_name')" required>

                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('invoice.customer_phone') </label>
                                    <input type="text" class="form-control"
                                           name="customer_phone"
                                           id="customer_phone" :value="customer_phone"
                                           placeholder="@lang('invoice.customer_phone')" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('maintenanceType.customer_vat_rate') </label>
                                    <input type="text" class="form-control"
                                           name="customer_vat_rate"
                                           id="customer_vat_rate" :value="customer_vat_rate"
                                           placeholder="@lang('maintenanceType.customer_vat_rate')" required>
                                </div>
                                </div>
                                <div  class="row">
                                                    
                               
                                                        <div class="col-md-2">
                                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_plate_no')   </label>
                                                            <input type="text" class="form-control is-invalid" name="mntns_cars_id"
                                                                id="mntns_cars_id" placeholder="@lang('maintenanceCar.mntns_cars_plate_no')"
                                                                v-model="mntns_cars_id"
                                                                oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي-0-9\s]/g,' ');"
                                                                required>
                                                    </div>
                                                        <div class="col-md-2">
                                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_brand_id')   </label>
                                                            <select class="form-select form-control is-invalid" name="mntns_cars_brand_id" id="mntns_cars_brand_id" required>
                                                                <option value="" selected> choose</option>    
                                                                @foreach($brand as $br)
                                                                <option value="{{$br->system_code_id}}" > {{$br->getSysCodeName()}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div> 

                                                        <div class="col-md-2">
                                                            <label for="recipient-name" class="col-form-label "> @lang('maintenanceCar.mntns_cars_type') </label>
                                                            <input type="text" class="form-control is-invalid" name="mntns_cars_type"
                                                                id="mntns_cars_type" placeholder="@lang('maintenanceCar.mntns_cars_type')"
                                                                v-model="mntns_cars_type" step="3600000"
                                                                oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي\s]/g,' ');" required>
                                                                
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_color')  </label>
                                                            <input type="text" class="form-control is-invalid" name="mntns_cars_color"
                                                                    id="mntns_cars_color" placeholder="@lang('maintenanceCar.mntns_cars_color')"
                                                                    v-model="mntns_cars_color"
                                                                    oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي\s]/g,' ');"
                                                                    required>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="recipient-name" class="col-form-label"> @lang('maintenanceCar.mntns_cars_model')  </label>
                                                            <input type="number" class="form-control is-invalid" name="mntns_cars_model"
                                                                id="mntns_cars_model" placeholder="@lang('maintenanceCar.mntns_cars_model')"
                                                                v-model="mntns_cars_model"
                                                                oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                                required>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_meter')  </label>
                                                            <input type="number" class="form-control is-invalid" name="mntns_cars_meter"
                                                                    id="mntns_cars_meter" placeholder="@lang('maintenanceCar.mntns_cars_meter')"
                                                                    v-model="mntns_cars_meter"
                                                                    oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                                    required>
                                                        </div>

                                              

                                            <div hidden class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('maintenanceType.mntns_card_status')</label>
                                                <select class="form-select form-control"
                                                        name="mntns_cards_category" id="mntns_cards_category" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($mntns_cards_category as $mcc)
                                                        <option value="{{$mcc->system_code_id}}"> {{ $mcc->system_code_name_ar
                                                    }}-{{ $mcc->system_code_name_en }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div hidden class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label">   @lang('maintenanceType.mntns_work_type')</label>
                                                <select class="form-select form-control"
                                                        name="mntns_cards_item_id" id="mntns_cards_item_id"
                                                        required>
                                                    <option value="" selected> choose</option>
                                                    <option v-for="cards_work_type in cards_work_types"
                                                            :value="cards_work_type.mntns_type_id">
                                                        @{{cards_work_type.mntns_type_name_ar}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div  class="row">
                                            {{--assets--}}
                                            <div hidden class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('maintenanceType.mntns_card_loc') </label>
                                                <select class="form-select form-control"
                                                        name="mntns_cars_id" id="mntns_cars_id"
                                                        required>
                                                    <option value="" selected> choose</option>
                                                    <option v-for="mntns_car in mntns_cars"
                                                            :value="mntns_car.asset_id">
                                                        @{{mntns_car.asset_name_ar}}
                                                    </option>
                                                </select>

                                            </div>


                                            


                                            <div class="col-md-12">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('maintenanceType.mntns_notes') </label>
                                                <textarea class="form-control" name="mntns_cards_notes"
                                                          required></textarea>
                                            </div>


                                        </div>

                                        <div class="row card">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <br>
                                                        <div class="table-responsive">

                                                        <table class="table table-bordered card_table" id="internal_maintenance_table">
                                                                <tbody >
                                                                    <tr >
                                                                        <th colspan="10" style="text-align: center;background-color: #113f50;color: white;">
                                                                        @lang('maintenanceType.mntns_internal')
                                                                        </th>
                                                                    </tr>
                                                                
                                                                    <tr>
                                                                        <th colspan="10">
                                                                            <div class="col-md-12">
                                                                                <div class="mb-3">
                                                                                    <div class="row">
                                                                                        <div class="col-md-2">
                                                                                            <label for="recipient-name" class="col-form-label">   @lang('maintenanceType.mntns_type')  </label>
                                                                                            <select class="selectpicker show-tick form-control"  data-live-search="true" name="mntns_cards_item_id" id="mntns_cards_item_id"  >
                                                                                                <option value="" selected> choose</option> 
                                                                                                
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.value')  </label>
                                                                                            <input type="text" class="form-control" name="mntns_type_value" id="mntns_type_value" value="" readonly>
                                                                                        </div>
                                                                                        
                                                                                        <div class="col-md-2">
                                                                                            <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.disc_type')  </label>
                                                                                            <select class="form-select form-control" name="mntns_cards_item_disc_type" id="mntns_cards_item_disc_type" data-live-search="true">
                                                                                                <!-- <option value="" selected> choose</option>  -->
                                                                                                @foreach($mntns_cards_item_disc_type as $mctdt)
                                                                                                <option value="{{ $mctdt->system_code_id }}" data-mntnsdisctype="{{ $mctdt->getSysCodeName() }}"> {{ $mctdt->getSysCodeName() }}</option> 
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.disc')   </label>
                                                                                            <input type="number" class="form-control" name="mntns_cards_item_disc_amount" id="mntns_cards_item_disc_amount" value="0" >
                                                                                            <input type="hidden" class="form-control" name="mntns_cards_item_disc_value" id="mntns_cards_item_disc_value" value="0" >
                                                                                        </div>
                                                                                        <div class="col-md-4 row">
                                                                                            <div class="col-md-5">
                                                                                                <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.vat')  </label>
                                                                                                <input type="number" class="form-control" name="vat_value" id="vat_value" value="" readonly>
                                                                                            </div>
                                                                                            <div class="col-md-5">
                                                                                                <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.total')  </label>
                                                                                                <input type="number" class="form-control" name="total_after_vat" id="total_after_vat" value="" readonly>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_b_add')  </label>
                                                                                            
                                                                                                <button onclick="saveInternalRow()" class="btn btn-primary">
                                                                                                    <i class="fe fe-plus mr-2"></i> 
                                                                                                </button>
                                                                                                
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </th>
                                                                    </tr>
                                                                
                                                                    <tr>
                                                                        <th class="ctd table-active">No</th>
                                                                        <th class="ctd table-active" style="width:25%"> @lang('maintenanceType.mntns_type') </th>
                                                                        <th class="ctd table-active"> @lang('maintenanceType.mntns_hours')</th>
                                                                        <th class="ctd table-active"> @lang('maintenanceType.value') </th>
                                                                        <th class="ctd table-active"> @lang('maintenanceType.disc_type') </th>
                                                                        <th class="ctd table-active"> @lang('maintenanceType.disc')  </th>
                                                                        <th class="ctd table-active">@lang('maintenanceType.vat')</th>
                                                                        <th class="ctd table-active">@lang('maintenanceType.total') </th>
                                                                        <th class="ctd table-active">Action</th>
                                                                    
                                                                    </tr>
                                                                    <?php 
                                                                        $internal_discount_total = 0;
                                                                        $internal_vat_total = 0;
                                                                        $internal_total = 0;
                                                                        $internal_row_count = 0;
                                                                    ?>
                                                                    
                                                                    <input type="hidden" class="form-control" name="internal_row_count" id="internal_row_count" value="{{ $internal_row_count }}" >
                                                                    <input type="hidden" class="form-control" name="internal_total_disc_amount" id="internal_total_disc_amount" value="{{ $internal_discount_total }}" >
                                                                    <input type="hidden" class="form-control" name="internal_total_vat_amount" id="internal_total_vat_amount" value="{{$internal_vat_total}} " >
                                                                    <input type="hidden" class="form-control" name="internal_total_amount" id="internal_total_amount" value="{{ $internal_total }}" >
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="2" class="ctd table-active"> @lang('maintenanceType.disc')</td>
                                                                        <td colspan="1" class="ctd table-active"> <div id="internal_total_disc_amount_div"> {{ $internal_discount_total }} </div></td>
                                                                        <td colspan="2" class="ctd table-active">@lang('maintenanceType.vat')</td>
                                                                        <td colspan="1" class="ctd table-active"> <div id="internal_total_vat_amount_div"> {{$internal_vat_total}} </div> </td>
                                                                        <td colspan="2" class="ctd table-active">@lang('maintenanceType.total')</td>
                                                                        <td colspan="1" class="ctd table-active"> <div id="internal_total_amount_div"> {{ $internal_total }} </div> </td>
                                                                    </tr>
                                                                    <tr>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        
                                                                    </tr>
                                                                </tfoot>
                                                            </table>

                                                           

                                                        </div>
                                                    </div>
                                                </div>

                                        <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                                        <input id="files" type="file" name="image[]"
                                                               capture="user" style="visibility:hidden;"
                                                               accept="image/*">


                                                                                    <div class="row gutters-lg">
                                                                                    <div class="col-md-2 col-sm-2">
                                                                                    </div>
                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            رفرف امامي يمين</p>

                                                                                                    </div>

                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            رفرف امامي يسار</p>

                                                                                                    </div>
                                                                                        </div>

                                                                                        <div class="row gutters-lg">
                                                                                    <div class="col-md-2 col-sm-2">
                                                                                    </div>
                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            باب امامي يمين</p>

                                                                                                    </div>

                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            باب امامي يسار</p>

                                                                                                    </div>
                                                                                        </div>

                                                                                        <div class="row gutters-lg">
                                                                                    <div class="col-md-2 col-sm-2">
                                                                                    </div>
                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            باب خلفي يمين</p>

                                                                                                    </div>

                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            باب خلفي يسار</p>

                                                                                                    </div>
                                                                                        </div>

                                                                                        <div class="row gutters-lg">
                                                                                    <div class="col-md-2 col-sm-2">
                                                                                    </div>
                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            رفرف خلفي  يمين</p>

                                                                                                    </div>

                                                                                                    <div class="col-md-4 col-sm-4">
                                                                                                        <label class="colorinput">
                                                                                                            <input name="evaluation_result[]"
                                                                                                                type="checkbox"
                                                                                                                value="1"
                                                                                                                onchange="checkOne('')"
                                                                                                                class="colorinput-input sev_check"/>
                                                                                                            <span class="colorinput-color"
                                                                                                                style="background-color:#00cc00 !important;"></span>

                                                                                                        </label>

                                                                                                        <p class="font-15 bold d-inline-block">
                                                                                                            رفرف خلفي يسار</p>

                                                                                                    </div>
                                                                                        </div>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <button class="btn btn-primary"
                                                        type="submit">@lang('maintenanceType.save') </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
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

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)
        })
    </script>


    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                mntns_cards_type: '{{$selected_type_id}}',
                cards_work_types: [],
                mntns_cars: []
            },
            mounted(){
                this.getMaintenanceTypes()
            },
            methods: {
                getMaintenanceTypes() {
                    $.ajax({
                        type: 'GET',
                        data: {mntns_cards_type: this.mntns_cards_type},
                        url: '{{ route("maintenanceCardServices.getMaintenanceTypes") }}'
                    }).then(response => {
                        this.cards_work_types = response.data
                        this.mntns_cars = response.mntns_cars
                    })
                }
            }
        })
    </script>
@endsection