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
                            <form action="{{route('maintenanceCardCheck.store')}}"
                                  enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label "> @lang('maintenanceType.mntns_card_types')</label>
                                            <select class="form-select form-control"
                                                    name="mntns_cards_type" v-model="mntns_cards_type"
                                                    id="mntns_cards_type" required @change="getMaintenanceTypes()">
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
                                                   class="col-form-label">{{__('customers')}}</label>
                                            <select class="selectpicker" name="customer_id" v-model="customer_id"
                                                    @change="getCustomerData()" required data-live-search="true">
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->customer_id}}">
                                                        {{$customer->customer_name_full_ar}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label ">{{__('customer name')}}</label>
                                            <input type="text" class="form-control" name=""
                                                   :value="customer.customer_name_full_ar">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label ">{{__('customer phone')}}</label>
                                            <input type="text" class="form-control" name=""
                                                   :value="customer.customer_mobile">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label ">{{__('customer tax number')}}</label>
                                            <input type="text" class="form-control" name=""
                                                   :value="customer.customer_vat_no">
                                        </div>


                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label">{{__('car plate')}}</label>
                                            <select class="selectpicker" name="mntns_cars_id" v-model="mntns_cars_id"
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
                                            <input type="text" name="mntns_cars_plate_no" v-model="mntns_cars_plate_no"
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
                                            <select class="selectpicker" name="mntns_cars_type"
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
                                            <select class="selectpicker" name="mntns_cars_color"
                                                    data-live-search="true">
                                                @foreach($colors as $color)
                                                    <option value="{{$color->system_code_id}}">{{$color->system_code_name_ar}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">{{__('car model')}}</label>
                                            <input type="text" class="form-control" name="mntns_cars_model"
                                                   :value="mnts_car.mntns_cars_model">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">{{__('car walk')}}</label>
                                            <input type="number" class="form-control" name="mntns_cars_meter"
                                                   :value="mnts_car.mntns_cars_meter">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>{{__('notes')}}</label>
                                            <textarea class="form-control" name="mntns_cards_notes"></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>{{__('technicals')}}</label>
                                            <select class="form-control" name="updated_user">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->user_id}}">{{$user->user_name_ar}}</option>
                                                @endforeach
                                            </select>
                                        </div>
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

                                                            <button type="button" @click="subRow()" v-if="index > 0"
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
                customer_id: '',
                customer: {},
                cards_work_types: [],
                mntns_cars: [],
                brand_id: '',
                brand_dts: [],
                mntns_cars_id: '',
                mnts_car: {},
                mntns_cars_plate_no: '',
                card_dts: [{
                    'mntns_cards_item_id': '',
                    'mntns_type_value': 0,
                    'mntns_cards_item_disc_type': '',
                    'mntns_cards_item_disc_amount': 0,
                    'mntns_cards_disc_value': 0,
                    'vat_rate': 15,
                    'vat_value': 0,
                    'total_before_vat': 0,
                    'total_after_vat': 0,
                    'discount': 0
                }],
                cars_type: false

            },
            mounted() {
                this.getMaintenanceTypes()
            },
            methods: {
                getCustomerData() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{route('maintenanceCardCheck.getCustomerData')}}'
                    }).then(response => {
                        this.customer = response.data
                    })
                },
                getMaintenanceTypes() {
                    $.ajax({
                        type: 'GET',
                        data: {mntns_cards_type: this.mntns_cards_type},
                        url: '{{ route("maintenanceCard.getMaintenanceTypes") }}'
                    }).then(response => {
                        this.cards_work_types = response.data
                        this.mntns_cars = response.mntns_cars
                    })
                },
                getBrandDts() {
                    this.brand_dts = []
                    console.log('brand dt')
                    $.ajax({
                        type: 'GET',
                        data: {brand_id: this.brand_id},
                        url: '{{ route("maintenanceCardCheck.getBrandDts") }}'
                    }).then(response => {
                        this.brand_dts = response.data
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
                            this.brand_id = this.mnts_car.mntns_cars_brand_id
                            this.getBrandDts()
                        })
                    }

                },
                addRow() {
                    this.card_dts.push({
                        'mntns_cards_item_id': '',
                        'mntns_type_value': 0,
                        'mntns_cards_item_disc_type': '',
                        'mntns_cards_item_disc_amount': 0,
                        'mntns_cards_disc_value': 0,
                        'vat_rate': 15,
                        'vat_value': 0,
                        'total_before_vat': 0,
                        'total_after_vat': 0,
                        'discount': 0
                    })
                },
                subRow(index) {
                    this.card_dts.splice(index, 1)
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
                }
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