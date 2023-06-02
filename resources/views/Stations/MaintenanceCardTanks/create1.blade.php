@extends('Layouts.master')

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
                            <form action="{{route('maintenanceCardTanks.store')}}" id="card_data_form"
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

                                            <div class="col-md-6">
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

                                            <div class="col-md-6">
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

                                        <div class="row">
                                            {{--assets--}}
                                            <div class="col-md-6">
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


                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('maintenanceType.mntns_card_cost') </label>
                                                <input type="number" step=".001"
                                                       class="form-select form-control"
                                                       name="mntns_cards_total_amount"
                                                       required>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('maintenanceType.mntns_notes') </label>
                                                <textarea class="form-control" name="mntns_cards_notes"
                                                          required></textarea>
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
                        url: '{{ route("maintenanceCardTanks.getMaintenanceTypes") }}'
                    }).then(response => {
                        this.cards_work_types = response.data
                        this.mntns_cars = response.mntns_cars
                    })
                }
            }
        })
    </script>
@endsection