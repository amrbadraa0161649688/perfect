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
                        <form id="stocking_data_form"  enctype="multipart/form-data">
                            @csrf  
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <input type="hidden" class="form-control" name="store_stocking_hd_id" id="store_stocking_hd_id" value="{{ $stocking->store_stocking_hd_id }}" >
                                        <input type="hidden" class="form-control" name="stocking_uuid" id="stocking_uuid" value="{{ $stocking->uuid }}" >
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
                                        </div>

                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> @lang('stocking.item_category') </label>
                                            <input type="text" class="form-control" name="store_category_type" id="store_category_type" value="{{ $stocking->storeCategory->getSysCodeName() }}" readonly disabled>
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
                                    <table class="table table-bordered card_table" id="item_table">
                                        <tbody >
                                            
                                            <tr>
                                                <th class="ctd table-active">No</th>
                                                <th class="ctd table-active" style="width:15%" > @lang('stocking.item_code') </th>
                                                <th class="ctd table-active" style="width:15%"> @lang('stocking.item') </th>
                                                <th class="ctd table-active"> @lang('stocking.location') </th>
                                                <th class="ctd table-active"> @lang('stocking.stocking_qty') </th>
                                                <th class="ctd table-active"> @lang('stocking.item_balance')  </th>
                                                <th class="ctd table-active"> @lang('stocking.addition_qty')  </th>
                                                <th class="ctd table-active"> @lang('stocking.lossing_qty') </th>
                                                <th class="ctd table-active" style="width:15%"> @lang('stocking.action') </th>
                                                
                                            </tr>
                                            <?php 
                                                $item_row_count = 0;
                                                $details = $stocking->details;
                                            ?>
                                            <div id="showResult">
                                                @foreach($stocking->details as $key => $d)
                                                    @if($d->store_vou_qnt != $d->store_vou_balance )
                                                    <?php 
                                                        $item_row_count =  floatval($item_row_count) + 1;
                                                    ?>
                                                    <tr id="showResult">
                                                        <td class="ctd"> {{ $key + 1 }} </td>
                                                        <td class="ctd"> {{$d->item->item_code}}  </td>
                                                        <td class="ctd"> {{$d->item->item_name_e}} <br> {{$d->item->item_name_a}}  </td>
                                                        <td class="ctd"> {{$d->store_vou_loc}}  </td>
                                                        <td class="ctd"> {{$d->store_vou_qnt}}  </td>
                                                        <td class="ctd"> {{$d->store_vou_balance}}  </td>

                                                        <td class="ctd"> {{$d->store_vou_qnt_add}}  </td>
                                                        <td class="ctd"> {{$d->store_vou_qnt_desc}}  </td>
                                                        <td class="ctd">   
                                                            @if($d->store_vou_balance < $d->store_vou_qnt)
                                                                <button type="button" class="btn btn-primary">
                                                                    @lang('stocking.add_receive_perm')
                                                                </button>
                                                            @else
                                                            <button type="button" class="btn btn-primary">
                                                                    @lang('stocking.add_sale_inv')
                                                            </button>
                                                            @endif
                                                        </td>
                                                        
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <input type="hidden" class="form-control" name="item_row_count" id="item_row_count" value="{{ $item_row_count }}" >
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-footer row">
                        
                        @if($stocking->store_vou_status == 'NEW')
                        <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4">
                            <button" class="btn btn-secondary btn-block" onclick="stocking()"> تسوية الجرد </button>
                        </div>
                        <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4">
                            <a href="#" class="btn btn-warning btn-block">طباعة </a>
                        </div>
                        <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4">	
                            <a href="{{ route('store-stocking.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                        </div>
                        @else
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                            <a href="#" class="btn btn-warning btn-block">طباعة </a>
                        </div>
                        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                            <a href="{{ route('store-stocking.index') }}" class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                        </div>
                        @endif	
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
           console.log('done');

        });

        function stocking(type)
        {
            if($('.is-invalid').length > 0){
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            url = '{{ route('store-stocking.stocking') }}'
            var form = new FormData($('#stocking_data_form')[0]);
            form.append('company_id', $('#company_id').val());
            form.append('warehouses_type', $('#warehouses_type').val());
            form.append('branch_id', $('#branch_id').val());
           
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
                    //url =  '{{ route("store-stocking.edit", ":id") }}';
                    //url = url.replace(':id',data.uuid);
                   // window.location.href = url;
                    
                }
                else
                {
                    toastr.warning(data.msg);
                }
            });
        }

    </script>
@endsection

