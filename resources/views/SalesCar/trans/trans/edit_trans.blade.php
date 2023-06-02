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
                                    <input type="hidden" class="form-control" name="store_hd_id" id="store_hd_id" value="{{ $sales->store_hd_id }}">
                                    <input type="hidden" class="form-control" name="sales_uuid" id="sales_uuid" value="{{ $sales->uuid }}">
                                    <input type="hidden" class="form-control" name="branch_id" id="branch_id" value="{{ $sales->branch_id }}">   
                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label "> رقم اذن التحويل </label>
                                            <input type="text" class="form-control" name="store_hd_code" id="store_hd_code" value="{{ $sales->store_hd_code }}" readonly disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label "> تاريخ اذن التحويل </label>
                                            <input type="text" class="form-control" name="created_date" id="created_date" value="{{ $sales->created_date->format('Y-m-d H:m') }}" readonly disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label "> الفرع المصدر </label>
                                            <input type="text" class="form-control" name="source_branch" id="source_branch" value="{{ $sales->sourceBranch->getBranchName() }}" readonly disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label "> المستودع المصدر </label>
                                            <input type="text" class="form-control" name="source_store" id="source_store" value="{{ $sales->sourceStore->getSysCodeName() }}" readonly disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label "> الفرع المستلم </label>
                                            <input type="text" class="form-control" name="dest_branch" id="dest_branch" value="{{ $sales->destBranch->getBranchName() }}" readonly disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name" class="col-form-label "> المستودع المستلم  </label>
                                            <input type="text" class="form-control" name="dest_store" id="dest_store" value="{{ $sales->destStore->getSysCodeName() }}" readonly disabled>
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
                                    @include('salesCar.trans.trans.table.item_table')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer row">
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="#" class="btn btn-warning btn-block">طباعة </a>
                        </div>
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                            <a href="{{ route('sales-car-transfer-trans.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
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
            vat_rate = 15/100;
            lang = '{{app()->getLocale()}}';

            $('#store_vou_qnt_o').change(function()  {
                if($('#store_vou_item_id').val() == '')
                {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار السيارة');
                }

                $('#store_vou_item_total_price').val($('#store_vou_qnt_o').val() * (parseFloat($('#store_vou_item_price_unit').val()) + parseFloat($('#sales_cars_add_amount').val())));
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
            });

            $('#store_vou_item_price_unit').change(function()  {
                if($('#store_vou_item_id').val() == '')
                {
                    $('#store_vou_qnt_o').val(1)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if(!$('#store_vou_qnt_o').val())
                {
                    $('#store_vou_qnt_o').val(1)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_o').val() * (parseFloat($('#store_vou_item_price_unit').val()) + parseFloat($('#sales_cars_add_amount').val())));
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
            });

            $('#sales_cars_add_amount').change(function()  {
                if($('#store_vou_item_id').val() == '')
                {
                    $('#sales_cars_add_amount').val(0)
                    return toastr.warning('لابد من اختيار السيارة');
                }
                if(parseFloat($('#sales_cars_add_amount').val()) < 0)
                {
                    $('#sales_cars_add_amount').val(1)
                    return toastr.warning('لابد من ادخال قيمة اضافات اكبر من  او تساوي صفر');
                }
                $('#store_vou_item_total_price').val($('#store_vou_qnt_o').val() * (parseFloat($('#store_vou_item_price_unit').val()) + parseFloat($('#sales_cars_add_amount').val())));
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
            });

            
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
                                                data: { 'branddt' : data[k] , 'branddtname' : data[k].brand_dt_name_ar }
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
                                                data: { 'branddt' : data[k] , 'branddtname' : data[k].brand_dt_name_en  }
                                            }
                                        )
                                    
                                    );
                            }
                        });
                    },
                    error: function()
                    {
                        //handle errors
                        alert('error...');
                    }
                });
            });

            $('#brand_dt').on('change',function(){
                //console.log('changed');
                $("#store_vou_item_id option").remove();
                var brand_dt = $('#brand_dt').val();
                var branch_id = $('#branch_id').val();
                $.ajax({
                    url : '{{ route( 'get.car.by.brand.details' ) }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "brand_dt": brand_dt,
                        "branch_id": branch_id
                        },
                    type: 'get',
                    dataType: 'json',
                    success: function( result )
                    {
                        $('#store_vou_item_id').append($('<option>', {value:'', text:'choose'}));
                        $.each( result.data, function(k) {
                            data = result.data;
                            $('#store_vou_item_id').append(
                                
                                $('<option>', 
                                        {
                                            value : data[k].sales_cars_id,
                                            text: data[k].sales_cars_chasie_no,
                                            data: { 'cardt' : data[k]  }
                                        }
                                    )
                                
                                );
                        });
                    },
                    error: function()
                    {
                        //handle errors
                        alert('error...');
                    }
                });
            });

           

            $('#store_vou_item_id').on('change',function(){
                $('#sales_cars_add_amount').val($('#store_vou_item_id :selected').data('cardt').sales_cars_add_amount);
                $('#store_vou_item_price_unit').val($('#store_vou_item_id :selected').data('cardt').sales_cars_sales_amount);
                $('#store_vou_item_total_price').val($('#store_vou_qnt_o').val() * (parseFloat($('#store_vou_item_price_unit').val()) + parseFloat($('#sales_cars_add_amount').val())));
                $('#store_vou_vat_amount').val($('#store_vou_item_total_price').val() * vat_rate );
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()) );
            });
        });

        function checkItemInput()
        {
            
            if(
                $('#brand_id').val() == '' || 
                $('#brand_dt').val() == '' ||
                $('#store_vou_item_id').val() == '' || 
                $('#store_vou_qnt_o').val() == ''  ||

                $('#store_vou_item_price_unit').val() == ''  ||
                $('#store_vou_item_total_price').val() == ''  ||
                $('#invoice_date_external').val() == ''  )
                {
                    return false;
                }

            return true;
            
        }

        function saveItemRow()
        {
            if(! checkItemInput())
            {
                return toastr.warning('الرجاء التاكد من ادخال كافة الحقول');
            }
            tableBody = $("#item_table");
            rowNo = parseFloat($('#item_row_count').val()) + 1; //// ;

            //return  alert(rowNo);
            row_data = {
                'id' : 'tr'+rowNo,
                'count' : rowNo,
                'store_hd_id' : $('#store_hd_id').val(),
                'store_vou_item_id' : $('#store_vou_item_id').val(),
                'sales_cars_add_amount' : $('#sales_cars_add_amount').val(),

                'sales_cars_chasie_no' :  $('#store_vou_item_id :selected').data('cardt').sales_cars_chasie_no,
                'sales_cars_plate_no' :  $('#store_vou_item_id :selected').data('cardt').sales_cars_plate_no,
                'store_brand_id': parseFloat($('#brand_id').val()),
                'brand_name' : $('#brand_id :selected').data('brandname'),
                'store_brand_dt_id': parseFloat($('#brand_dt').val()),
                'brand_dt_name' : $('#brand_dt :selected').data('branddtname'),

               
                'store_vou_qnt_t_o' : parseFloat($('#store_vou_qnt_o').val()),
                'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                'store_vou_item_total_price' : parseFloat($('#store_vou_item_total_price').val()),

                'store_vou_vat_rate' : (15/100),
                'store_vou_vat_amount' : parseFloat($('#store_vou_vat_amount').val()),
                'store_vou_price_net' : parseFloat($('#store_vou_price_net').val()),
            }; 

            url = '{{ route('sales-car-item-sales-trans.store') }}';
            var form = new FormData($('#item_data_form')[0]);
            form.append('item_table_data', JSON.stringify(row_data));
            form.append('item_type','trans');
            
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
                '<td  class="ctd" id="' + 'tr' + rowNo + 'sales_cars_chasie_no">' + rowData['sales_cars_chasie_no'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'sales_cars_plate_no">' + rowData['sales_cars_plate_no'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'brand_name">' + rowData['brand_name'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'brand_dt_name">' + rowData['brand_dt_name'] + '</td>' +

                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_qnt_t_o">'+ rowData['store_vou_qnt_t_o'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_price">'+ rowData['store_vou_item_price_unit'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'sales_cars_add_amount">'+ rowData['sales_cars_add_amount'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_total_price">'+ rowData['store_vou_item_total_price'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_vat_amount">'+ rowData['store_vou_vat_amount'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_price_net">'+ rowData['store_vou_price_net'] +'</td>'+
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('+ uuid +')"><i class="fa fa-trash"></i></button></td>'+
            '<tr>'+
            '<div id="new_row"></div>';
            tableBody.append(markup); 
            updateTotal(total);
            //reset input 
            $('#brand_dt').val('').change();
            $('#add_item_div').find('input').val(''); 
            $('#store_vou_item_id').change();
        }

        function deleteItem(uuid)
        { 
            var form = new FormData($('#item_data_form')[0]);
			form.append('uuid', uuid);
            url = "{{route('sales-car-transfer.delete')}}";

            $.ajax({
                
				type: 'POST',
				url : '',
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
                    console.log('return dalse');
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

    </script>
@endsection

