@extends('Layouts.master')
@section('style')
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

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
                                        @lang('customer.add_price_list')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('PriceList-cargo.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <select class="form-control" v-model="company_id" name="company_id"
                                                            required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->company_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{ $company->company_name_ar }}
                                                                @else
                                                                    {{ $company->company_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('invoice.customer_name') </label>
                                                    <select class="form-select form-control" name="customer_id"
                                                            id="customer_id" required>
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
                                                <div class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('trucks.truck_status') </label>
                                                    <select class="form-select form-control" name="price_list_status"
                                                            id="price_list_status" required>
                                                        <option value="" selected>@lang('home.choose')</option>

                                                        <option value="1"> @lang('home.active')
                                                        </option>
                                                        <option value="0">@lang('home.not_active')
                                                        </option>
                                                    </select>

                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input id="date" type="text" class="form-control"
                                                           readonly>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" calss="form-control" readonly
                                                           class="form-control"
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
                                                           placeholder="@lang('customer.from_date')" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('customer.to_date')</label>
                                                    <input type="date" class="form-control" name="price_list_end_date"
                                                           id="price_list_end_date"
                                                           placeholder="@lang('customer.to_date')" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>@lang('home.invoice_notes')</label>
                                                    <textarea class="form-control" name="price_list_notes"
                                                    id="price_list_notes" ></textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-condensed" id="add_table">
                                            <thead class="bg-blue font-white ">
                                            <tr>
                                                <th style="width:250px"
                                                    class="text-center">@lang('customer.shipe_type')</th>

                                                <th style="width:200px"
                                                    class="text-center">@lang('customer.from_loc')</th>
                                                <th style="width:200px"
                                                    class="text-center">@lang('customer.to_loc')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('customer.max_price')</th>
                                                <th style="width:150px"
                                                    class="text-center">@lang('customer.min_price')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.distance')</th>
                                                <th style="width:130px"
                                                class="text-center">@lang('customer.time')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.cost')</th>   
                                                
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr id="add_clone" class="clone" v-for="(pricelist,index) in pricelists">
                                                <td>
                                                    <select class="form-control is-invalid type"  name="item_id[]"
                                                     v-model="pricelists[index]['item_id']" required>
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
                                                    <select class="form-control is-invalid type"  name="loc_from[]"
                                                     v-model="pricelists[index]['loc_from']" required>
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
                                                    <select class="form-control is-invalid type"  name="loc_to[]"
                                                     v-model="pricelists[index]['loc_to']" required>
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
                                                    <input type="number" v-model="pricelists[index]['max_fees']"
                                                           class="form-control  no-arabic is-invalid numbers-only"
                                                            step="0.01"

                                                           name="max_fees[]" value="0.00" required >
                                                </td>
                                                <td>
                                                    <input type="number" v-model="pricelists[index]['min_fees']"
                                                    class="form-control  no-arabic is-invalid numbers-only"
                                                     step="0.01"

                                                    name="min_fees[]" value="0.00" required >
                                                </td>

                                                <td>
                                                    <input type="text" v-model="pricelists[index]['distance']"
                                                           class="form-control no-arabic numbers-only factor"
                                                           name="distance[]" value="0.00"  required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text"
                                                           v-model="pricelists[index]['distance_time']"
                                                           class="form-control no-arabic  numbers-only"
                                                           id="distance_time"
                                                           name="distance_time[]" value="0"  required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text"
                                                           v-model="pricelists[index]['cost_fees']"
                                                           class="form-control no-arabic  numbers-only"
                                                           id="cost_fees"
                                                           name="cost_fees[]" value="0"  required>
                                                </td>
                                                
                                                <td>
                                                    <button type="button" @click="addRow()"
                                                            class="btn btn-circle btn-icon-only red-flamingo">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button" @click="removeRow(index)" v-if="index>0"
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
                                        <button class="btn btn-primary mt-2"  id="create_inv" type="submit">
                                            @lang('home.save')</button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

<script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>



@section('scripts')
    <script>
        $(document).ready(function () {
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)

            var cloneRow = $('#add_clone').clone();
            cloneRow.removeAttr('id');
            $('.remove-row', cloneRow).removeClass('hidden');
            var tableClone = $('#add_table tbody');
            var startCounter = ('tbody tr', tableClone).length;
            tableClone.on('click', '.add_row', function () {
                var row = cloneRow.clone();
                startCounter += 1;
                $('input,select', row).each((i, e) => {
                    var el = $(e);
                    var name = el.attr('name') || "";
                    name = name.replaceAll(/\d/ig, startCounter);
                    el.attr('name', name);
                });
                tableClone.append(row)
            })
            tableClone.on('click', '.remove-row', function (e) {
                var row = $(this).closest('tr');
                row.remove();
            })




        })

    </script>




    <script>

        new Vue({
            el: '#app',
            data: {
                company_id: '',
                acc_period_id: '',

                customer_id: '',
                pricelists: [{
                        'item_id':'','loc_from':'','loc_to':'','max_fees': 0.00, 'min_fees': 0.000,
                        'distance': 0.00, 'distance_time': 0.00,
                        'cost_fees': 0.00, 'distance_fees': 0.00
                }],
                waybill_id:''
            },
            methods: {
                addRow() {
                    this.pricelists.push({
                        'item_id':'','loc_from':'','loc_to':'','max_fees': 0.00, 'min_fees': 0.00,
                        'distance': 0.00, 'distance_time': 0.00,
                        'cost_fees': 0.00, 'distance_fees': 0.00
                    })
                },
                removeRow(index) {
                    this.pricelists.splice(index, 1)
                },


            },


        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
@endsection

