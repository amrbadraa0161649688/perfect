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
                <form id="item_data_update_form">
                    @csrf  
                    <input type="hidden" class="form-control" name="uuid" id="uuid" value="{{ $item->uuid }}"> 
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.company')   </label>
                                    <select class="form-select form-control" name="company_id_m" id="company_id_m" disabled>
                                        <option value="auth()->user()->company->company_id" selected> {{ auth()->user()->company->getCompanyName()}}</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_category')   </label>
                                    <select class="form-select form-control" name="item_category" id="item_category" disabled>
                                        @foreach($warehouses_type_lits as $warehouses_type)
                                            <option value="{{$warehouses_type->system_code}}"  {{($item->item_category == $warehouses_type->system_code_id ? 'selected' :  '' )}}>
                                                    {{ $warehouses_type->getSysCodeName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.branch')  </label>
                                    <select class="form-select form-control" name="branch_id_m" id="branch_id_m" disabled>
                                        @foreach($branch_list as $branch)
                                            <option value="{{$branch->branch_id}}" {{($item->branch_id == $branch->branch_id ? 'selected' :  '' )}}>
                                                {{ $branch->getBranchName() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_code') </label>
                                    <input type="text" class="form-control " name="item_code" id="item_code" value="{{$item->item_code}}"  oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_name_a') </label>
                                    <input type="text" class="form-control" name="item_name_a" id="item_name_a" value="{{$item->item_name_a}}" >
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_name_e')   </label>
                                    <input type="text" class="form-control " name="item_name_e" id="item_name_e" value="{{$item->item_name_e}}" >
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_vendor_code')   </label>
                                    <input type="text" class="form-control" name="item_vendor_code" id="item_vendor_code" value="{{$item->item_vendor_code}}" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');">
                                </div>

                                

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('storeItem.item_location')   </label>
                                    <input type="text" class="form-control" name="item_location" id="item_location" value="{{$item->item_location}}">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('storeItem.item_unit')   </label>
                                    <select class="form-select form-control" name="item_unit" id="item_unit" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($unit_lits as $unit)
                                        <option value="{{$unit->system_code}}" {{($item->item_unit == $unit->system_code_id ? 'selected' :  '' )}}> {{ $unit->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('storeItem.item_code_1')  </label>
                                    <input type="text" class="form-control" name="item_code_1" id="item_code_1" value="{{$item->item_code_1}}" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_code_2') </label>
                                    <input type="text" class="form-control" name="item_code_2" id="item_code_2" value="{{$item->item_code_2}}" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,' ');">
                                </div>

                                <div class="col-md-4">
                                    
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_price_sales') </label>
                                    <input type="number" class="form-control" name="item_price_sales" id="item_price_sales" value="{{$item->item_price_sales}}" >
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_price_cost') </label>
                                    <input type="number" class="form-control" name="item_price_cost" id="item_price_cost"  value="{{$item->item_price_cost}}" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_balance') </label>
                                    <input type="number" class="form-control" name="item_balance" id="item_balance" value="{{$item->item_balance}}" >
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_price_mntns') </label>
                                    <input type="number" class="form-control" name="item_price_mntns" id="item_price_mntns"  value="{{$item->item_price_mntns}}" >
                                </div>

                                <div class="col-md-8">
                                    <label for="recipient-name" class="col-form-label"> @lang('storeItem.item_desc')  </label>
                                    <textarea rows="2" class="form-control" name="item_desc" id="item_desc" placeholder="Here can be your note" value="{{$item->item_desc}}">
                                        {{$item->item_desc}}
                                    </textarea>
                                </div>


                            </div>
                        </div>
                    </div>
                    
                </form>
                <div class="card-footer">
                    <button class="btn btn-primary btn-block"  onclick="updateItem()" >  @lang('storeItem.update_button')   </button>
                    <a href="{{ route('store-item.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script type="text/javascript">

    $(document).ready(function () {
        $('#company_id_m').change(function () {
            if (!$('#company_id_m').val()) {
                $('#company_id_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#company_id_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#item_category').change(function () {
            if (!$('#item_category').val()) {
                $('#item_category').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#item_category').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#branch_id_m').change(function () {
            if (!$('#branch_id_m').val()) {
                $('#branch_id_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#branch_id_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });


        $('#item_code').keyup(function () {
            console.log('111');
            if ($('#item_code').val().length < 3) {
                $('#item_code').addClass('is-invalid')
            } else {
                $('#item_code').removeClass('is-invalid');
            }
        });

        $('#item_name_a').keyup(function () {
            if ($('#item_name_a').val().length < 3) {
                $('#item_name_a').addClass('is-invalid')
            } else {
                $('#item_name_a').removeClass('is-invalid');
            }
        });

        $('#item_name_e').keyup(function () {
            if ($('#item_name_e').val().length < 3) {
                $('#item_name_e').addClass('is-invalid')
            } else {
                $('#item_name_e').removeClass('is-invalid');
            }
        });

        $('#item_vendor_code').keyup(function () {
            if ($('#item_vendor_code').val().length < 3) {
                $('#item_vendor_code').addClass('is-invalid')
            } else {
                $('#item_vendor_code').removeClass('is-invalid');
            }
        });

        $('#item_location').keyup(function () {
            if ($('#item_location').val().length < 3) {
                $('#item_location').addClass('is-invalid')
            } else {
                $('#item_location').removeClass('is-invalid');
            }
        });

        $('#item_code_1').keyup(function () {
            if ($('#item_code_1').val().length < 3) {
                $('#item_code_1').addClass('is-invalid')
            } else {
                $('#item_code_1').removeClass('is-invalid');
            }
        });

        $('#item_code_2').keyup(function () {
            if ($('#item_code_2').val().length < 3) {
                $('#item_code_2').addClass('is-invalid')
            } else {
                $('#item_code_2').removeClass('is-invalid');
            }
        });

        $('#item_price_sales').keyup(function () {
            if ($('#item_price_sales').val().length < 1) {
                $('#item_price_sales').addClass('is-invalid')
            } else {
                $('#item_price_sales').removeClass('is-invalid');
            }
        });

        $('#item_price_cost').keyup(function () {
            if ($('#item_price_cost').val().length < 1) {
                $('#item_price_cost').addClass('is-invalid')
            } else {
                $('#item_price_cost').removeClass('is-invalid');
            }
        });

        $('#item_balance').keyup(function () {
            if ($('#item_balance').val().length < 1) {
                $('#item_balance').addClass('is-invalid')
            } else {
                $('#item_balance').removeClass('is-invalid');
            }
        });

        $('#item_unit').change(function () {
            if (!$('#item_unit').val()) {
                $('#item_unit').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#item_unit').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

    });

    function updateItem()
    {
        if($('.is-invalid').length > 0){
            return toastr.warning('تاكد من ادخال كافة الحقول');
        }
        url = '{{ route('store-item.update') }}'
        var form = new FormData($('#item_data_update_form')[0]);

        var data = form  ; 
        $.ajax({
            type: 'POST',
            url : url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            
        }).done(function(data){
            if(data.success)
            {
                toastr.success(data.msg);
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }
</script>
    
@endsection


