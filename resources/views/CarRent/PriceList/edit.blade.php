@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <style lang="">

        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .bootstrap-select {
            width: 100% !important;
        }

        .v-application {
            font-family: "Cairo" !important;
        }
    </style>

@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <v-app>
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12">
                        <form style="font-size: 16px ;font-weight: bold " class="card" action="{{ route('CarRentPriceList.update',$price_list_hd->rent_list_id) }}"
                              method="post">
                            @csrf
                            @method('put')

                            <div style="font-size: 16px ;font-weight: bold  " class="card-body">
                                <h3 class="card-title">@lang('home.add_price_list')</h3>
                                {{--PRICE LIST HD--}}

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.companies')</label>
                                            <select class="form-control" name="company_id" required
                                                    @change="getBranches();getCarModels()" v-model="company_id">

                                                <option value="">@lang('home.choose')</option>
                                                @foreach($companies as $company)
                                                    <option value="{{$company->company_id}}">
                                                        {{ app()->getLocale()=='ar' ? $company->company_name_ar :
                                                        $company->company_name_en }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div  style="font-size: 16px ;font-weight: bold ;color:blue " class="col-sm-6 col-md-9">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.branches')</label>
                                            {{--<select class="form-control" name="price_branches[]"--}}
                                            {{--size="2" multiple required v-model="branches_id">--}}
                                            {{--<option value="">@lang('home.choose')</option>--}}
                                            {{--<option v-for="branch in branches" :value="branch.branch_id">--}}
                                            {{--@if(app()->getLocale()=='ar')--}}
                                            {{--@{{ branch.branch_name_ar }}--}}
                                            {{--@else--}}
                                            {{--@{{  branch.branch_name_en }}--}}
                                            {{--@endif--}}
                                            {{--</option>--}}

                                            {{--</select>--}}
                                            <v-autocomplete
                                                    multiple
                                                    @change="showBranches()"
                                                    v-model="selected_branches"
                                                    :items="branches"
                                                    item-value="branch_id"
                                                    item-text="branch_name_ar"
                                                    label="@lang('home.branches')"
                                            ></v-autocomplete>

                                            <input type="hidden" name="price_branches" v-model="selected_branches"
                                                   required v-if="show_branches">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.gender')</label>
                                            <select class="form-select form-control" name="customer_type_code"
                                                    v-model="customer_type_code"
                                                    id="customer_type" required @change="validateCustomers()">
                                                <option value="" selected></option>
                                                @foreach($sys_codes_type as $sys_code_type)
                                                    <option value="{{$sys_code_type->system_code}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_type->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_type->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customers')</label>
                                            <select class="form-control" name="customer_id" required
                                                    v-if="show_customers" v-model="customer_id">

                                                <option value="">@lang('home.choose')</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->customer_id}}">{{ app()->getLocale() == 'ar'
                                                ? $customer->customer_name_full_ar
                                                : $customer->customer_name_full_en}}</option>
                                                @endforeach

                                            </select>

                                            <select class="form-control" name="customer_id" v-else>
                                                <option value="">@lang('home.choose')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customer_category')</label>
                                            <select class="form-control" name="price_customer_category" required>

                                                <option value="">@lang('home.choose')</option>
                                                @foreach($sys_code_classifications as $sys_code_classification)
                                                    <option value="{{ $sys_code_classification->system_code_id }}"
                                                    @if($price_list_hd->price_customer_category == $sys_code_classification->system_code_id )
                                                        selected
                                                    @endif>
                                                        {{ app()->getLocale() == 'ar'
                                                        ?  $sys_code_classification->system_code_name_ar
                                                        : $sys_code_classification->system_code_name_en }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.from')</label>
                                            <input type="date" class="form-control" name="rent_list_start_date" required
                                                   value="{{ $price_list_hd->rent_list_start_date }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.to')</label>
                                            <input type="date" class="form-control"
                                                   name="rent_list_end_date" required
                                                   value="{{ $price_list_hd->rent_list_end_date }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-1">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.status')</label>
                                            <select class="form-control" name="rent_list_status" style="font-size: 16px ;font-weight: bold ;color:red "  required>

                                                <option>@lang('home.choose')</option>
                                                <option value="1" @if($price_list_hd->rent_list_status == 1)
                                                selected @endif>@lang('home.active')</option>
                                                <option value="0" @if($price_list_hd->rent_list_status == 0)
                                                selected @endif>@lang('home.not_active')</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.notes')</label>
                                            <textarea class="form-control" name="rent_list_notes"
                                                      >{{ $price_list_hd->rent_list_notes }}</textarea>
                                        </div>
                                    </div>


                                </div>

                                {{--PRICE LIST DT--}}
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div style="font-size: 16px ;font-weight: bold " class="card-body">
                                                <div class="table-responsive">
                                                    <table  class="table table-bordered mb-1">
                                                        <thead >
                                                        <tr >
                                                            <th style="width: 200px;">@lang('home.car_models')</th>
                                                            <th style="width: 200px;">@lang('home.rent_type')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.discount_value')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.rent_price')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.extra_kilometer')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.extra_kilometer_price')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.extra_hour')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.extra_hour_price')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.hours_to_day')</th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.extra_driver')</th>
                                                            <th></th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        {{--old data--}}
                                                        <tr v-for="priceList,index in priceLists">
                                                            <input type="hidden" name="rent_list_dt_id[]"
                                                                   :value="priceLists[index]['rent_list_dt_id']">
                                                            <td>
                                                                <select class="form-control" name="car_model_id[]"
                                                                        v-model="priceLists[index]['car_model_id']">
                                                                    <option value="">@lang('home.choose')</option>
                                                                    <option :value="car_model.car_rent_model_id"
                                                                            v-for="car_model in car_models">
                                                                        @if(app()->getLocale() == 'ar')
                                                                            @{{ car_model.car_brand_ar }}
                                                                            + @{{ car_model.car_rent_model_code }}
                                                                        @else
                                                                            @{{car_model.car_brand_en }}
                                                                            +  @{{ car_model.car_rent_model_code }}
                                                                        @endif
                                                                    </option>
                                                                </select>
                                                            </td>

                                                            <td>

                                                                <select name="rent_type_id[]" class="form-control"
                                                                        required
                                                                        v-model="priceLists[index]['rent_type_id']">
                                                                    <option value="">@lang('home.choose') </option>
                                                                    @foreach($contract_types as $contract_type)
                                                                        <option value="{{$contract_type->system_code_id}}">
                                                                            {{app()->getLocale() == "ar"
                                                                                ? $contract_type->system_code_name_ar
                                                                                : $contract_type->system_code_name_en
                                                                            }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="discount_value[]"
                                                                       v-model="priceLists[index]['discount_value']"
                                                                       >
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="rent_price[]"
                                                                       v-model="priceLists[index]['rent_price']"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="extra_kilometer[]"
                                                                       v-model="priceLists[index]['extra_kilometer']"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="extra_kilometer_price[]"
                                                                       v-model="priceLists[index]['extra_kilometer_price']"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="extra_hour[]"
                                                                       v-model="priceLists[index]['extra_hour']"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="extra_hour_price[]"
                                                                       v-model="priceLists[index]['extra_hour_price']"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="hours_to_day[]"
                                                                       v-model="priceLists[index]['hours_to_day']"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="extra_driver[]"
                                                                       v-model="priceLists[index]['extra_driver']"
                                                                       >
                                                            </td>
                                                            <td colspan="3">
                                                                <div class="row">
                                                                    <div class="col-md-2
                                                                            mr-2 ml-2">
                                                                        <button type="button" @click="deleteRow(index)"
                                                                                class="btn btn-circle btn-icon-only yellow-gold">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>


                                                                    <div class="col-md-2
                                                                            mr-2 ml-2">
                                                                        <button type="button" @click="addRow()"
                                                                                v-if="index == priceLists.length-1"
                                                                                class="btn btn-circle btn-icon-only red-flamingo">
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </div>

                                                                </div>

                                                                <div class="row">

                                                                    <button type="button" @click="removeAllNewRow()"
                                                                            v-if="index == priceLists.length-1"
                                                                            class="btn btn-info btn-sm">
                                                                        @lang('home.delete_all_new')
                                                                    </button>
                                                                </div>

                                                            </td>

                                                        </tr>


                                                        {{--new data--}}
                                                        <tr v-for="priceList,index in priceLists_new">
                                                            <td>
                                                                <select class="form-control" name="new_car_model_id[]"
                                                                        v-model="priceLists_new[index]['new_car_model_id']"

                                                                        @change="validateRaw(index)">
                                                                    <option value="">@lang('home.choose')</option>
                                                                    <option :value="car_model.car_rent_model_id"
                                                                            v-for="car_model in car_models">
                                                                        @if(app()->getLocale() == 'ar')
                                                                            @{{ car_model.car_brand_ar }}
                                                                            + @{{ car_model.car_rent_model_code }}
                                                                        @else
                                                                            @{{car_model.car_brand_en }}
                                                                            +  @{{ car_model.car_rent_model_code }}
                                                                        @endif
                                                                    </option>
                                                                </select>
                                                            </td>

                                                            <td>

                                                                <select name="new_rent_type_id[]" class="form-control"
                                                                        :required="priceLists_new[index]['rent_type_id_required']">
                                                                    <option value="">@lang('home.choose') </option>
                                                                    @foreach($contract_types as $contract_type)
                                                                        <option value="{{$contract_type->system_code_id}}">
                                                                            {{app()->getLocale() == "ar"
                                                                                ? $contract_type->system_code_name_ar
                                                                                : $contract_type->system_code_name_en
                                                                            }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_discount_value[]"
                                                                       :required="priceLists_new[index]['discount_value_required']"
                                                                       v-model="priceLists_new[index]['discount_value']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_rent_price[]"
                                                                       :required="priceLists_new[index]['rent_price_required']"
                                                                       v-model="priceLists_new[index]['rent_price']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_extra_kilometer[]"
                                                                       :required="priceLists_new[index]['extra_kilometer_required']"
                                                                       v-model="priceLists_new[index]['extra_kilometer']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_extra_kilometer_price[]"
                                                                       :required="priceLists_new[index]['extra_kilometer_price_required']"
                                                                       v-model="priceLists_new[index]['extra_kilometer_price']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_extra_hour[]"
                                                                       :required="priceLists_new[index]['extra_hour_required']"
                                                                       v-model="priceLists_new[index]['extra_hour']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_extra_hour_price[]"
                                                                       :required="priceLists_new[index]['extra_hour_price_required']"
                                                                       v-model="priceLists_new[index]['extra_hour_price']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_hours_to_day[]"
                                                                       :required="priceLists_new[index]['hours_to_day_required']"
                                                                       v-model="priceLists_new[index]['hours_to_day']">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="new_extra_driver[]"
                                                                       :required="priceLists_new[index]['extra_driver_required']"
                                                                       v-model="priceLists_new[index]['extra_driver']">
                                                            </td>
                                                            <td>
                                                                <button type="button" @click="addRow()"
                                                                        class="btn btn-circle btn-icon-only red-flamingo">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button" @click="removeRow(index)"
                                                                        class="btn btn-circle btn-icon-only yellow-gold">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </td>

                                                        </tr>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <button class="btn btn-primary" type="submit">@lang('home.save')</button>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </v-app>
    </div>

@endsection

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                id:{!! $id !!},
                price_list_hd: {},
                selected_branches: [],
                company_id: '',
                branches: [],
                branches_id: [],
                customer_id: '',
                customer_type_code: '',
                show_customers: false,
                car_models: {},
                priceLists: [],
                show_branches: false,
                // priceLists_new: [{
                //     'new_car_model_id': '',
                //     'discount_value': '',
                //     'rent_price': '',
                //     'extra_kilometer': '',
                //     'extra_kilometer_price': '',
                //     'extra_hour': '',
                //     'extra_hour_price': '',
                //     'hours_to_day': '',
                //     'extra_driver': '',
                //     'rent_type_id_required': false,
                //     'discount_value_required': false,
                //     'rent_price_required': false,
                //     'extra_kilometer_price_required': false,
                //
                //     'extra_kilometer_required': false,
                //     'extra_hour_required': false,
                //     'extra_hour_price_required': false,
                //     'hours_to_day_required': false,
                //     'extra_driver_required': false,
                // }],
                priceLists_new: []

            },
            mounted() {
                this.getPriceList()

            },
            methods: {
                showBranches() {
                    this.show_branches = true
                },
                validateRaw(index) {
                    console.log(index)
                    if (this.priceLists_new[index]['new_car_model_id']) {
                        this.priceLists_new[index]['rent_type_id_required'] = true
                        this.priceLists_new[index]['discount_value_required'] = true
                        this.priceLists_new[index]['rent_price_required'] = true
                        this.priceLists_new[index]['extra_kilometer_price_required'] = true
                        this.priceLists_new[index]['extra_kilometer_required'] = true
                        this.priceLists_new[index]['extra_hour_required'] = true
                        this.priceLists_new[index]['extra_hour_price_required'] = true
                        this.priceLists_new[index]['hours_to_day_required'] = true
                        this.priceLists_new[index]['extra_driver_required'] = true
                    } else {
                        this.priceLists_new[index]['rent_type_id_required'] = false
                        this.priceLists_new[index]['discount_value_required'] = false
                        this.priceLists_new[index]['rent_price_required'] = false
                        this.priceLists_new[index]['extra_kilometer_price_required'] = false
                        this.priceLists_new[index]['extra_kilometer_required'] = false
                        this.priceLists_new[index]['extra_hour_required'] = false
                        this.priceLists_new[index]['extra_hour_price_required'] = false
                        this.priceLists_new[index]['hours_to_day_required'] = false
                        this.priceLists_new[index]['extra_driver_required'] = false
                    }
                },
                addRow() {
                    this.priceLists_new.push({
                        'new_car_model_id': '',
                        'discount_value': '',
                        'rent_price': '',
                        'extra_kilometer': '',
                        'extra_kilometer_price': '',
                        'extra_hour': '',
                        'extra_hour_price': '',
                        'hours_to_day': '',
                        'rent_type_id_required': false,
                        'discount_value_required': false,
                        'rent_price_required': false,
                        'extra_kilometer_price_required': false,
                        'extra_kilometer_required': false,
                        'extra_hour_required': false,
                        'extra_hour_price_required': false,
                        'hours_to_day_required': false,
                        'extra_driver_required': false,
                    })
                },
                removeRow(index) {
                    this.priceLists_new.splice(index, 1)
                },
                removeAllNewRow() {
                    this.priceLists_new = []
                },
                getPriceList() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.id},
                        url: ''
                    }).then(response => {
                        this.price_list_hd = response.data
                        this.company_id = this.price_list_hd.company_id

                        if (this.company_id) {
                            this.getBranches();
                            this.getCarModels();
                        }
                        this.branches_id = JSON.parse(this.price_list_hd.price_branches)
                        this.selected_branches = response.selected_branches
                        this.customer_type_code = response.customer_type_code.system_code

                        this.priceLists = response.price_list_dts

                        if (response.customer_type_code.system_code == 538) { //افراد
                            this.show_customers = false
                        }

                        if (response.customer_type_code.system_code == 539) { // شركات
                            this.show_customers = true
                            this.customer_id = this.price_list_hd.customer_id
                        }

                    })
                },
                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.branches") }}'
                    }).then(response => {
                        this.branches = response.data
                    })
                },
                validateCustomers() {
                    if (this.customer_type_code == 538) {
                        this.show_customers = false
                    } else if (this.customer_type_code == 539) {
                        this.show_customers = true
                    }
                },
                getCarModels() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id, rent_list_id: this.price_list_hd.rent_list_id},
                        url: '{{ route("CarRentPriceList.getCarModels") }}'
                    }).then(response => {
                        this.car_models = response.data
                    })
                },
                deleteRow(index) {

                    $.ajax({
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            rent_list_dt_id: this.priceLists[index]['rent_list_dt_id']
                        },
                        url: '{{ route("CarRentPriceList.deletePriceListDt") }}'
                    }).then(response => {
                        console.log(response)
                    })


                    this.priceLists.splice(index, 1)
                }
            }
        })

    </script>

@endsection