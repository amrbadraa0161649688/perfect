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
                                        <input type="hidden" class="form-control" name="store_hd_id" id="store_hd_id" value="{{ $purchase->store_hd_id }}" >
                                        <input type="hidden" class="form-control" name="purchase_uuid" id="purchase_uuid" value="{{ $purchase->uuid }}" >

                                        <input type="hidden" class="form-control" name="customer_addition_rate" id="customer_addition_rate" value="{{ $purchase->customer->customer_addition_rate }}" >
                                        <input type="hidden" class="form-control" name="customer_discount_rate" id="customer_discount_rate" value="{{ $purchase->customer->customer_discount_rate }}" >
                                        <input type="hidden" class="form-control" name="customer_vat_rate" id="customer_vat_rate" value="{{ $purchase->customer->customer_vat_rate }}" >

                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales.quote_no') </label>
                                            <input type="text" class="form-control" name="store_hd_code" id="store_hd_code" value="{{ $purchase->store_hd_code }}" readonly disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales.warehouse_type') </label>
                                            <select class="form-select form-control" name="store_category_type" id="store_category_type" disabled>
                                                <option value="" selected> choose</option>  
                                                @foreach($warehouses_type_list as $w_t)
                                                <option value="{{$w_t->system_code}}" {{($purchase->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label"> @lang('sales.customer') </label>
                                            <select class="form-select form-control" name="store_acc_no" id="store_acc_no" required disabled>
                                                <option value="" selected> choose</option>  
                                                @foreach($customer as $vendor)
                                                <option value="{{$vendor->customer_id}}" data-vendorname="{{ $vendor->getCustomerName() }}" data-vendorvat="{{ $vendor->customer_vat_no }}" {{($purchase->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales.date') </label>
                                            <input type="text" class="form-control" name="created_date" id="created_date" value="{{ $purchase->created_date->format('Y-m-d H:m') }}" readonly disabled>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label ">@lang('sales.customer_name')  </label>
                                            <input type="text" class="form-control" name="store_acc_name" id="store_acc_name" value="{{$purchase->store_acc_name}}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales.tax_no') </label>
                                            <input type="number" class="form-control" name="store_acc_tax_no" id="store_acc_tax_no" value="{{$purchase->store_acc_tax_no}}" readonly >
                                        </div>
                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label "> @lang('sales.mobile_no') </label>
                                            <input type="number" class="form-control" name="store_vou_ref_after" id="store_vou_ref_after" value="{{$purchase->store_vou_ref_after}}" readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name" class="col-form-label"> @lang('sales.payment_method')  </label>
                                            <select class="form-select form-control" name="store_vou_pay_type" id="store_vou_pay_type" required>
                                                <option value="" selected> choose</option>    
                                                @foreach($payemnt_method_list as $p_method)
                                                <option value="{{$p_method->system_code}}" {{($purchase->store_vou_pay_type == $p_method->system_code_id? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="recipient-name" class="col-form-label"> @lang('sales.note')  </label>
                                            <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value=""> {{$purchase->tore_vou_notes }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <br>
                                            <br>
                                            <!--   <button type="button" onclick="" class="btn btn-primary btn-block">
                                                <i class="fe fe-save mr-2"></i> تحديث
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row card">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#search_item_modal">
                            <i class="fe fe-search mr-2"></i>  عرض الاصناف 
                        </button>
                        <div class="col-md-12">
                            <div class="mb-3"> 
                                <br>
                                <div class="table-responsive">
                                    @include('store.sales.quote.table.item_table')
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer row">
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                        <a  href="{{config('app.telerik_server')}}?rpt={{$purchase->report_url_q->report_url}}&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                    title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport" target="_blank">
                                    {{trans('Print')}}

                            </a>
                        </div>
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                            <a href="{{ route('store-sales-quote.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                        </div>	
                    </div>
                </div>
                <br>
            </div>
        </div>
        @include('store.search.search_item') 
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
        
        function getNum(number)
        {
            return parseFloat(number).toFixed(2);
        }

        $(document).ready(function () {
            //vat_rate = 15/100;
            vat_rate = $('#customer_vat_rate').val();
            customer_addition_rate = $('#customer_addition_rate').val();
            customer_discount_rate = $('#customer_discount_rate').val();

            $('#item_id').on('change',function(){
                $('#store_vou_qnt_o').val('');
                $('#store_vou_item_total_price').val('');
                $('#store_vou_vat_amount').val('' );
                $('#store_vou_price_net').val('' );
                $('#store_vou_disc_amount').val( '' );

                if($('#item_id').val()!=='')
                {
                    item = $('#item_id :selected').data('item');
                    $('#store_vou_item_code').val($('#item_id :selected').data('itemname'));
                    $('#item_balance').val($('#item_id :selected').data('balance'));
                    $('#store_vou_item_price_unit').val(getNum(calculateItemPriceByAdditionRate(item.item_price_sales,customer_addition_rate)));
                    $('#item_price_cost').val(item.item_price_sales);
                    $('#item_price_sales').val(item.item_price_sales);
                    $('#last_price_cost').val(item.item_price_sales);

                    $('#discount_rate').val(customer_discount_rate * 100);
                }
            });

            $('#store_vou_qnt_o').change(function()  {
                if($('#item_id').val() == '')
                {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum( ($('#store_vou_item_price_unit').val()) - getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(),customer_discount_rate))) );
               
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate)) ;
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())) );
                $('#store_vou_disc_amount').val( getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(),customer_discount_rate)) );
            });

            $('#store_vou_item_price_unit').change(function()  {
                if($('#item_id').val() == '')
                {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if(!$('#store_vou_qnt_o').val())
                {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum( ($('#store_vou_item_price_unit').val() - calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(),customer_discount_rate))) );
                $('#store_vou_vat_amount').val(getNum( $('#store_vou_item_total_price').val() * vat_rate) ) ;
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())) );
                $('#store_vou_disc_amount').val( getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(),customer_discount_rate)) );
            });
        });

        function calculateItemPriceByAdditionRate($price,$rate)
        {
            result = parseFloat($price) + (parseFloat($price) * parseFloat($rate));
            return result;
        }

        function calculateItemPriceByDiscountRate($price,$rate)
        {
            $result = (parseFloat($price) * parseFloat($rate));
            return  $result;
        }


        function checkItemInput()
        {
            
            if(
                $('#item_id').val() == '' || 
                $('#store_vou_item_code').val() == ''  || 
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
                return toastr.warning('لا يوجد بيانات لاضافتها');
            }
            tableBody = $("#item_table");
            rowNo = parseFloat($('#item_row_count').val()) + 1; //// ;

            //return  alert(rowNo);
            row_data = {
                'id' : 'tr'+rowNo,
                'count' : rowNo,
                'store_hd_id' : $('#store_hd_id').val(),
                'store_vou_item_id': parseFloat($('#item_id').val()),
                'store_vou_item_code' : $('#item_id :selected').data('item').item_code,
                'store_vou_loc' : $('#item_id :selected').data('item').item_location,
                'store_vou_qnt_o' : parseFloat($('#store_vou_qnt_o').val()),
                'store_vou_item_price_cost' :  $('#item_id :selected').data('item').item_price_cost,
                'store_vou_item_price_sales' :  parseFloat($('#last_price_cost').val()),
                'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                'item_balance' : parseFloat($('#item_balance').val()),
                'store_vou_item_total_price' : parseFloat($('#store_vou_item_total_price').val()),
                'last_price_cost' : parseFloat($('#last_price_cost').val()),
                'store_vou_vat_rate' : (vat_rate),
                'store_vou_vat_amount' : parseFloat($('#store_vou_vat_amount').val(),2),
                'store_vou_price_net' : parseFloat($('#store_vou_price_net').val()),
                'store_vou_disc_amount' : parseFloat($('#store_vou_disc_amount').val()),
                'store_voue_disc_value' : parseFloat(customer_discount_rate),
                'store_vou_disc_type' : 533,
            }; 

            url = '{{ route('store-item-sales-quote.store') }}';
            var form = new FormData($('#item_data_form')[0]);
            form.append('item_table_data', JSON.stringify(row_data));
            form.append('item_type','quote');
            
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
                '<td  class="ctd " id="'+'tr'+rowNo+'store_vou_item_code">' +rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_code">'+ rowData['store_vou_item_code'] +'</td>' +
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_qnt_o">'+ rowData['store_vou_qnt_o'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_price">'+ rowData['store_vou_item_price_unit'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_disc_amount">'+ rowData['store_vou_disc_amount'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_item_total_price">'+ rowData['store_vou_item_total_price'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_vat_amount">'+ rowData['store_vou_vat_amount'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'store_vou_price_net">'+ rowData['store_vou_price_net'] +'</td>'+
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('+ uuid +')"><i class="fa fa-trash"></i></button></td>'+
            '<tr>'+
            '<div id="new_row"></div>';
            tableBody.append(markup); 
            updateTotal(total);
            //reset input 
            $('#item_id').val('').change();
            $('#add_item_div').find('input').val('');
            $('#store_vou_qnt_o').val('');
                $('#store_vou_item_total_price').val('');
                $('#store_vou_vat_amount').val('' );
                $('#store_vou_price_net').val('' );
                $('#store_vou_disc_amount').val( '' ); 
            $('#item_id').selectpicker('refresh');
        }

        function deleteItem(uuid)
        { 
            var form = new FormData($('#item_data_form')[0]);
			form.append('uuid', uuid);

            $.ajax({
                
				type: 'POST',
				url : "{{route('store-purchase.delete')}}",
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


        function getSearchResult()
        {
            url = '{{ route('store-item.search') }}'
            $('#searchData').html('');
            var form  = $('#search_data_form').serialize() ;

            $.ajax({
                type: 'GET',
                url : url,
                data: form,
                dataType: 'json',
                
            }).done(function(data)
            {
                if(data.success)
                {
                    toastr.success(data.msg);
                    $('#searchData').html(data.view);
                    
                }
                else
                {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>
@endsection

