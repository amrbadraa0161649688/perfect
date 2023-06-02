@extends('Layouts.master')
@section('content')
    <div class="section-body py-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-6">
                {{-- Basic information --}}
                {{--<div class="tab-pane fade show active" id="data-grid" role="tabpanel">--}}
                <div class="card">
                    <div class="section-body">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center">
                                <ul class="nav nav-tabs page-header-tab">
                                    <li class="nav-item">
                                        <a href="#form-grid" data-toggle="tab"
                                           style="font-size: 18px"
                                           class="nav-link active">@lang('home.data')</a>
                                    </li>

                                    <li class="nav-item"><a class="nav-link"
                                                            href="#photos-grid"
                                                            style="font-size: 18px"
                                                            data-toggle="tab">{{__('Take Photo')}}</a></li>
                                </ul>
                                <div class="header-action"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <form action="{{route('TrucksTrailers.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="tab-content mt-3">

                    {{--   data   --}}
                    <div class="tab-pane fade show active" id="form-grid"
                         role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">{{__('truck connect')}}</h3>
                                <div class="row">

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('company group')}}</label>
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                            $company->companyGroup->company_group_ar :  $company->companyGroup->company_group_en}}"
                                                   readonly>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('company')}}</label>
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                            $company->company_name_ar :  $company->company_name_en}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('employee')}}</label>
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                            auth()->user()->user_name_ar :  auth()->user()->user_name_en}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('date')}}</label>
                                            <input type="text" class="form-control"
                                                   value="{{\Carbon\Carbon::now()->format('d-m-Y')}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">{{__('trucks')}}</label>
                                            <select class="form-control" name="truck_id" v-model="truck_id"
                                                    @change="getTruckDetails()">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($trucks as $truck)
                                                    <option value="{{$truck->truck_id}}">{{$truck->truck_code }}
                                                        ==> {{$truck->truck_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-4" v-if="is_trailer">
                                        <div class="form-group">
                                            <label class="form-label">{{__('trailer')}}</label>
                                            <input type="text" class="form-control" readonly
                                                   :value="trailer.asset_code + ' => ' + trailer.asset_name_ar +' => ' + trailer.asset_serial">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-2" v-if="Object.keys(driver).length">
                                        <div class="form-group">
                                            <label class="form-label">{{__('driver')}}</label>
                                            <input type="text" class="form-control" readonly
                                                   :value="driver.emp_name_full_ar">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-1" v-if="is_trailer">
                                        <div class="form-group">
                                            <br>
                                            <br>
                                            <button type="button" @click="truckDisConnect()"
                                                    class="btn btn-primary">{{__('trailer disconnect')}}</button>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('driver')}}</label>
                                            <select class="form-control" name="driver_id"
                                                    :required="driver_required">
                                                <option value=""></option>
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->emp_id}}">
                                                        {{app()->getLocale() == 'ar' ? $employee->emp_name_full_ar :
                                                        $employee->emp_name_full_en}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6" v-if="have_trailer">
                                        <div class="form-group">
                                            <label class="form-label">{{__('trailers')}}</label>
                                            <select class="form-control" name="trailer_id">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($trailers as $trailer)
                                                    <option value="{{$trailer->asset_id}}">
                                                        {{$trailer->asset_code .'==>' . $trailer->asset_serial . '==>' . $trailer->asset_name_ar}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{__('status')}}</label>
                                            <select class="form-control" name="transaction_type_code"
                                                    v-model="status_code"
                                                    @change="validInput()">
                                                <option value="">@lang('home.choose')</option>
                                                <option v-for="status in statuses" :value="status.system_code">
                                                    @if(app()->getLocale() == 'ar')
                                                        @{{status.system_code_name_ar}}
                                                    @else
                                                        @{{status.system_code_name_en}}
                                                    @endif
                                                </option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{__('notes')}}</label>
                                            <textarea rows="5" class="form-control" name="notes_hd"
                                                      required> </textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th colspan="3">
                                                {{__('check list')}}
                                            </th>
                                        </tr>

                                        </thead>
                                        <thead>
                                        <tr>
                                            <th class="pl-0">#</th>
                                            <th>{{__('title')}}</th>
                                            <th>{{__('notes')}}</th>
                                        </tr>
                                        </thead>

                                        @foreach($items_list as $k=>$item_list)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="check_list_id[]"
                                                           value="{{$item_list->system_code_id}}">
                                                </td>
                                                <td>{{app()->getLocale() == 'ar' ?
                                                $item_list->system_code_name_ar : $item_list->system_code_name_en}}</td>
                                                <td>
                                                    <textarea name="check_list_notes_dt[]"
                                                              class="form-control"></textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--   take photos  --}}
                    <div class="tab-pane fade" id="photos-grid" role="tabpanel">
                        <div class="card">

                            <div class="card-body">

                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <h5>{{__('Take Photo')}}</h5>

                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image[]"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">

                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image[]"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">

                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image[]"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">

                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image[]"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">

                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image[]"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                    </div>


                </div>
            </form>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                truck_id: '',
                trailer: {},
                is_trailer: false,
                truck: {},
                driver: {},
                have_trailer: false,
                statuses: [],
                driver_required: false,
                status_code: ''
            },
            methods: {
                validInput() {
                    if (this.status_code == 152002) {
                        this.driver_required = true
                    }

                    if (this.status_code == 152003) {
                        this.driver_required = true
                    }

                    if (this.status_code == 152001) {
                        this.driver_required = false
                    }
                },
                truckDisConnect() {
                    this.have_trailer = false
                    this.is_trailer = true
                    $.ajax({
                        type: 'GET',
                        data: {
                            truck_id: this.truck_id
                        },
                        url: '{{ route("TrucksTrailers.disConnectTruck") }}'
                    }).then(response => {
                        console.log('success')
                        if (response.status == 200) {
                            this.have_trailer = true
                            this.is_trailer = false
                        }
                    })
                },
                getTruckDetails() {
                    this.trailer = ''
                    this.is_trailer = false
                    this.driver = ''
                    $.ajax({
                        type: 'GET',
                        data: {
                            truck_id: this.truck_id
                        },
                        url: '{{ route("TrucksTrailers.getTruckDetails") }}'
                    }).then(response => {
                        this.truck = response.truck
                        this.statuses = response.statuses
                        this.driver = response.driver ? response.driver : {}

                        if (response.status == 500) {
                            this.trailer = ''
                            this.is_trailer = false
                            this.have_trailer = true
                        }
                        else if (response.status == 200) {
                            this.trailer = response.data

                            this.is_trailer = true
                            this.have_trailer = false
                        }
                    })
                }


            }
        })
    </script>
@endsection