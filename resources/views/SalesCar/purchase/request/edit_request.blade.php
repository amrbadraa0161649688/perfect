@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
    <style type="text/css">
		.ctd{
			text-align: center;
            
		}
        .full
        {
            padding-left: 40%;
        }
	</style>
   

@endsection

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
                        <form id="item_data_form"  enctype="multipart/form-data">
                            @csrf  
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="row">
                                    <input type="hidden" class="form-control" name="store_hd_id" id="store_hd_id" value="{{ $sales->store_hd_id }}" >
                                    <input type="hidden" class="form-control" name="sales_uuid" id="sales_uuid" value="{{ $sales->uuid }}" >
                                    <input type="hidden" class="form-control" name="header_page" id="header_page" value="request" >
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales_car.request_no')  </label>
                                            <input type="text" class="form-control" name="store_hd_code" id="store_hd_code" value="{{ $sales->store_hd_code }}" readonly disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label ">  @lang('sales_car.item_category') </label>
                                            <select class="form-select form-control" name="store_category_type" id="store_category_type" disabled>
                                                <option value="" selected> choose</option>  
                                                @foreach($warehouses_type_list as $w_t)
                                                <option value="{{$w_t->system_code}}" {{($sales->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label"> @lang('sales_car.vendor') </label>
                                            <select class="form-select form-control" name="store_acc_no" id="store_acc_no" required>
                                                <option value="" selected> choose</option>  
                                                @foreach($vendor_list as $vendor)
                                                <option value="{{$vendor->customer_id}}" data-vendorname="{{ $vendor->getCustomerName() }}" data-vendorvat="{{ $vendor->customer_vat_no }}" {{($sales->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales_car.request_date')  </label>
                                            <input type="text" class="form-control" name="created_date" id="created_date" value="{{ $sales->created_date->format('Y-m-d H:m') }}" readonly disabled>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales_car.vendor_name') </label>
                                            <input type="text" class="form-control" name="store_acc_name" id="store_acc_name" value="{{$sales->store_acc_name}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label ">  @lang('sales_car.vat_no') </label>
                                            <input type="number" class="form-control" name="store_acc_tax_no" id="store_acc_tax_no" value="{{$sales->store_acc_tax_no}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label"> @lang('sales_car.payment_method')  </label>
                                            <select class="form-select form-control" name="store_vou_pay_type" id="store_vou_pay_type" required>
                                                <option value="" selected> choose</option>    
                                                @foreach($payemnt_method_list as $p_method)
                                                <option value="{{$p_method->system_code}}" {{($sales->store_vou_pay_type == $p_method->system_code_id? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label">  @lang('sales_car.note') </label>
                                            <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes"  value="{{$sales->store_vou_notes }}"> {{$sales->store_vou_notes }}</textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <br>
                                            <br>
                                            <button type="button" onclick="updateHeader()" class="btn btn-primary btn-block">
                                                <i class="fe fe-save mr-2"></i> تحديث
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row card">
                            
                        <div class="col-md-12">
                            <div class="mb-3"> 
                                <br>
                                <div class="table-responsive">
                                    @include('salesCar.purchase.request.item_table')
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer row">
                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <a  href="{{config('app.telerik_server')}}?rpt={{$sales->report_url_pr->report_url}}&id={{$sales->uuid}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport" target="_blank">
                                    {{trans('Print')}}

                            </a>
                        </div>
                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <a  href="{{config('app.telerik_server')}}?rpt={{$sales->report_url_pr_branch->report_url}}&id={{$sales->uuid}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport" target="_blank">
                                    {{trans('Balance Branch')}}

                            </a>
                        </div>
                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                        <a  href="{{config('app.telerik_server')}}?rpt={{$sales->report_url_pr_supp->report_url}}&id={{$sales->uuid}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport" target="_blank">
                                    {{trans('Supplier')}}

                            </a>
                        </div>
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-3">	
                            <a href="{{ route('sales-car-request.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                        </div>	
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
        @if(session('company'))
            var company_id = {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
        @else
            var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif
    </script>

    <script type="text/javascript">
        
        $(document).ready(function () {
            lang = '{{app()->getLocale()}}';
            vat_rate = 15/100;
            $('#brand_id').on('change',function(){
                if($('#brand_id').val()!=='')
                {
                    $('#store_vou_item_price_unit').val(0);
                    $('#store_vou_qnt_r').val(0);
                    $('#store_vou_item_total_price').val(0);
                }
            });

            $('#brand_dt').on('change',function(){
                if($('#brand_dt').val()!=='')
                {
                    $('#store_vou_item_price_unit').val(0);
                    $('#store_vou_qnt_r').val(0);
                    $('#store_vou_item_total_price').val(0);
                }
            });

            $('#store_vou_qnt_r').change(function()  {
                if($('#brand_id').val() == '' || $('#brand_dt').val() == '')
                {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_r').val() * $('#store_vou_item_price_unit').val());
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
            });

            $('#store_vou_item_price_unit').change(function()  {
                if($('#brand_id').val() == '' || $('#brand_dt').val() == '')
                {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if(!$('#store_vou_qnt_r').val())
                {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_r').val() * $('#store_vou_item_price_unit').val());
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
            });

            $('#store_acc_no').on('change',function(){
                $('#store_acc_name').val($('#store_acc_no :selected').data('vendorname'));
                $('#store_acc_name').removeClass('is-invalid');
                $('#store_acc_tax_no').val($('#store_acc_no :selected').data('vendorvat'));
                $('#store_acc_tax_no').removeClass('is-invalid');
            });

            $('#store_acc_no').change(function () {
                if (!$('#store_acc_no').val()) {
                    $('#store_acc_no').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#store_acc_no').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });

            $('#store_vou_pay_type').change(function () {
                if (!$('#store_vou_pay_type').val()) {
                    $('#store_vou_pay_type').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#store_vou_pay_type').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });

            $('#store_acc_name').keyup(function () {
                if ($('#store_acc_name').val().length < 3) {
                    $('#store_acc_name').addClass('is-invalid')
                } else {
                    $('#store_acc_name').removeClass('is-invalid');
                }
            });

            $('#store_acc_tax_no').keyup(function () {
                if ($('#store_acc_tax_no').val().length < 3) {
                    $('#store_acc_tax_no').addClass('is-invalid')
                } else {
                    $('#store_acc_tax_no').removeClass('is-invalid');
                }
            });
        });

        function checkItemInput()
        {
            
            if(
                $('#brand_id').val() == '' || 
                $('#brand_dt').val() == '' || 
                $('#store_vou_qnt_r').val() == ''  ||
                $('#store_vou_item_price_unit').val() == ''  ||
                $('#store_vou_item_total_price').val() == ''  )
                {
                    return false;
                }

            return true;
            
        }

        function saveItemRow()
        {
            if(! checkItemInput())
            {
                return toastr.warning('لا يوجد بيانات لاضافتها');
            }
            tableBody = $("#item_table");
            rowNo = parseFloat($('#item_row_count').val()) + 1; //// ;

            //return  alert(rowNo);
            row_data = {
                'id' : 'tr'+rowNo,
                'count' : rowNo,
                'store_hd_id' : $('#store_hd_id').val(),
                'store_brand_id': parseFloat($('#brand_id').val()),
                'brand_name' : $('#brand_id :selected').data('brandname'),
                'store_brand_dt_id': parseFloat($('#brand_dt').val()),
                'brand_dt_name' : $('#brand_dt :selected').data('branddt').brand_dt_name_en,
                'store_vou_qnt_r' : parseFloat($('#store_vou_qnt_r').val()),
                'store_vou_item_price_cost' :  parseFloat($('#last_price_cost').val()),
                'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                'store_vou_item_total_price' : parseFloat($('#store_vou_item_total_price').val()),
                'last_price_cost' : parseFloat($('#last_price_cost').val()),
                'store_vou_vat_rate' : (15/100),
                'store_vou_vat_amount' : parseFloat($('#store_vou_vat_amount').val()),
                'store_vou_price_net' : parseFloat($('#store_vou_price_net').val()),
            }; 

            console.log(row_data);

            url = '{{ route('store-item-sales-car-request.store') }}';
            var form = new FormData($('#item_data_form')[0]);
            form.append('item_table_data', JSON.stringify(row_data));
            form.append('item_type','request');
            
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
                    updateItemTable(rowNo,row_data,data.uuid,data.total);
                    
				}
				else
				{
					toastr.warning(data.msg);
				}
			});
        }

        function updateItemTable(rowNo,rowData,uuid,total)
        {
            uuid ="'"+uuid+"'";
            markup = 
            '<tr id='+uuid+'>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'count">' +rowData['count'] + '</td>' +
                '<td  class="ctd " id="'+'tr'+rowNo+'brand_name">' +rowData['brand_name'] + '</td>' +
                '<td  class="ctd" id="'+'tr'+rowNo+'brand_dt_name">'+ rowData['brand_dt_name'] +'</td>' +
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_qnt_r">'+ rowData['store_vou_qnt_r'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_price">'+ rowData['store_vou_item_price_unit'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_total_price">'+ rowData['store_vou_item_total_price'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_vat_amount">'+ rowData['store_vou_vat_amount'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_price_net">'+ rowData['store_vou_price_net'] +'</td>'+
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('+ uuid +')"><i class="fa fa-trash"></i></button></td>'+
            '<tr>'+
            '<div id="new_row"></div>';
            tableBody.append(markup); 
            updateTotal(total);
            //reset input 
            $('#brand_id').val('').change();
            $('#add_item_div').find('input').val(''); 
            $('#brand_id').selectpicker('refresh');
        }

        function deleteItem(uuid)
        { 
            var form = new FormData($('#item_data_form')[0]);
			form.append('uuid', uuid);

            $.ajax({
                
				type: 'POST',
				url : "{{route('car-sales.delete')}}",
				data: form,
				cache: false,
				contentType: false,
				processData: false,
               
				
			}).done(function(data){
				if(data.success)
				{
                    toastr.success(data.msg);
                    console.log('return true');
                    console.log(data.data.uuid);
                    $('#'+data.data.uuid).remove();
                    updateTotal(data.total);
                    return 'true';
				}
				else
				{
					toastr.warning(data.msg);
                    return 'false';
				}
			});

            
        }

        function updateTotal(total)
        {
            $('#total_sum_div').text(total['total_sum']);
            $('#total_sum').val(total['total_sum']);

            $('#total_sum_net_div').text(total['total_sum_net']);
            $('#total_sum_net').val(total['total_sum_net']);

            $('#total_sum_vat_div').text(total['total_sum_vat']);
            $('#total_sum_vat').val(total['total_sum_vat']);
        }


        function updateHeader()
        {
            url = '{{ route('car-sales.header.update') }}'
            var form = new FormData($('#item_data_form')[0]);
            //var form  = $('#search_data_form').serialize() ;

            $.ajax({
                type: 'POST',
                url : url,
                data: form,
                cache: false,
				contentType: false,
				processData: false,
                
            }).done(function(data)
            {
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

        // get brnd dt by brand 
        $('#brand_id').on('change',function(){
            //console.log('changed');
            $("#brand_dt option").remove();
            var brand_id = $('#brand_id').val();
            $.ajax({
                url : '{{ route( 'get.brand.dt.by.brand' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "brand_id": brand_id
                    },
                type: 'get',
                dataType: 'json',
                success: function( result )
                {
                    $('#brand_dt').append($('<option>', {value:'', text:'choose'}));
                    $.each( result.data, function(k) {
                        data = result.data;
                        if(lang =='ar')
                        {
                            $('#brand_dt').append(
                                // $('<option>', {value:data[k].brand_dt_id, text: data[k].brand_dt_name_en })
                                $('<option>', 
                                        {
                                            value:data[k].brand_dt_id,
                                            text: data[k].brand_dt_name_ar,
                                            data: { 'branddt' : data[k]  }
                                        }
                                    )
                                
                                );
                        }else{
                            $('#brand_dt').append(
                                // $('<option>', {value:data[k].brand_dt_id, text: data[k].brand_dt_name_en })
                                $('<option>', 
                                        {
                                            value:data[k].brand_dt_id,
                                            text: data[k].brand_dt_name_en,
                                            data: { 'branddt' : data[k]  }
                                        }
                                    )
                                
                                );
                        }
                        $('#brand_dt').selectpicker('refresh');
                    });
                },
                error: function()
                {
                    //handle errors
                    alert('error...');
                }
            });
        });

        qty_input = document.getElementById("store_vou_qnt_r");
        qty_input.addEventListener("keydown", function (e) {
            if (e.code === "Enter") {  //checks whether the pressed key is "Enter"
                
                if($('#brand_id').val() == '')
                {
                    $('#store_vou_qnt_r').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_r').val() * $('#store_vou_item_price_unit').val());
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
                
                saveItemRow();
            }
        });

    </script>
@endsection

