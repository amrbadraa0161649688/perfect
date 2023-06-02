@extends('Layouts.master')
@section('style')
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
                                        @lang('trucks.add_trip_line')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('TripLine.store') }}" method="post">
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
                                                           class="form-label"> @lang('trucks.truck_status') </label>
                                                    <select class="form-select form-control" name="trip_line_status"
                                                            id="trip_line_status" required>
                                                        <option value="" selected>@lang('home.choose')</option>

                                                        <option value="1"> @lang('home.active')
                                                        </option>
                                                        <option value="0">@lang('home.not_active')
                                                        </option>
                                                    </select>

                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input id="date" type="text" class="form-control"
                                                           readonly>
                                                </div>

                                                <div class="col-md-3">
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
                                                    <label class="form-label">@lang('trucks.trip_line_loc_from')</label>
                                                    <select class="form-control type"
                                                            name="trip_line_loc_from"
                                                            v-model="trip_line_loc_from" required
                                                            @change="setFirstLocation()">
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
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('trucks.trip_line_loc_to')</label>
                                                    <select class="form-control type" name="trip_line_loc_to"
                                                            id="trip_line_loc_to"
                                                            v-model="trip_line_loc_to" required>
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
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('trucks.truck_type')</label>
                                                    <select class="form-select form-control" name="truck_type"
                                                            id="truck_type"
                                                            v-model="truck_type" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_truck_type as $sys_truck_types)
                                                            <option value="{{ $sys_truck_types->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_truck_types->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_truck_types->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('trucks.trip_line_type')</label>
                                                    <select class="form-select form-control" name="trip_line_type"
                                                            v-model="trip_line_type" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_line_type as $sys_line_types)
                                                            <option value="{{ $sys_line_types->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_line_types->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_line_types->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>@lang('home.invoice_notes')</label>
                                        <textarea class="form-control" name="price_list_notes"
                                                  id="price_list_notes"></textarea>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-condensed" id="add_table">
                                            <thead  class="thead-light">
                                            <tr>

                                                <th style="width:250px"
                                                    class="text-center">@lang('customer.from_loc')</th>
                                                <th style="width:250px"
                                                    class="text-center">@lang('customer.to_loc')</th>

                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.distance')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.time')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.diesel_expense')</th>

                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.road_bonus')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('customer.fess')</th>

                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr id="add_clone" class="clone" v-for="(price_list,index) in pricelists">

                                                <td>
                                                    <select class="form-control type" name="loc_from[]"
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
                                                    <select class="form-control type" name="loc_to[]"
                                                            v-model="pricelists[index]['loc_to']" required
                                                            @change="setLocationNext(index)">
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
                                                    <input type="decimal" v-model="pricelists[index]['distance']"
                                                           class="form-control no-arabic numbers-only factor"
                                                           name="distance[]" value="0" required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="decimal"
                                                           v-model="pricelists[index]['distance_time']"
                                                           class="form-control no-arabic  numbers-only"
                                                           id="distance_time"
                                                           name="distance_time[]" value="0" required>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number"
                                                           v-model="pricelists[index]['cost_fees_1']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           step="0.01"
                                                           name="cost_fees_1[]" value="0.00" required>
                                                </td>

                                                <td class="text-center">
                                                    <input type="number"
                                                           v-model="pricelists[index]['cost_fees_2']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           step="0.01"
                                                           name="cost_fees_2[]" value="0.00" required>
                                                </td>

                                                <td class="text-center">
                                                    <input type="number"
                                                           v-model="pricelists[index]['cost_fees_3']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           step="0.01"
                                                           name="cost_fees_3[]" value="0.00" required>
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

                                            <tr>
                                                <td>@lang('home.total')</td>

                                                <td></td>

                                                <td>
                                                    <input type="decimal" readonly class="form-control"
                                                           name="total_distance" v-model="total_distance">

                                                    {{--<input type="hidden" :value="total_distance" name="total_distance">--}}
                                                </td>


                                                <td>
                                                    <input type="decimal" readonly class="form-control"
                                                           name="total_distance_time" v-model="total_distance_time">
                                                </td>

                                                <td>
                                                    <input type="decimal" readonly class="form-control" step=".01"
                                                           name="total_cost_fees_1" v-model="total_cost_fees_1">
                                                </td>

                                                <td>
                                                    <input type="decimal" readonly class="form-control" step=".01"
                                                           name="total_cost_fees_2" v-model="total_cost_fees_2">
                                                </td>

                                                <td>
                                                    <input type="decimal" readonly class="form-control" step=".01"
                                                           name="total_cost_fees_3" v-model="total_cost_fees_3">
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
    <script>
        $(document).ready(function () {

            $('form').submit(function () {

                $('#create_inv').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })
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

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                company_id: '',
                acc_period_id: '',

                customer_id: '',
                pricelists: [{
                    'item_id': '', 'loc_from': '', 'loc_to': '',
                    'distance': 0.00, 'distance_time': 0.00,
                    'cost_fees_1': 0.00,
                    'cost_fees_2': 0.00,
                    'cost_fees_3': 0.00
                }],
                waybill_id: '',
                trip_line_loc_from: '',
                trip_line_loc_to: '',
                trip_line_type: '',
                truck_type: '',
                length: '',
                count: 0,
            },
            methods: {
                addRow() {
                    this.count = 0
                    this.pricelists.push({
                        'item_id': '', 'loc_from': '', 'loc_to': '',
                        'distance': 0.00, 'distance_time': 0.00,
                        'cost_fees_1': 0.00,
                        'cost_fees_2': 0.00,
                        'cost_fees_3': 0.00,
                    })

                    this.length = Object.keys(this.pricelists).length

                    for (this.count; this.count < this.length; this.count++) {
                        if (this.pricelists[this.count]['loc_to']) {
                            this.pricelists[this.count + 1]['loc_from'] = this.pricelists[this.count]['loc_to']
                        }
                    }
                },
                removeRow(index) {
                    this.pricelists.splice(index, 1)
                },
                setFirstLocation() {
                    this.pricelists[0]['loc_from'] = this.trip_line_loc_from
                },
                setLocationNext(index) {
                    if (Object.keys(this.pricelists).length > (index + 1)) {
                        this.pricelists[index + 1]['loc_from'] = this.pricelists[index]['loc_to']
                    }
                },

            },
            computed: {
                total_cost_fees_1: function () {
                    let total = 0;
                    Object.entries(this.pricelists).forEach(([key, val]) => {
                        total += (parseFloat(val.cost_fees_1))
                    });
                    return total;
                },
                total_cost_fees_2: function () {
                    let total = 0;
                    Object.entries(this.pricelists).forEach(([key, val]) => {
                        total += (parseFloat(val.cost_fees_2))
                    });
                    return total;
                },
                total_cost_fees_3: function () {
                    let total = 0;
                    Object.entries(this.pricelists).forEach(([key, val]) => {
                        total += (parseFloat(val.cost_fees_3))
                    });
                    return total;
                },
                total_distance: function () {
                    let total = 0;

                    Object.entries(this.pricelists).forEach(([key, val]) => {
                        total += (parseFloat(val.distance))
                    });
                    return total;

                },
                total_distance_time: function () {
                    let total = 0;
                    Object.entries(this.pricelists).forEach(([key, val]) => {
                        total += (parseFloat(val.distance_time))
                    });
                    return total;
                },

            }


        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
@endsection

