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
                        <form class="card" action="{{route('CarRentPriceList.store')}}" method="post">
                            @csrf
                            <div style="font-size: 16px ;font-weight: bold " class="card-body">
                                <h3 class="card-title">@lang('home.add_price_list')</h3>
                                {{--PRICE LIST HD--}}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.companies')</label>
                                            <select class="form-control selectpicker" name="company_id" required
                                                    data-live-search="true"
                                                    @change="getBranches() ; getCarModels()" v-model="company_id">

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

                                    <div class="col-sm-6 col-md-9 mt-4"
                                         style="font-size: 16px ;font-weight: bold ;color:blue ">

                                        {{--<label class="form-label">@lang('home.branches')</label>--}}
                                        {{--<select class="form-control" name="price_branches[]"--}}
                                        {{--size="2" multiple required>--}}
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
                                            v-model="price_branches"
                                            :items="branches"
                                            item-value="branch_id"
                                            item-text="branch_name_ar"
                                            label="@lang('home.branches')"
                                        ></v-autocomplete>

                                        <input type="hidden" name="price_branches" v-model="price_branches" required>

                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.gender')</label>
                                            <select class="form-select form-control selectpicker"
                                                    name="customer_type_code"
                                                    data-live-search="true"
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
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customers')</label>
                                            <select class="form-control selectpicker" data-live-search="true"
                                                    name="customer_id"
                                                    data-live-search="true"
                                                    required
                                                    v-if="show_customers">

                                                <option value="">@lang('home.choose')</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->customer_id}}">{{ app()->getLocale() == 'ar'
                                                ? $customer->customer_name_full_ar
                                                : $customer->customer_name_full_en}}</option>
                                                @endforeach

                                            </select>

                                            <select class="form-control selectpicker" name="customer_id" v-else>
                                                <option value="">@lang('home.choose')</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customer_category')</label>
                                            <select class="form-control selectpicker" name="price_customer_category"
                                                    required data-live-search="true">

                                                <option value="">@lang('home.choose')</option>
                                                @foreach($sys_code_classifications as $sys_code_classification)
                                                    <option value="{{ $sys_code_classification->system_code_id }}">
                                                        {{ app()->getLocale() == 'ar'
                                                        ?  $sys_code_classification->system_code_name_ar
                                                        : $sys_code_classification->system_code_name_en }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.from')</label>
                                            <input type="date" class="form-control" name="rent_list_start_date"
                                                   style="font-size: 16px ;font-weight: bold "
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.to')</label>
                                            <input type="date" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold "
                                                   name="rent_list_end_date" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.status')</label>
                                            <select class="form-control selectpicker" name="rent_list_status"
                                                    style="font-size: 16px ;font-weight: bold ;color:red "
                                                    data-live-search="true" required>

                                                <option>@lang('home.choose')</option>
                                                <option value="1">@lang('home.active')</option>
                                                <option value="0">@lang('home.not_active')</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.notes')</label>
                                            <textarea class="form-control" name="rent_list_notes"></textarea>
                                        </div>
                                    </div>


                                </div>

                                {{--PRICE LIST DT--}}
                                <div class="row">


                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-title">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                        data-target="#exampleModal"
                                                        data-whatever="@mdo">@lang('home.car_models')</button>
                                            </div>


                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                        <tr>
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
                                                            <th>#</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr v-for="priceList,index in priceLists">
                                                            <td>
                                                                <select class="form-control selectpicker"
                                                                        name="car_model_id[]"
                                                                        style="display: block !important"
                                                                        data-live-search="true"
                                                                        required
                                                                        v-model="priceLists[index]['car_model_id']">
                                                                    <option value="">@lang('home.choose')</option>
                                                                    <option :value="car_model.car_rent_model_id"
                                                                            v-for="car_model in car_models">
                                                                        @{{ car_model.car_rent_model_code }} -
                                                                        @{{ car_model.car_brand_name }} -
                                                                        @{{ car_model.car_brand_dt_name }} -
                                                                        @{{ car_model.car_model_year }}
                                                                    </option>
                                                                </select>
                                                            </td>

                                                            <td>

                                                                <select name="rent_type_id[]"
                                                                        class="form-control selectpicker"
                                                                        style="display: block !important"
                                                                        data-live-search="true"
                                                                        required>
                                                                    <option value="">@lang('home.choose') </option>
                                                                    @foreach($contract_types as $contract_type)
                                                                        <option
                                                                            value="{{$contract_type->system_code_id}}">
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
                                                            <td>
                                                                <button type="button" @click="addRow()"
                                                                        class="btn btn-circle btn-icon-only red-flamingo">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button" @click="removeRow(index)"
                                                                        v-if="index>0"
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

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-title">
                                                @lang('home.car_rent_price_add')
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0" id="car_rent_price_add">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 200px;">@lang('home.addition_types')</th>
                                                            <th style="width: 200px;">@lang('home.rent_add_price')</th>
                                                            <th>#</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <select class="form-control selectpicker"
                                                                        name="rent_add_id[]"
                                                                        style="display: block !important"
                                                                        data-live-search="true"
                                                                        required>
                                                                    <option value="">@lang('home.choose')</option>
                                                                    @foreach($system_code_types as $system_code)
                                                                        <option
                                                                            value="{{$system_code->system_code_id}}">
                                                                            {{ $system_code->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control"
                                                                       name="rent_add_price[]"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <button type="button" @click="addRowAdd()"
                                                                        class="btn btn-circle btn-icon-only red-flamingo">
                                                                    <i class="fa fa-plus"></i>
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
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                            </div>
                        </form>


                        <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">@lang('home.data')
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                            data-target="#exampleModal"
                                                            data-whatever="@mdo">@lang('home.add_cars')</button>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select_all"
                                                                       @click="addSelectAll()" v-model="select_all">
                                                            </th>
                                                            <th style="font-size: 16px ;font-weight: bold ">@lang('home.car_models')</th>
                                                            <th>@lang('home.car_type')</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>@lang('carrent.car_purchase_date')</th>
                                                            <th>@lang('carrent.car_qty')</th>
                                                           
                                                           
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="car_model,index in car_models">
                                                            <td>
                                                                <input type="checkbox"
                                                                       v-model="car_model_id"
                                                                       @click="addRowFomModal(car_model.car_rent_model_id,
                                                                       $event)"
                                                                       :value="car_model.car_rent_model_id">
                                                            </td>

                                                            <td>@{{ car_model.car_rent_model_code }}</td>
                                                            <td>
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{ car_model.car_brand_ar }}
                                                                @else
                                                                    @{{car_model.car_brand_en }}
                                                                @endif
                                                            </td>
                                                            <td>@{{ car_model.car_brand_dt_name  }}</td>
                                                            <td>@{{ car_model.car_model_year }}</td>
                                                            <td>@{{ car_model.car_purchase_date }}</td>
                                                            <td>@{{ car_model.car_qty }}</td>
                                                            
                                                            
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </v-app>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            $('#select_all').click(function () {
                if ($('#select_all').prop('checked') == true) {
                    $('input:checkbox').prop('checked', true);
                } else {
                    $('input:checkbox').prop('checked', false);
                }
            });
        });
    </script>



    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                price_branches: '',
                branches: [],
                company_id: '',
                car_models: [],
                priceLists: [
                    // {
                    //     'car_model_id': '',
                    //     'discount_value': '',
                    //     'rent_price': '',
                    //     'extra_kilometer': '',
                    //     'extra_kilometer_price': '',
                    //     'extra_hour': '',
                    //     'extra_hour_price': '',
                    //     'hours_to_day': '',
                    //     'extra_driver': '',
                    // }
                ],
                show_customers: false,
                customer_type_code: '',
                car_model_id: [],
                select_all: false
            },
            methods: {
                addRowFomModal(id, event) {

                    if (event.target.checked) {
                        this.priceLists.push({
                            'car_model_id': id,
                            'discount_value': '',
                            'rent_price': '',
                            'extra_kilometer': '',
                            'extra_kilometer_price': '',
                            'extra_hour': '',
                            'extra_hour_price': '',
                            'hours_to_day': '',
                        })
                    } else {
                        this.priceLists.splice(this.priceLists.indexOf(id), 1)
                    }

                },
                addSelectAll() {
                    if (!this.select_all) {
                        var count = this.car_models.length
                        for (id = 0; id < count; id++) {
                            this.priceLists.push({
                                'car_model_id': this.car_models[id]['car_rent_model_id'],
                                'discount_value': '',
                                'rent_price': '',
                                'extra_kilometer': '',
                                'extra_kilometer_price': '',
                                'extra_hour': '',
                                'extra_hour_price': '',
                                'hours_to_day': '',
                            })
                        }
                    } else {
                        this.priceLists = []
                    }

                },
                addRow() {
                    this.priceLists.push({
                        'car_model_id': '',
                        'discount_value': '',
                        'rent_price': '',
                        'extra_kilometer': '',
                        'extra_kilometer_price': '',
                        'extra_hour': '',
                        'extra_hour_price': '',
                        'hours_to_day': '',
                    })
                },
                removeRow(index) {
                    this.priceLists.splice(index, 1)
                },
                addRowAdd() {
                    $('#car_rent_price_add tbody').append(`
                    <tr>
                                                            <td>
                                                                <select class="form-control selectpicker"
                                                                        name="rent_add_id[]"
                                                                        style="display: block !important"
                                                                        data-live-search="true"
                                                                        required>
                                                                    <option value="">@lang('home.choose')</option>
                                                                    @foreach($system_code_types as $system_code)
                    <option
                        value="{{$system_code->system_code_id}}">
                                                                            {{ $system_code->name }}
                    </option>
@endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control"
                           name="rent_add_price[]"
                           required>
                </td>
                <td>
                    <button type="button" @click="removeRowAdd()"
                            class="btn btn-circle btn-icon-only yellow-gold">
                        <i class="fa fa-minus"></i>
                    </button>
                </td>
            </tr>
`);
                },
                removeRowAdd() {
                    console.log(this)
                    $(this).parent().parent().remove();
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
                getCarModels() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("CarRentPriceList.getCarModels") }}'
                    }).then(response => {
                        this.car_models = response.data
                    })
                },
                validateCustomers() {
                    if (this.customer_type_code == 538) {
                        this.show_customers = false
                    } else if (this.customer_type_code == 539) {
                        this.show_customers = true
                    }
                }
            }
        })
    </script>
@endsection
