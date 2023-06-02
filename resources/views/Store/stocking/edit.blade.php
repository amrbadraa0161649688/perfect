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
                                        <input type="hidden" class="form-control" name="store_stocking_hd_id" id="store_stocking_hd_id" value="{{ $stocking->store_stocking_hd_id }}" >
                                        <input type="hidden" class="form-control" name="stocking_uuid" id="stocking_uuid" value="{{ $stocking->uuid }}" >
                                        <input type="hidden" class="form-control" name="item" id="item" value="" >
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('stocking.stocking_no') </label>
                                            <input type="text" class="form-control" name="store_hd_code" id="store_hd_code" value="{{ $stocking->store_hd_code }}" readonly disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('stocking.stocking_date') </label>
                                            <input type="text" class="form-control" name="store_vou_date" id="store_vou_date" value="{{ $stocking->getVouDate() }}" readonly disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('stocking.branch') </label>
                                            <input type="text" class="form-control" name="branch_id" id="branch_id" value="{{ $stocking->branch->getBranchName() }}" readonly disabled>
                                            <input type="hidden" class="form-control" name="branch" id="branch" value="{{ $stocking->branch_id }}" readonly disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('stocking.item_category') </label>
                                            <input type="text" class="form-control" name="store_category_type" id="store_category_type" value="{{ $stocking->storeCategory->getSysCodeName() }}" readonly disabled>
                                            <input type="hidden" class="form-control" name="store_category_type_id" id="store_category_type_id" value="{{ $stocking->store_category_type }}" readonly disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('stocking.location') </label>
                                            <input type="text" class="form-control" name="store_location" id="store_location" value="{{ $stocking->store_location }}" readonly disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label">@lang('stocking.note') </label>
                                            <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value=""> {{$stocking->tore_vou_notes }}</textarea>
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
                                    @include('store.stocking.table.item_table')
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer row">
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="#" class="btn btn-warning btn-block">طباعة </a>
                        </div>
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                            <a href="{{ route('store-stocking.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
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
            $('#item_id').on('change',function(){
                getDataByCode('x');
                // if($('#item_id').val()!=='')
                // {
                //     item = $('#item_id :selected').data('item');
                //     $('#item_location').val(item.item_location);
                //     $('#item_name_a').val(item.item_name_a);
                //     $('#item_name_e').val(item.item_name_e);
                // }
            });

        });

        function checkItemInput()
        {
            
            if(
                $('#item_id').val() == '' || 
                $('#store_vou_qnt').val() == '')
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
                'store_stocking_hd_id' : $('#store_stocking_hd_id').val(),
                'store_vou_item_id': parseFloat($('#item').val()),
                'store_vou_item_code' : $('#item_id').val(),
                'store_vou_loc' : $('#item_location').val(),
                'store_vou_qnt' : $('#store_vou_qnt').val(),
                
            }; 

            url = '{{ route('store-stocking.add.item') }}';
            var form = new FormData($('#item_data_form')[0]);
            form.append('item_table_data', JSON.stringify(row_data));
            
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
                    $('table#item_table tr#showResult').remove();
                    $('#item_table tr:last').after(data.view);
                    $('#item_id').val('');
                    $('#store_vou_qnt').val(1);
                    $('#item').val('');
                    $('#item_location').val('');
                    $('#item_name_a').val('');
                    $('#item_name_e').val('');
				}
				else
				{
					toastr.warning(data.msg);
				}
			});
        }

        function getDataByCode(ele)
        {
            item_code = $('#item_id').val();
            url = '{{ route('get.item.by.code') }}'
           
            $.ajax({
                type: 'GET',
                url : url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    store_category_type : $('#store_category_type_id').val(),
                    branch : $('#branch').val(),
                    item_code : item_code,
                },
                dataType: 'json',
                
            }).done(function(data)
            {
                if(data.success)
                {
                    toastr.success(data.msg);
                    item = data.data;
                    $('#item').val(item.item_id);
                    $('#item_location').val(item.item_location);
                    $('#item_name_a').val(item.item_name_a);
                    $('#item_name_e').val(item.item_name_e);
                    saveItemRow();
                    
                }
                else
                {
                    toastr.warning(data.msg);
                }
            });
        }

        function updateQty(uuid)
        {
            qty = $('#stocking_qty_'+uuid).val();
            stocking_item_location = $('#stocking_item_location_'+uuid).val();
           
            var form = new FormData($('#item_data_form')[0]);
            form.append('item_uuid', uuid);
            form.append('item_qty', parseFloat(qty)); 
            form.append('stocking_item_location',stocking_item_location);

            $.ajax({
				type: 'POST',
				url : "{{route('store-stocking.update.stocking.qty')}}",
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

        function deleteItem(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-stocking.delete')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    $('table#item_table tr#showResult').remove();
                    $('#item_table tr:last').after(data.view);
                }
                else {
                    toastr.warning(data.msg);
                }
            });


        }

    </script>
@endsection

