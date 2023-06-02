@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <style>
        .full
        {
            /* padding-left: 50%; */
            padding-inline-end : 50% !important;
        }
    </style>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <form action="">
                        @if(session('company_group'))
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ session('company_group')['company_group_ar'] }} @else
                            {{ session('company_group')['company_group_en'] }} @endif" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="company_id" name="company_id" onchange="getData( $('#company_id').val())" disabled>
                            <option value="">@lang('home.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                    @if( $user_data['company']->company_id == $company->company_id) selected @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="branch_id" name="branch_id" onchange="getData( $('#company_id').val())" disabled>
                            <option value="">@lang('home.choose')</option>
                            @foreach($branch_lits as $branch)
                                <option value="{{$branch->branch_id}}" @if( $user_data['branch']->branch_id == $branch->branch_id) selected @endif>
                                    {{ $branch->getBranchName() }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="warehouses_type" name="warehouses_type" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('home.choose')</option>
                            @foreach($warehouses_type_lits as $warehouses_type)
                                <option value="{{$warehouses_type->system_code_id}}"  >
                                        {{ $warehouses_type->getSysCodeName() }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
            </div>
            <br>
            <!-- <div class="row" id ="search_input">
                <div class="col-md-5 row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_code_s" id="item_code_s" placeholder="@lang('storeItem.item_code')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_vendor_code_s" id="item_vendor_code_s" placeholder="@lang('storeItem.item_vendor_code')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_code_1_s" id="item_code_1_s" placeholder="@lang('storeItem.item_code_1')">
                    </div>
                    
                </div>
                <div class="col-md-5 row">
                    
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_code_2_s" id="item_code_2_s" placeholder="@lang('storeItem.item_code_2')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_name_a_s" id="item_name_a_s" placeholder="@lang('storeItem.item_name_a')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_name_e_s" id="item_name_e_s" placeholder="@lang('storeItem.item_name_e')">
                    </div>
                </div>
                <div class="col-md-2 row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block" onclick="getData()">
                            @lang('storeItem.search_button')
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning btn-block" onclick="resetSearch()">
                            @lang('storeItem.clear_button')
                        </button>
                    </div>
                </div>
            </div> -->
            <div id="showData"></div>
        </div>
    </div>

    
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        
        $(function () {
            //company_id = {{ auth()->user()->company_id }};
            //getData(company_id);
            getData();
        });

        function getData(){
            //console.log($('#company_id').val());
            company_id = ($('#company_id').val() ? $('#company_id').val() : {{ auth()->user()->company_id }});
            search = { 
                company_id: $('#company_id').val(), 
                warehouses_type: $('#warehouses_type').val(), 
                branch_id: $('#branch_id').val()
            };
            $.ajax({
                type: 'get',
                url :"{{route('store-transfer-trans.data','trans')}}",
                data:{
                    _token : "{{ csrf_token() }}",
                    company_id : company_id,
                    search : search,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function(data){
                //App.stopPageLoading();
                if(data.success==false){
                    toastr.warning(data.msg);
                }
                $('#showData').html(data.view);
            });
        }

        
        function saveItem()
        {
            if($('.is-invalid').length > 0){
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            url = '{{ route('store-item.store') }}'
            var form = new FormData($('#item_data_form')[0]);
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
                    getData();
                    closeItemModal();
                    
				}
				else
				{
					toastr.warning(data.msg);
				}
			});
        }

        function closeItemModal()
        {
            $('#create_trans_modal').on('hidden.bs.modal', function () {
                //$('#create_trans_modal .modal-body').html('');
            });
            $('#create_trans_modal').modal('hide');
        }

        function resetSearch()
        {
            $('#search_input').find('input:text').val('');    
        }

        function deletItem($uuid)
        {
            url = '{{ route('store-item.delete') }}';
            $.ajax({
                type: 'POST',
                url : url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data:
                {
                    'uuid' : $uuid,
                },
                
            }).done(function(data){
                if(data.success)
                {
                    toastr.success(data.msg);
                    getData();
                }
                else
                {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>
@endsection

