@extends('Layouts.master')
@section('style')
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
                                    <div class="font-25 bold">
                                        @lang('trucks.trip_lines')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('TripLine.update',$trip_line->trip_line_hd_id) }}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <input type="text" disabled="" class="form-control"
                                                           @if(app()->getLocale()=='ar') :value="trip_line_hd.company_name_ar"
                                                           @else
                                                           :value="trip_line_hd.company_name_en" @endif>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.trip_line_code')</label>
                                                    <input type="text" calss="form-control" disabled
                                                           class="form-control" @if(app()->getLocale()=='ar')
                                                           :value="trip_line_hd.trip_line_code" @else
                                                           :value="trip_line_hd.trip_line_code" @endif>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('trucks.truck_status') </label>
                                                    <select class="form-select form-control" name="trip_line_status"
                                                            id="trip_line_status" required
                                                            v-model="trip_line_hd.trip_line_status">
                                                        <option value="1"> @lang('home.active') </option>
                                                        <option value="0">@lang('home.not_active')</option>
                                                    </select>

                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.updated_date')</label>
                                                    <input id="date" type="text" class="form-control"
                                                           disabled>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" calss="form-control" disabled
                                                           class="form-control" @if(app()->getLocale()=='ar')
                                                           :value="trip_line_hd.user_name_ar" @else
                                                           :value="trip_line_hd.user_name_en" @endif>
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
                                                    <input type="text" class="form-control" disabled=""
                                                           @if(app()->getLocale()=='ar') :value="trip_line_hd.loc_from_name_ar"
                                                           @else
                                                           :value="trip_line_hd.loc_from_name_en" @endif>
                                                    <input type="hidden" name="loc_from_id"
                                                           v-model="trip_line_hd.loc_from_id">

                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('trucks.trip_line_loc_to')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           @if(app()->getLocale()=='ar') :value="trip_line_hd.loc_to_name_ar"
                                                           @else
                                                           :value="trip_line_hd.loc_to_name_en" @endif>

                                                    <input type="hidden" name="loc_to_id"
                                                           v-model="trip_line_hd.loc_to_id">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('trucks.truck_type')</label>
                                                    <select class="form-select form-control" name="truck_type"
                                                            id="truck_type" required v-model="trip_line_hd.truck_type">
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
                                                            id="trip_line_type" required
                                                            v-model="trip_line_hd.trip_line_type">
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

                                                <div class="col-md-6">
                                                    <label>@lang('home.invoice_notes')</label>
                                                    <textarea class="form-control" name="price_list_notes"
                                                              id="price_list_notes" readonly>@{{trip_line_hd.trip_line_desc}}</textarea>
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

                                            <tr id="add_clone" class="clone"
                                                v-for="(trip_line_dt,index) in trip_line_dts">
                                                <input type="hidden" :value="trip_line_dt.trip_line_dt_id"
                                                       name="trip_line_dt[]">
                                                <td>
                                                    <select class="form-control type" name="loc_from[]"
                                                            required v-model="trip_line_dt.loc_from_id"
                                                            @change="index == 0 ? onChangeFrom($event) : ''">
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
                                                            v-model="trip_line_dt.loc_to_id"
                                                            @change="setLocationNext(index);index+1 == trip_line_dts.length ? onChangeTo($event) : ''">
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
                                                    <input type="decimal" name="distance[]"
                                                           class="form-control no-arabic numbers-only factor"
                                                           v-model="trip_line_dt.distance">
                                                </td>
                                                <td class="text-center">
                                                    <input type="decimal" name="distance_time[]"
                                                           class="form-control no-arabic numbers-only factor"
                                                           v-model="trip_line_dt.distance_time">
                                                </td>

                                                <td class="text-center">

                                                    <input type="decimal" name="cost_fees_1[]"
                                                           class="form-control no-arabic numbers-only factor"
                                                           v-model="trip_line_dt.cost_fees_1">
                                                </td>

                                                <td class="text-center">
                                                    <input type="decimal" name="cost_fees_2[]"
                                                           class="form-control no-arabic numbers-only factor"
                                                           v-model="trip_line_dt.cost_fees_2">
                                                </td>

                                                <td class="text-center">
                                                    <input type="decimal" name="cost_fees_3[]"
                                                           class="form-control no-arabic numbers-only factor"
                                                           v-model="trip_line_dt.cost_fees_3">
                                                </td>

                                                <td>
                                                    <button type="button" @click="addRow()"
                                                            class="btn btn-circle btn-icon-only red-flamingo">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button"
                                                            @click="removeRow(index,trip_line_dt.trip_line_dt_id)"
                                                            v-if="index>0"
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
                trip_line_hd_id: '',
                trip_line_hd: {},
                trip_line_dts: [],
                length: 0,
                count: 0,
            },
            mounted() {
                this.trip_line_hd_id = '{{$id}}'
                this.getTripLine()
            },
            methods: {
                getTripLine() {
                    $.ajax({
                        type: 'GET',
                        data: {trip_line_hd_id: this.trip_line_hd_id},
                        url: ''
                    }).then(response => {
                        this.trip_line_hd = response.data
                        this.trip_line_dts = response.tripLineDts
                    })
                },
                removeRow(index, id) {
                    if (id > 0) {
                        $.ajax({
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                trip_line_dt_id: id
                            },
                            url: '{{ route('TripLine.delete') }}'
                        }).then(response => {
                            this.trip_line_dts.splice(index, 1);
                        })
                    } else {
                        this.trip_line_dts.splice(index, 1)
                    }

                },
                addRow() {
                    this.count = 0
                    this.trip_line_dts.push({
                        'trip_line_dt_id': 0,
                        'loc_from_id': '',
                        'loc_to_id': '',
                        'loc_from_name': '',
                        'loc_to_name': '',
                        'distance': 0,
                        'distance_time': 0,
                        'cost_fees_1': 0,
                        'cost_fees_2': 0,
                        'cost_fees_3': 0,
                    });

                    this.length = Object.keys(this.trip_line_dts).length

                    for (this.count; this.count < this.length; this.count++) {
                        if (this.trip_line_dts[this.count]['loc_to_id']) {
                            this.trip_line_dts[this.count + 1]['loc_from_id'] = this.trip_line_dts[this.count]['loc_to_id']
                        }
                    }
                },
                setLocationNext(index) {
                    if (Object.keys(this.trip_line_dts).length > (index + 1)) {
                        this.trip_line_dts[index + 1]['loc_from_id'] = this.trip_line_dts[index]['loc_to_id']
                    }
                },
                onChangeFrom: function (e) {


                    var id = e.target.value;
                    var name = e.target.options[e.target.options.selectedIndex].text;

                    this.trip_line_hd.loc_from_name_ar = name
                    this.trip_line_hd.loc_from_name_en = name
                    this.trip_line_hd.loc_from_id = id

                },
                onChangeTo: function (e) {
                    var id = e.target.value;
                    var name = e.target.options[e.target.options.selectedIndex].text;

                    this.trip_line_hd.loc_to_name_ar = name
                    this.trip_line_hd.loc_to_name_en = name
                    this.trip_line_hd.loc_to_id = id


                },
            },
            computed: {
                total_cost_fees_1: function () {
                    let total = 0;
                    Object.entries(this.trip_line_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.cost_fees_1))
                    });
                    return total;
                },
                total_cost_fees_2: function () {
                    let total = 0;
                    Object.entries(this.trip_line_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.cost_fees_2))
                    });
                    return total;
                },
                total_cost_fees_3: function () {
                    let total = 0;
                    Object.entries(this.trip_line_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.cost_fees_3))
                    });
                    return total;
                },
                total_distance: function () {
                    let total = 0;

                    Object.entries(this.trip_line_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.distance))
                    });
                    return total;

                },
                total_distance_time: function () {
                    let total = 0;
                    Object.entries(this.trip_line_dts).forEach(([key, val]) => {
                        total += (parseFloat(val.distance_time))
                    });
                    return total;
                },
            }
        });
    </script>
@endsection

