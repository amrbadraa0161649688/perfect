@extends('Layouts.master')
@section('content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#data-grid" data-toggle="tab"
                           class="nav-link active">@lang('home.data')</a>
                    </li>

                    <li class="nav-item">
                        <a href="#attachments-grid" data-toggle="tab" class="nav-link">@lang('home.attachments')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#notes-grid" data-toggle="tab">@lang('home.notes')</a>
                    </li>


                </ul>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            <form action="{{route('car-accident.store')}}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="tab-content mt-3">


                    <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title"></h3>

                                    <div class="row">
                                        <input type="hidden" name="contract_id" value="{{$contract->contract_id}}">
                                        <input type="hidden" name="car_id" value="{{$contract->car->car_id}}">
                                        {{-- بيانات المستأجر --}}
                                        <div class="col-md-6 "
                                             style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px; ">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control text-center" readonly
                                                           value="بيانات المستأجر \ المسؤول">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.name')</label>
                                                        <input type="text" class="form-control text-center" readonly

                                                               @if(app()->getLocale() == 'ar')
                                                               value="{{$contract->customer->customer_name_full_ar}}"
                                                               @else
                                                               value="{{$contract->customer->customer_name_full_en}}"
                                                               @endif
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.id_number')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="{{$contract->c_idNumber}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.phone_number')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="{{$contract->c_mobile}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.nationality')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               value="{{$contract->customer->customer_nationality}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                                <div hidden class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.birthday')</label>
                                                        <input type="date" class="form-control text-center" readonly
                                                               value="{{$contract->customer->customer_birthday}}"
                                                               style="background-color:transparent">
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                        {{-- بيانات السياره --}}
                                        <div class="col-md-6 "
                                             style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px;">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control text-center" readonly
                                                           value="بيانات السياره">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.car_number')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$contract->car->full_car_plate}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.car_type')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent" value="{{app()->getLocale() =='ar' ?
                                                       $contract->car->brand->brand_name_ar : $contract->car->brand->brand_name_en}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('carrent.car_model')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$contract->car->car_model_year}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('carrent.car_color')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$contract->car->car_color}}">
                                                    </div>
                                                </div>

                                                <div hidden class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('carrent.car_ownership')</label>
                                                        <input type="text" class="form-control text-center" readonly
                                                               style="background-color:transparent"
                                                               value="{{$contract->car->owner_name}}">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                    </div>

                                    {{--,وصف الحادث--}}

                                    <div class="row mt-3 mb-3"
                                         style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px; ">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.date_registration')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       name="car_accident_date_open" v-model="car_accident_date_open">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.date_registration_hejri')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       name="car_accident_date_open_hejri"
                                                       v-model="car_accident_date_open_hejri">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.today')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{ \Carbon\Carbon::now()->translatedFormat('l')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.type')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       @if(isset($contract)) value="@lang('home.contract')" @endif >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.contract_itinerary')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{$contract->contract_code}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.user')</label>
                                                <input type="text" class="form-control" disabled=""
                                                       @if(app()->getLocale() == 'ar')
                                                       value="{{auth()->user()->user_name_ar}}"
                                                       @else
                                                       value="{{auth()->user()->user_name_en}}"
                                                        @endif >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 mb-3"
                                         style="border: #0E9BE2 solid 1px; border-radius: 5px; padding: 10px; ">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.accident_type')</label>
                                                <select name="car_accident_type_id" class="form-control" required>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($accident_types as $accident_type)
                                                        <option value="{{$accident_type->system_code_id}}">
                                                            {{app()->getLocale() == 'ar'
                                                            ? $accident_type->system_code_name_ar
                                                            : $accident_type->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.accident_status')</label>

                                                <input type="text" class="form-control" readonly
                                                       @if(app()->getLocale() == 'ar')
                                                       value="{{$accident_status->system_code_name_ar}}"
                                                       @else
                                                       value="{{$accident_status->system_code_name_en}}"
                                                        @endif >

                                                <input type="hidden" name="car_accident_status"
                                                       value="{{$accident_status->system_code_id}}">

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.actual_accident_date')</label>
                                                <input type="date" class="form-control"
                                                       name="car_accident_date" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.direct_accident')</label>

                                                <select name="car_accident_directly" class="form-control">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($direct_accident as $direct_acc)
                                                        <option value="{{$direct_acc->system_code_id}}">
                                                            {{app()->getLocale() == 'ar'
                                                            ? $direct_acc->system_code_name_ar
                                                            : $direct_acc->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.status_number')</label>
                                                <input type="number" min="0" class="form-control"
                                                       value="" name="car_accident_status_no">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.appreciation_body')</label>

                                                <select name="car_accident_appreciate" class="form-control">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($appreciation_sides as $appreciation_side)
                                                        <option value="{{$appreciation_side->system_code_id}}">
                                                            {{app()->getLocale() == 'ar'
                                                            ? $appreciation_side->system_code_name_ar
                                                            : $appreciation_side->system_code_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.appreciation_status_number')</label>
                                                <input type="text" class="form-control"
                                                       name="car_accident_appreciate_status">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">@lang('home.Compensation_amount')</label>
                                                <input type="text" class="form-control"
                                                       value="" name="car_accident_amount">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group mb-0">
                                                <label class="form-label">@lang('home.Description_of_the_accident')</label>
                                                <textarea rows="5" class="form-control" value=""
                                                          name="car_accident_notes"></textarea>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">@lang('home.add')</button>
                                </div>


                            </div>
                        </div>
                    </div>

                    {{------------Practical_attachment_grid---------------------------------------------------------------}}
                    <div class="tab-pane fade" id="attachments-grid" role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-primary mb-2" id="add_files">
                                <i class="fe fe-plus"></i>
                                @lang('home.add_files')
                            </button>
                        </div>
                        <div class="card" id="add_files_form" style="display: none">
                            <div class="card-body">
                                <div class="row clearfix">
                                    <div class="col-md-12">


                                        <div class="row">
                                            <input type="hidden" name="app_menu_id" value="47">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('home.attachment_type')</label>
                                                    <select class="form-control" name="attachment_type">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($attachment_types as $attachment_type)
                                                            <option value="{{ $attachment_type->system_code_id }}">
                                                                {{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                         : $attachment_type->system_code_name_en }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('home.issue_date')</label>
                                                    <input class="form-control" type="date" id="issue_date"
                                                           @change="getIssueDate()"
                                                           v-model="issue_date"
                                                           name="issue_date">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('home.issue_date_hijri')</label>
                                                    <input class="form-control" type="text" id="issue_date_hijri"
                                                           v-model="issue_date_hijri"
                                                           name="issue_date_hijri">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('home.copy_no')</label>
                                                    <input class="form-control" type="number" name="copy_no"
                                                           placeholder="@lang('home.copy_no')">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('home.expire_date')</label>
                                                    <input class="form-control" type="date" id="expire_date"
                                                           @change="getExpireDate()"
                                                           v-model="expire_date" name="expire_date">

                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('home.expire_date_hijri')</label>
                                                    <input class="form-control" type="text" name="expire_date_hijri"
                                                           v-model="expire_date_hijri" id="expire_date_hijri">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('home.attachment_data')</label>
                                                    <textarea class="form-control"
                                                              placeholder="@lang('home.attachment_data')"
                                                              name="attachment_data"></textarea>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <div class="card">
                                                        <div class="card-header">

                                                        </div>
                                                        <div class="card-body">
                                                            <input type="file" id="dropify-event"
                                                                   accept=".png, .jpg , .pdf"
                                                                   name="attachment_file_url">
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{------------Practical_notes_grid--------------------------------------------------------------------}}
                    <div class="tab-pane fade" id="notes-grid" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-primary mb-2" id="add_note">
                                <i class="fe fe-plus"></i>
                                @lang('home.add_note')
                            </button>
                        </div>

                        <div class="card" id="add_note_form" style="display: none">


                            <div class="row clearfix">

                                <div class="col-md-12">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="message-text"
                                                           class="col-form-label"> @lang('home.attachment_data')</label>
                                                    <textarea class="form-control" name="notes_data"
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>


                    </div>


                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#add_files').click(function () {
                var display = $("#add_files_form").css("display");
                if (display == 'none') {
                    $('#add_files_form').css('display', 'block')
                } else {
                    $('#add_files_form').css('display', 'none')
                }

            });

            $('#add_note').click(function () {
                var display = $("#add_note_form").css("display");
                if (display == 'none') {
                    $('#add_note_form').css('display', 'block')
                } else {
                    $('#add_note_form').css('display', 'none')
                }
            });
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                date: '',
                car_accident_date_open: '{{Carbon\Carbon::now()->format('d-m-y')}}',
                car_accident_date_open_hejri: ''
            },
            mounted() {
                $('#issue_date_hijri').on("dp.change", (e) => {
                    this.issue_date_hijri = $('#issue_date_hijri').val()
                    this.getGeorgianDate()
                });

                $('#expire_date_hijri').on("dp.change", (e) => {
                    this.expire_date_hijri = $('#expire_date_hijri').val()
                    this.getGeorgianDate2()
                });

                this.getRegisterDateHijri()

            },
            methods: {
                getGeorgianDate() {
                    if (this.issue_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.issue_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.issue_date = response.data
                        })
                    }
                },
                getGeorgianDate2() {
                    if (this.expire_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.expire_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.expire_date = response.data
                        })
                    }
                },
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
                getIssueDateHijri() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date_hijri},
                        url: '{{ route("api.getDate2") }}'
                    }).then(response => {
                        this.issue_date = response.data
                    })
                },

                getRegisterDateHijri() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.car_accident_date_open},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.car_accident_date_open_hejri = response.data
                    })
                }
            }
        })
    </script>

@endsection