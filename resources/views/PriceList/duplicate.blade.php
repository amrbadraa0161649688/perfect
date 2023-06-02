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
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="font-25" bold>
                                        @lang('customer.update_price_list')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('PriceList.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">
                                                {{--الشركه الفرعيه--}}
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <select class="form-control" name="company_id"
                                                            required>

                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->company_id }}"
                                                                    @if($price_list->company_id == $company->company_id) selected @endif>
                                                                @if(app()->getLocale()=='ar')
                                                                    {{ $company->company_name_ar }}
                                                                @else
                                                                    {{ $company->company_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{--العميل--}}
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('invoice.customer_name') </label>
                                                    <select name="customer_id"
                                                            class="selectpicker" data-live-search="true"
                                                            id="customer_id" required>
                                                        @foreach($customers as $customer)
                                                            <option value="{{$customer->customer_id }}"
                                                                    @if($price_list->customer_id == $customer->customer_id) selected @endif>
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $customer->customer_name_full_ar }}
                                                                @else
                                                                    {{ $customer->customer_name_full_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('trucks.truck_status') </label>
                                                    <select class="form-select form-control" name="price_list_status"
                                                            id="price_list_status" required>
                                                        <option value="" selected>@lang('home.choose')</option>

                                                        <option value="1" @if($price_list->price_list_status == 1)
                                                        selected @endif> @lang('home.active')
                                                        </option>
                                                        <option value="0" @if($price_list->price_list_status == 0)
                                                        selected @endif>@lang('home.not_active')
                                                        </option>
                                                    </select>

                                                </div>
                                                {{--التاريخ--}}
                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input id="date" type="text" class="form-control"
                                                           readonly value="{{date('d/m/Y')}}">
                                                </div>
                                                {{--المستخدم--}}
                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" class="form-control" readonly
                                                           value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                           @else {{ auth()->user()->user_name_en }} @endif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('customer.from_date')</label>
                                                    <input type="date" class="form-control" name="price_list_start_date"
                                                           id="price_list_start_date"
                                                           value="{{$price_list->price_list_start_date}}"
                                                           required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('customer.to_date')</label>
                                                    <input type="date" class="form-control" name="price_list_end_date"
                                                           id="price_list_end_date"
                                                           value="{{$price_list->price_list_end_date}}"
                                                           required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>@lang('home.invoice_notes')</label>
                                                    <textarea class="form-control" name="price_list_notes"
                                                              id="price_list_notes">{{ $price_list->price_list_notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-condensed" id="add_table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th style="width:250px"
                                                    class="text-center">@lang('customer.shipe_type')</th>

                                                <th style="width:250px"
                                                    class="text-center">@lang('customer.from_loc')</th>
                                                <th style="width:250px"
                                                    class="text-center">@lang('customer.to_loc')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('customer.max_price')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('customer.min_price')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.distance')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.time')</th>

                                                <th></th>

                                            </tr>
                                            </thead>
                                            <tbody>

                                            {{-- old data --}}

                                            <tr id="add_clone" class="clone"
                                                v-for="(price_list_dt,index) in filteredOldPriceLists3">
                                                <input type="hidden" name="price_list_dt_id[]"
                                                       :value="filteredOldPriceLists3[index]['price_list_dt_id']">

                                                <td>
                                                    <select class="form-control is-invalid type" name="item_id[]"
                                                            v-model="filteredOldPriceLists3[index]['item_id']" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_codes_item as $sys_code_item)
                                                            <option value="{{ $sys_code_item->system_code }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_item->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_item->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <select class="form-control type" name="loc_from[]"
                                                            v-model="filteredOldPriceLists3[index]['loc_from']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_codes_location as $sys_code_location)
                                                            <option value="{{ $sys_code_location->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_location->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_location->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <select class="form-control type" name="loc_to[]"
                                                            v-model="filteredOldPriceLists3[index]['loc_to']" required>
                                                        @foreach($sys_codes_location as $sys_code_location)
                                                            <option value="{{ $sys_code_location->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_location->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_location->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <input type="number" step="0.01"
                                                           v-model="filteredOldPriceLists3[index]['max_fees']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           name="max_fees[]" value="0.00" required>
                                                </td>

                                                <td class="text-center">
                                                    <input type="number" step="0.01"
                                                           v-model="filteredOldPriceLists3[index]['min_fees']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           name="min_fees[]" value="0.00" required>
                                                </td>

                                                <td>
                                                    <input type="decimal"
                                                           v-model="filteredOldPriceLists3[index]['distance']"
                                                           class="form-control no-arabic numbers-only factor"
                                                           name="distance[]" value="0.00" required>
                                                </td>

                                                <td class="text-center">
                                                    <input type="decimal"
                                                           v-model="filteredOldPriceLists3[index]['distance_time']"
                                                           class="form-control no-arabic  numbers-only"
                                                           id="distance_time"
                                                           name="distance_time[]" value="0" required>
                                                </td>

                                                <td>
                                                    <button type="button" @click="addRow()"
                                                            class="btn btn-circle btn-icon-only red-flamingo">
                                                        <i class="fa fa-plus"></i>
                                                    </button>


                                                </td>
                                                <td>
                                                    <button type="button" @click="deleteRow(index)"
                                                            class="btn btn-circle btn-icon-only yellow-gold">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>


                                            {{-- new data --}}
                                            <tr id="add_clone" class="clone" v-for="(price_list,index) in priceLists">

                                                <td>
                                                    <select class="form-control  type" name="item_id[]" required
                                                            v-model="priceLists[index]['item_id']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_codes_item as $sys_code_item)
                                                            <option value="{{ $sys_code_item->system_code }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_item->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_item->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <select class="form-control type" name="loc_from[]" required
                                                            v-model="priceLists[index]['loc_from']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_codes_location as $sys_code_location)
                                                            <option value="{{ $sys_code_location->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_location->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_location->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control type" name="loc_to[]"
                                                            v-model="priceLists[index]['loc_to']" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_codes_location as $sys_code_location)
                                                            <option value="{{ $sys_code_location->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_location->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_location->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>


                                                <td class="text-center">
                                                    <input type="number"
                                                           v-model="priceLists[index]['max_fees']" step=".01" required
                                                           class="form-control no-arabic numbers-only amount"
                                                           name="max_fees[]" value="0.00">
                                                </td>
                                                <td class="text-center">
                                                    <input type="number"
                                                           v-model="priceLists[index]['min_fees']" required
                                                           class="form-control no-arabic numbers-only amount"
                                                           name="min_fees[]" value="0.00">
                                                </td>
                                                <td>
                                                    <input type="decimal" v-model="priceLists[index]['distance']"
                                                           required
                                                           class="form-control no-arabic numbers-only factor"
                                                           name="distance[]" value="0.00">
                                                </td>

                                                <td class="text-center">
                                                    <input type="decimal"
                                                           v-model="priceLists[index]['distance_time']" required
                                                           class="form-control no-arabic  numbers-only"
                                                           id="distance_time"
                                                           name="distance_time[]" value="0">
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

                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2" id="create_inv" type="submit">
                                            @lang('home.save')</button>

                                    </div>
                                    <div class="spinner-border" role="status" style="display: none">
                                        <span class="sr-only">Loading...</span>
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
            $('form').submit(function () {

                $('#create_inv').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            $("#old_min_fees[]").blur(function () {
                this.value = parseFloat(this.value).toFixed(2);
            });
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)

        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                price_list_id:{!! $id !!},
                old_priceLists: [],
                priceLists: [{
                    'item_id': '', 'loc_from': '', 'loc_to': '',
                    'loc_to_required': false,
                    'distance': 0, 'distance_time': 0,
                    'distance_required': 0, 'distance_time_required': 0,
                    'cost_fees': 0,
                    'cost_fees_required': false,
                    'min_fees': 0.00,
                    'min_fees_required': false,
                    'max_fees': 0.00,
                    'max_fees_required': false,

                }],
                waybill_loc_from: '',
                waybill_loc_to: '',
                waybill_item: ''
            },
            mounted() {
                this.getPriceList()
            },
            methods: {
                addRequiredProp(index) {
                    if (this.priceLists[index]['loc_from']) {
                        this.priceLists[index]['item_id_required'] = true
                        this.priceLists[index]['cost_fees_required'] = true
                        this.priceLists[index]['min_fees_required'] = true
                        this.priceLists[index]['max_fees_required'] = true
                        this.priceLists[index]['distance_time_required'] = true
                        this.priceLists[index]['distance_required'] = true
                        this.priceLists[index]['loc_to_required'] = true
                    } else {

                        this.priceLists[index]['cost_fees_required'] = false
                        this.priceLists[index]['min_fees_required'] = false
                        this.priceLists[index]['max_fees_required'] = false
                        this.priceLists[index]['distance_time_required'] = false
                        this.priceLists[index]['distance_required'] = false
                        this.priceLists[index]['loc_to_required'] = false
                    }
                },
                getPriceList() {
                    $.ajax({
                        type: 'GET',
                        data: {price_list_id: this.price_list_id},
                        url: ''
                    }).then(response => {
                        this.price_list = response.data

                        this.old_priceLists = response.price_list_dts
                    })
                },
                addRow() {
                    this.priceLists.push({
                        'item_id': '', 'loc_from': '', 'loc_to': '',
                        'loc_to_required': '',
                        'distance': 0, 'distance_time': 0,
                        'distance_required': 0, 'distance_time_required': 0,
                        'cost_fees': 0,
                        'cost_fees_required': false,
                        'min_fees': 0,
                        'min_fees_required': false,
                        'max_fees': 0,
                        'max_fees_required': false,
                    })

                },
                removeRow(index) {
                    this.priceLists.splice(index, 1)
                },
                deleteRow(index) {
                    $.ajax({
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            price_list_dt_id: this.old_priceLists[index]['price_list_dt_id']
                        },
                        url: '{{ route('PriceList.delete') }}'
                    }).then(response => {
                        // console.log(response)
                        this.old_priceLists.splice(index, 1);
                    })
                }
            },

            computed: {

                filteredOldPriceLists: function () {
                    return this.old_priceLists.filter(old_priceList => {
                        return old_priceList.item_id.match(this.waybill_item)
                    })
                },

                filteredOldPriceLists2: function () {
                    return this.filteredOldPriceLists.filter(filteredOldPriceList => {
                        return filteredOldPriceList.loc_from.match(this.waybill_loc_from)
                    })
                },

                filteredOldPriceLists3: function () {
                    return this.filteredOldPriceLists2.filter(filteredOldPriceList2 => {
                        return filteredOldPriceList2.loc_to.match(this.waybill_loc_to)
                    })
                },
            }

        });
    </script>
@endsection

