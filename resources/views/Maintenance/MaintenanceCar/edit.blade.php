@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}

@endsection

@section('content')


<div class="section-body mt-3" id="app">
    <div class="container-fluid">
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active " id="data-grid" role="tabpanel">
                <form class="card" id="validate-form" action="{{ route('maintenance-car.update') }}" method="post" enctype="multipart/form-data" id="submit_user_form">
                @csrf   
                <input type="hidden" class="form-control is-invalid" name="uuid" id="uuid" value="{{ $maintenance_car->uuid }}"> 
                <div class="card-header">
                        @lang('maintenanceCar.maintenance_car_edit')
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('maintenanceCar.customer_id') </label>
                                            <select class="form-select form-control is-invalid" name="customer_id" id="customer_id" required>
                                            <option value="" selected> choose</option>    
                                                @foreach($customer as $cus)
                                                <option value="{{$cus->customer_id}}" {{($maintenance_car->customer_id == $cus->customer_id ? 'selected' : '')}} > {{$cus->getCustomerName()}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_brand_id')   </label>
                                            <select class="form-select form-control is-invalid" name="mntns_cars_brand_id" id="mntns_cars_brand_id" required>
                                                <option value="" selected> choose</option>    
                                                @foreach($brand as $br)
                                                <option value="{{$br->system_code_id}}" {{($maintenance_car->mntns_cars_brand_id == $br->system_code_id ? 'selected' : '')}}> {{$br->getSysCodeName()}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_plate_no')   </label>
                                            <input type="text" class="form-control is-invalid" name="mntns_cars_plate_no"
                                                id="mntns_cars_plate_no" placeholder="@lang('maintenanceCar.mntns_cars_plate_no')"
                                                v-model="mntns_cars_plate_no"
                                                oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي-0-9\s]/g,' ');"
                                                value="{{ $maintenance_car->mntns_cars_plate_no }}"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_chasie_no')   </label>
                                            <input type="text"  class="form-control is-invalid" name="mntns_cars_chasie_no"
                                                id="mntns_cars_chasie_no" placeholder="@lang('maintenanceCar.mntns_cars_chasie_no')"
                                                v-model="mntns_cars_chasie_no"
                                                oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');"
                                                value="{{ $maintenance_car->mntns_cars_chasie_no }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('maintenanceCar.mntns_cars_type') </label>
                                            <input type="text" class="form-control is-invalid" name="mntns_cars_type"
                                                id="mntns_cars_type" placeholder="@lang('maintenanceCar.mntns_cars_type')"
                                                v-model="mntns_cars_type" step="3600000"
                                                oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي\s]/g,' ');" value="{{ $maintenance_car->mntns_cars_type }}" required>
                                                
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label"> @lang('maintenanceCar.mntns_cars_model')  </label>
                                            <input type="number" class="form-control is-invalid" name="mntns_cars_model"
                                                id="mntns_cars_model" placeholder="@lang('maintenanceCar.mntns_cars_model')"
                                                v-model="mntns_cars_model"
                                                oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                value="{{ $maintenance_car->mntns_cars_model }}"
                                                required>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_color')  </label>
                                            <input type="text" class="form-control is-invalid" name="mntns_cars_color"
                                                    id="mntns_cars_color" placeholder="@lang('maintenanceCar.mntns_cars_color')"
                                                    v-model="mntns_cars_color"
                                                    oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي\s]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_color }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_meter')  </label>
                                            <input type="number" class="form-control is-invalid" name="mntns_cars_meter"
                                                    id="mntns_cars_meter" placeholder="@lang('maintenanceCar.mntns_cars_meter')"
                                                    v-model="mntns_cars_meter"
                                                    oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_meter }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_owner')  </label>
                                            <input type="text" class="form-control is-invalid" name="mntns_cars_owner"
                                                    id="mntns_cars_owner" placeholder="@lang('maintenanceCar.mntns_cars_owner')"
                                                    v-model="mntns_cars_owner"
                                                    oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي\s]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_owner }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_driver')  </label>
                                            <input type="text" class="form-control is-invalid" name="mntns_cars_driver"
                                                    id="mntns_cars_driver" placeholder="@lang('maintenanceCar.mntns_cars_driver')"
                                                    v-model="mntns_cars_driver"
                                                    oninput="this.value=this.value.replace(/[^A-Za-z-ء-ي\s]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_driver }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_mobile_no')  </label>
                                            <input type="number" class="form-control is-invalid" name="mntns_cars_mobile_no"
                                                    id="mntns_cars_mobile_no" placeholder="@lang('maintenanceCar.mntns_cars_mobile_no')"
                                                    v-model="mntns_cars_mobile_no"
                                                    oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_mobile_no }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_address')  </label>
                                            <input type="text" class="form-control is-invalid" name="mntns_cars_address"
                                                    id="mntns_cars_address" placeholder="@lang('maintenanceCar.mntns_cars_address')"
                                                    v-model="mntns_cars_address"
                                                    oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_address }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceCar.mntns_cars_vat_no')  </label>
                                            <input type="number" class="form-control is-invalid" name="mntns_cars_vat_no"
                                                    id="mntns_cars_vat_no" placeholder="@lang('maintenanceCar.mntns_cars_vat_no')"
                                                    v-model="mntns_cars_vat_no"
                                                    oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                    value="{{ $maintenance_car->mntns_cars_vat_no }}"
                                                    required>
                                        </div>
                                        <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_card_plate') </label>
                                    <select class="selectpicker show-tick form-control" name="car_cost_center" id="car_cost_center" data-live-search="true" >
                                    <option value="" selected> choose</option>  
                                        @foreach($trucks as $mct)
                                        <option value="{{$mct->truck_id}}" >{{ $mct->truck_code }} -- {{ $mct->truck_name }} -- {{ $mct->truck_plate_no }} </option>
                                        @endforeach
                                    </select>
                                    </select>

                                        
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('maintenance-car.index') }}" class="btn btn-secondary"> @lang('maintenanceCar.back_button') </a>
                        <button type="submit" class="btn btn-primary mr-2" data-bs-dismiss="modal" id="create_car">@lang('maintenanceCar.save')</button>
                       
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


    <script type="text/javascript">
        @if(session('company'))
            var company_id = {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
        @else
            var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif

    </script>

    <script>
        
        $(document).ready(function () {
            function show(el) {
                var x = el.id;
                $("#app-" + x).css("display", "block");
                $("#app-" + x).siblings().css('display', 'none')
            }


            //    validation 
            $('#customer_id').change(function () {
                if (!$('#customer_id').val()) {
                    $('#customer_id').addClass('is-invalid')
                    
                } else {
                    $('#customer_id').removeClass('is-invalid')
                    
                }
            });

            $('#mntns_cars_brand_id').change(function () {
                console.log($('#mntns_cars_brand_id').val());
                if (!$('#mntns_cars_brand_id').val()) {
                    $('#mntns_cars_brand_id').addClass('is-invalid')
                } else {
                    $('#mntns_cars_brand_id').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_plate_no').keyup(function () {
                if ($('#mntns_cars_plate_no').val().length < 6)  {
                    $('#mntns_cars_plate_no').addClass('is-invalid')
                } else {
                    $('#mntns_cars_plate_no').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_chasie_no').keyup(function () {
                if ($('#mntns_cars_chasie_no').val().length < 6)  {
                    $('#mntns_cars_chasie_no').addClass('is-invalid')
                } else {
                    $('#mntns_cars_chasie_no').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_type').keyup(function () {
                if (!$('#mntns_cars_type').val()) {
                    $('#mntns_cars_type').addClass('is-invalid')
                } else {
                    $('#mntns_cars_type').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_model').keyup(function () {
                if ($('#mntns_cars_model').val().length < 4)  {
                    $('#mntns_cars_model').addClass('is-invalid')
                } else {
                    $('#mntns_cars_model').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_color').keyup(function () {
                if ($('#mntns_cars_color').val().length < 3)  {
                    $('#mntns_cars_color').addClass('is-invalid')
                } else {
                    $('#mntns_cars_color').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_meter').keyup(function () {
                if ($('#mntns_cars_meter').val().length < 3)  {
                    $('#mntns_cars_meter').addClass('is-invalid')
                } else {
                    $('#mntns_cars_meter').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_owner').keyup(function () {
                if ($('#mntns_cars_owner').val().length < 3)  {
                    $('#mntns_cars_owner').addClass('is-invalid')
                } else {
                    $('#mntns_cars_owner').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_driver').keyup(function () {
                if ($('#mntns_cars_driver').val().length < 3)  {
                    $('#mntns_cars_driver').addClass('is-invalid')
                } else {
                    $('#mntns_cars_driver').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_address').keyup(function () {
                if ($('#mntns_cars_address').val().length < 3)  {
                    $('#mntns_cars_address').addClass('is-invalid')
                } else {
                    $('#mntns_cars_address').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_mobile_no').keyup(function () {
                if ($('#mntns_cars_mobile_no').val().length < 9)  {
                    $('#mntns_cars_mobile_no').addClass('is-invalid')
                } else {
                    $('#mntns_cars_mobile_no').removeClass('is-invalid')
                }
            });

            $('#mntns_cars_vat_no').keyup(function () {
                if ($('#mntns_cars_vat_no').val().length < 3)  {
                    $('#mntns_cars_vat_no').addClass('is-invalid')
                } else {
                    $('#mntns_cars_vat_no').removeClass('is-invalid')
                }
            });

        });
    </script>
@endsection

