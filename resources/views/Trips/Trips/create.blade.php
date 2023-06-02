@extends('Layouts.master')

@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>


    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <style lang="">
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .v-application {
            font-family: "Cairo" !important;
        }
    </style>
@endsection


@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <v-app>

            <div class="container-fluid">
                <div class="tab-content mt-3">


                    {{-- Form To Create Customer--}}

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <form action="{{route('Trips.store')}}" method="post">
                                    @csrf
                                    <div class="card-body">

                                        <div class="row clearfix">
                                            {{--كود الشاحنه--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.truck_code')</label>
                                                    <select class="selectpicker" name="truck_id" data-live-search="true"
                                                            @change="getTrucks()" v-model="truck_id"
                                                            required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($trucks as $truck)
                                                            <option value="{{$truck->truck_id}}">{{$truck->truck_code . '=>'
                                                        . $truck->truck_name . '=>' .  $truck->driver->emp_name_full_ar}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{--  رقم الشاحنه--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone-ex" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.truck_plate')</label>
                                                    <input type="text" id="" :value="truck.truck_plate_no"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           readonly>

                                                </div>
                                            </div>

                                            {{--نوع الناقله--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone-ex" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.truck_type')</label>
                                                    @if(app()->getLocale()=='ar')
                                                        <input type="text" id="" :value="truck_type.system_code_name_ar"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               class="form-control" readonly>
                                                    @else
                                                        <input type="text" id="" :value="truck_type.system_code_name_en"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               class="form-control" readonly>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--حالة الشاحنه--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.status')</label>
                                                    <input type="text" id="" style="font-size: 16px ;font-weight: bold"
                                                           @if(app()->getLocale() == 'ar')
                                                           :value="status.system_code_name_ar"
                                                           @else
                                                           :value="status.system_code_name_ar"
                                                           @endif
                                                           class="form-control"
                                                           readonly>
                                                </div>
                                            </div>

                                            {{--تاريخ الانشاء--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="ssn"
                                                           class="control-label">@lang('home.trip_start_date')</label>
                                                    <input type="text" id="date" name="trip_hd_date"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           class="form-control"
                                                           readonly>

                                                </div>
                                            </div>

                                            {{--second row--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <label class="form-label"
                                                       style="font-size: 16px ;font-weight: bold">@lang('trucks.trip_line_type')</label>
                                                <select class="form-select form-control" name="trip_line_type"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        required v-model="trip_line_type" @change="getTripLinesAll()">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($sys_line_type as $sys_line_types)
                                                        <option value="{{ $sys_line_types->system_code }}">
                                                            @if(app()->getLocale()=='ar')
                                                                {{$sys_line_types->system_code_name_ar}}
                                                            @else
                                                                {{$sys_line_types->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            {{--اسم السائق--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <input type="hidden" :value="driver.emp_id" name="driver_id"
                                                           id="driver_id">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.driver_name')</label>

                                                    <input type="text" id="product-key"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           @if(app()->getLocale()== 'ar')
                                                           :value="driver.emp_code + '-'+driver.emp_name_full_ar"
                                                           @else
                                                           :value="driver.emp_code + '-' +driver.emp_name_full_en"
                                                           @endif
                                                           readonly>
                                                </div>
                                            </div>


                                            {{--الهويه السائق--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.identity')</label>
                                                    <input type="text" id="emp_identity" name="emp_identity"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           class="form-control" :value="driver.emp_identity" readonly>
                                                </div>
                                            </div>
                                            {{--رقم النسخه --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="issueNumber" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.issue_number')</label>
                                                    <input type="number" id="issueNumber" name="issueNumber"
                                                           :value="driver.issueNumber"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold" required>
                                                </div>
                                            </div>
                                            {{--رقم الجوال --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="emp_private_mobile"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.phone_number')</label>
                                                    <input type="number" id="emp_private_mobile"
                                                           name="emp_private_mobile" :value="driver.emp_private_mobile"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold" required>
                                                </div>
                                            </div>

                                            {{--الفروع--}}
                                            <div class="col-md-2">
                                                <label>@lang('home.branches')</label>
                                                <select class="selectpicker" data-live-search="true" id="branch_id"
                                                        name="branch_id" required data-actions-box="true"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        v-model="branch_id" @change="getTripLinesAll()">
                                                    <option value="0">اختار الكل</option>
                                                    @foreach($company->branches as $branch)
                                                        <option value="{{$branch->branch_id}}"
                                                                @if(!request()->branch_id) @if(session('branch')['branch_id'] == $branch->branch_id)
                                                                selected @endif @endif
                                                                @if(request()->branch_id) @foreach(request()->branch_id as
                                                                    $branch_id) @if($branch_id == $branch->branch_id)
                                                                selected @endif @endforeach @endif>
                                                            {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                                            $branch->branch_name_en}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.trip_line')</label>
                                                <v-autocomplete
                                                        required
                                                        name="trip_line_hd_id"
                                                        v-model="trip_line_hd_id"
                                                        :items="trip_lines"
                                                        item-value="trip_line_hd_id"
                                                        item-text="trip_line_desc"
                                                        @change="getTripline()"
                                                        label="@lang('home.trip_line')"
                                                ></v-autocomplete>
                                            </div>

                                            {{--المسافة  --}}
                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.distance')</label>
                                                    <input type="text" id="" name="trip_hd_distance"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           :readonly="trip_line_type!=126006"
                                                           v-model="trip_line_distance">
                                                </div>
                                            </div>

                                            {{--الوقت --}}
                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.time')</label>
                                                    <input type="text" id="" class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           :readonly="trip_line_type!=126006"
                                                           v-model="trip_line_time">
                                                </div>
                                            </div>
                                            {{--اسم المستخدم --}}
                                            <div hidden class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key"
                                                           class="control-label">@lang('home.user_name')</label>
                                                    <input type="text" id="" class="form-control"
                                                           value="{{app()->getLocale() == 'ar'
                                                       ? auth()->user()->user_name_ar
                                                       : auth()->user()->user_name_en}}" readonly>
                                                </div>
                                            </div>

                                            {{--تاريخ الانطلاق --}}
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.lunch_date')</label>
                                                    <input type="datetime-local" id="" name="trip_hd_start_date"
                                                           v-model="trip_hd_start_date" @change="getArrivalDate()"
                                                           class="form-control" value="{{$current_date}}" required>
                                                </div>
                                            </div>

                                            {{--تاريخ الوصول --}}
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.arrival_date')</label>
                                                    <input type="datetime-local" id="" name="trip_hd_end_date"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           readonly v-model="trip_hd_end_date">
                                                </div>
                                            </div>

                                            {{--المصروف --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.cost_fees')</label>
                                                    <input type="text" id="" class="form-control" name="trip_hd_fees_1"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           :readonly="trip_line_type!=126006"
                                                           :value="trip_line.trip_line_fess_1">
                                                </div>
                                            </div>

                                            {{--عداد الانطلاق --}}
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.lunch_counter')</label>
                                                    <input type="number" id="" name="truck_meter_start"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           class="form-control" v-model="truck_meter_start"
                                                           @change="getArrivalDate()" required>
                                                </div>
                                            </div>

                                            {{--عداد الوصور --}}
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.arrival_counter')</label>
                                                    <input type="number" id="" class="form-control"
                                                           name="truck_meter_end"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           readonly v-model="truck_meter_end">
                                                </div>
                                            </div>
                                            {{--مكافأة الطريق --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.road_reward')</label>
                                                    <input type="number" id="" name="trip_hd_fees_2"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           :readonly="trip_line_type!=126006"
                                                           :value="trip_line.trip_line_fees_2">

                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.notes')</label>
                                                    <input type="text" id="trip_hd_notes" name="trip_hd_notes"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           class="form-control">
                                                </div>
                                            </div>

                                        </div>

                                        <hr>


                                        {{--بيانات الرحله المسعفه--}}
                                        <div class="row clearfix" v-show="trip_line_type == 126006">
                                            {{--كود الشاحنه--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">{{__('trips')}}</label>
                                                    <select class="selectpicker" data-live-search="true"
                                                            v-model="old_trip_id"
                                                            name="old_trip_id" @change="getTripDt()">
                                                        <option value="">{{__('choose')}}</option>
                                                        @foreach($launched_trips as $launched_trip)
                                                            <option value="{{$launched_trip->trip_hd_id}}">{{$launched_trip->trip_hd_code}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>

                                            {{--خط السير--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.notes')</label>
                                                    <input type="text" class="form-control" readonly
                                                           :value="old_trip_line.trip_line_desc">
                                                </div>
                                            </div>

                                            {{--المسافه--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.distance')</label>

                                                    <input type="text" class="form-control" readonly
                                                           :value="old_trip.trip_hd_distance">
                                                </div>
                                            </div>

                                            {{--الديزل--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.cost_fees')</label>

                                                    <input type="text" class="form-control" readonly
                                                           :value="old_trip.trip_hd_fees_1">
                                                </div>
                                            </div>

                                            {{--الحافز--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.road_reward')</label>
                                                    <input type="text" class="form-control" readonly
                                                           :value="old_trip.trip_hd_fees_2">
                                                </div>
                                            </div>


                                            {{--البيانات الجديده للرحله المسعفه--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                            </div>

                                            {{--خط السير الجديد--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.trip_line')</label>
                                                    <v-autocomplete
                                                            :required="new_old_trip_required"
                                                            name="old_trip_line_hd_id"
                                                            v-model="new_old_trip_line_hd_id"
                                                            :items="old_trip_lines"
                                                            item-value="trip_line_hd_id"
                                                            item-text="trip_line_desc"
                                                            @change="getOldTripline()"
                                                            label="@lang('home.trip_line')"
                                                    ></v-autocomplete>
                                                </div>
                                            </div>

                                            {{--المسافه الجديه--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.distance')</label>

                                                    <input type="text" class="form-control"
                                                           name="old_trip_line_distance"
                                                           :required="new_old_trip_required"
                                                           v-model="new_old_trip_line_distance">
                                                </div>
                                            </div>

                                            {{--الديزل الجديد--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.cost_fees')</label>

                                                    <input type="text" class="form-control" name="old_trip_line_fess_1"
                                                           :required="new_old_trip_required"
                                                           v-model="new_old_trip_line.trip_line_fess_1">
                                                </div>
                                            </div>

                                            {{--الحافز الجديد--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.road_reward')</label>
                                                    <input type="text" class="form-control" name="old_trip_hd_fees_2"
                                                           :required="new_old_trip_required"
                                                           v-model="new_old_trip_line.trip_line_fees_2">
                                                </div>
                                            </div>


                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button class="btn btn-primary" type="submit" id="submit"
                                                :disabled="disable_button || disable_button_2">
                                            @lang('home.save')</button>

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

        </v-app>
    </div>

@endsection

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>

        // function submitForm(e) {
        //     e.preventDefault();


        // }
        $(document).ready(function () {
            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

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
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

    <script>

        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                truck_id: '',
                truck: {},
                status: {},
                driver: {},
                trip_line: {},
                trip_line_hd_id: '',
                trip_hd_start_date: '{{$current_date}}',
                trip_hd_end_date: '',
                trip_line_time: '',
                trip_line_distance: '',
                truck_meter_start: '',
                truck_type: {},
                branch_id: '{{session('branch')['branch_id']}}',
                truck_type_id: '',
                trip_lines: [],
                trip_line_type: '',
                old_trip_id: '',
                old_trip: {},
                old_trip_line_hd: {},
                old_trip_line: {},
                new_old_trip_line: {},
                new_old_trip_line_hd_id: '',
                new_old_trip_line_time: '',
                new_old_trip_line_distance: '',
                old_trip_lines: [],
                disable_button_2: true
            },
            mounted() {
                if (this.branch_id) {
                    this.getTripLinesAll()
                }
            },
            methods: {
                getTripDt() {
                    $.ajax({
                        type: 'GET',
                        data: {old_trip_id: this.old_trip_id},
                        url: '{{ route("api.Trips.getOldTripData") }}'
                    }).then(response => {
                        this.old_trip = response.trip
                        this.old_trip_line = response.trip_line
                        this.getOldTriplines();
                    })
                },
                getTrucks() {
                    $.ajax({
                        type: 'GET',
                        data: {truck_id: this.truck_id},
                        url: '{{ route("api.Trips.getTruck") }}'
                    }).then(response => {
                        this.truck = response.data
                        this.status = response.status
                        this.driver = response.driver
                        this.truck_type = response.truck_type
                        this.truck_type_id = this.truck_type.system_code_id

                        if (this.truck_type_id) {
                            this.getTripLinesAll();
                        }
                    })
                },
                getTripLinesAll() {
                    //api.Trips.getTripLines
                    this.trip_lines = []
                    this.truck_meter_start = ''
                    if (this.truck_type_id || this.trip_line_type) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                branch_id: this.branch_id,
                                truck_type_id: this.truck_type_id,
                                trip_line_type: this.trip_line_type
                            },
                            url: '{{ route("api.Trips.getTripLines") }}'
                        }).then(response => {
                            this.trip_lines = response.data
                        })
                    }

                },
                getOldTriplines() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("api.Trips.getOldTripLines") }}'
                    }).then(response => {
                        this.old_trip_lines = response.data
                    })
                },
                getTripline() {
                    this.disable_button_2 = true
                    this.trip_line = {}
                    this.trip_line_time = ''
                    this.trip_line_distance = ''
                    this.trip_hd_end_date = ''
                    $.ajax({
                        type: 'GET',
                        data: {trip_line_hd_id: this.trip_line_hd_id},
                        url: '{{ route("api.Trips.getTripLine") }}'
                    }).then(response => {
                        this.trip_line = response.data
                        this.trip_line_time = response.data.trip_line_time
                        this.trip_line_distance = response.data.trip_line_distance
                        this.getTime()

                    })


                },
                getTime() {
                    $.ajax({
                        type: 'GET',
                        data: {
                            trip_hd_start_date: this.trip_hd_start_date,
                            trip_line_time: this.trip_line_time
                        },
                        url: '{{ route("api.Trips.getArrivalDate") }}'
                    }).then(response => {
                        this.trip_hd_end_date = response.data
                        this.disable_button_2 = false
                    })
                },
                getOldTripline() {
                    this.new_old_trip_line_time = ''
                    this.new_old_trip_line_distance = ''
                    this.new_old_trip_line = {}
                    $.ajax({
                        type: 'GET',
                        data: {trip_line_hd_id: this.new_old_trip_line_hd_id},
                        url: '{{ route("api.Trips.getTripLine") }}'
                    }).then(response => {
                        this.new_old_trip_line = response.data
                        this.new_old_trip_line_time = response.data.trip_line_time
                        this.new_old_trip_line_distance = response.data.trip_line_distance
                    })

                },
                getArrivalDate() {
                    $.ajax({
                        type: 'GET',
                        data: {
                            trip_hd_start_date: this.trip_hd_start_date,
                            trip_line_time: this.trip_line_time
                        },
                        url: '{{ route("api.Trips.getArrivalDate") }}'
                    }).then(response => {
                        this.trip_hd_end_date = response.data
                    })
                },

            },
            computed: {
                truck_meter_end: function () {
                    return parseFloat(this.truck_meter_start) + parseFloat(this.trip_line_distance)
                },
                new_old_trip_required: function () {
                    if (this.trip_line_type == 126006) {
                        return true
                    } else {
                        return false
                    }
                },
                disable_button: function () {
                    if (this.truck_meter_start < 1) {
                        return true
                    } else {
                        return false
                    }
                }
            }

        })
    </script>
@endsection
