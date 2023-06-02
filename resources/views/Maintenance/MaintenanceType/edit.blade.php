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
                <form class="card" id="validate-form" action="{{ route('maintenance-type.update') }}" method="post" enctype="multipart/form-data" id="submit_user_form">
                <input type="hidden" class="form-control is-invalid" name="uuid" id="uuid" value="{{ $maintenance_type->uuid }}">
                @csrf    
                <div class="card-header">
                @lang('maintenanceType.edit_new_mntns_type')
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('maintenanceType.mntns_card_type') </label>
                                            <select class="form-select form-control" name="mntns_card_type" id="mntns_card_type" required>
                                            <option value="" selected> choose</option>    
                                            @foreach($card_list as $card)
                                                <option value="{{$card->system_code}}" {{ ($maintenance_type->mntns_card_type != $card->system_code_id ? '' : 'selected') }}  > {{$card->system_code_name_ar}} - {{$card->system_code_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('maintenanceType.mntns_type') </label>
                                            <select class="form-select form-control" name="mntns_type" id="mntns_type" required>
                                           
                                            @foreach($type_cat as $mt)
                                                <option value="{{$mt->system_code}}" {{ ($maintenance_type->mntns_type_category != $mt->system_code_id ? '' : 'selected') }}> {{$mt->system_code_name_ar}} - {{$mt->system_code_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_type_name_ar')   </label>
                                            <input type="text" class="form-control" name="mntns_type_name_ar"
                                                       id="mntns_type_name_ar" placeholder="@lang('maintenanceType.mntns_type_name_ar')"
                                                       v-model="mntns_type_name_ar"  value="{{$maintenance_type->mntns_type_name_ar}}"
                                                       oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                       required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_type_name_en')   </label>
                                            <input type="text" class="form-control" name="mntns_type_name_en"
                                                       id="mntns_type_name_en" placeholder="@lang('maintenanceType.mntns_type_name_en')"
                                                       v-model="mntns_type_name_en"  value="{{$maintenance_type->mntns_type_name_en}}"
                                                       oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                       required>
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_type_code')   </label>
                                            <input type="number"  class="form-control" name="mntns_type_code"
                                                id="mntns_type_code" placeholder="@lang('maintenanceType.mntns_type_code')"
                                                v-model="mntns_type_code"  value="{{$maintenance_type->mntns_type_code}}"
                                                oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('maintenanceType.mntns_type_hours') </label>
                                            <input type="text" class="form-control" name="mntns_type_hours"
                                                id="mntns_type_hours" placeholder="@lang('maintenanceType.mntns_type_hours')"
                                                v-model="mntns_type_hours" step="3600000" value="{{$maintenance_type->mntns_type_hours}}"
                                                oninput="this.value=this.value.replace( /[^0-9-:]/g,' ');" required>
                                                <label for="recipient-name" class="col-form-label "> صيغة الوقت مثلا 01:20</label>
                                                
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label"> @lang('maintenanceType.mntns_type_emp_no')  </label>
                                            <input type="number" class="form-control" name="mntns_type_emp_no"
                                                id="mntns_type_emp_no" placeholder="@lang('maintenanceType.mntns_type_emp_no')"
                                                v-model="mntns_type_emp_no" value="{{$maintenance_type->mntns_type_emp_no}}"
                                                oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label">  @lang('maintenanceType.mntns_type_value')  </label>
                                            <input type="number" class="form-control" name="mntns_type_value"
                                                    id="mntns_type_value" placeholder="@lang('maintenanceType.mntns_type_value')"
                                                    v-model="mntns_type_value" value="{{$maintenance_type->mntns_type_value}}"
                                                    oninput="this.value=this.value.replace( /[^0-9]/g,' ');"
                                                    required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('maintenance-type.index') }}" class="btn btn-secondary"> @lang('maintenanceType.back_button') </a>
                        <button type="submit" class="btn btn-primary mr-2" data-bs-dismiss="modal" id="create_emp">@lang('maintenanceType.save')</button>
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
            $('#mntns_type_name_ar').keyup(function () {
                if ($('#mntns_type_name_ar').val().length < 3) {
                    $('#mntns_type_name_ar').addClass('is-invalid')
                } else {
                    $('#mntns_type_name_ar').removeClass('is-invalid');
                }
            });

            $('#mntns_type_name_en').keyup(function () {
                if ($('#mntns_type_name_en').val().length < 3) {
                    $('#mntns_type_name_en').addClass('is-invalid')
                } else {
                    $('#mntns_type_name_en').removeClass('is-invalid');
                }
            });

            $('#mntns_type').change(function () {
                if (!$('#mntns_type').val()) {
                    $('#mntns_type').addClass('is-invalid')
                    
                } else {
                    $('#mntns_type').removeClass('is-invalid')
                    
                }
            });

            $('#mntns_type_code').keyup(function () {
                if (!$('#mntns_type_code').val()) {
                    $('#mntns_type_code').addClass('is-invalid')
                } else {
                    $('#mntns_type_code').removeClass('is-invalid')
                }
            });

            $('#mntns_card_type').change(function () {
                if (!$('#mntns_card_type').val()) {
                    $('#mntns_card_type').addClass('is-invalid')
                    
                } else {
                    $('#mntns_card_type').removeClass('is-invalid')
                    
                }
            });

            $('#mntns_type_hours').keyup(function () {
                if ($('#mntns_type_hours').val().length < 5 && $('#mntns_type_hours').val().length > 5)  {
                    $('#mntns_type_hours').addClass('is-invalid')
                } else {
                    $('#mntns_type_hours').removeClass('is-invalid')
                }
            });


            $('#mntns_type_emp_no').keyup(function () {
                if (!$('#mntns_type_emp_no').val()) {
                    $('#mntns_type_emp_no').addClass('is-invalid')
                } else {
                    $('#mntns_type_emp_no').removeClass('is-invalid')
                }
            });

            $('#mntns_type_value').keyup(function () {
                if (!$('#mntns_type_value').val()) {
                    $('#mntns_type_value').addClass('is-invalid')
                } else {
                    $('#mntns_type_value').removeClass('is-invalid')
                }
            });



            
        })
    </script>

    
    
@endsection

