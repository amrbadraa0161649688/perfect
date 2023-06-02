@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
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
                        <select class="form-control" id="company_id" name="company_id" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('home.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}" @if( $user_data['company']->company_id == $company->company_id) selected @endif>
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
                        <select class="form-control" id="sms_provider_id" name="sms_provider_id" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('home.choose')</option>
                            @foreach($providers as $providers)
                                <option value="{{$providers->sms_provider_id}}" >
                                    {{$providers->sms_provider_name}}
                                </option>
                            @endforeach
                        </select>

                    </form>
                </div>
            </div>
            <br>
            <div id="showData"></div>
        </div>
    </div>
    
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
        
        $(function () {
            getData();
        });

        function getData(){
            company_id = ($('#company_id').val() ? $('#company_id').val() : {{ auth()->user()->company_id }});
            search = { 
                company_id: $('#company_id').val(), 
            };
            $.ajax({
                type: 'get',
                url :"{{route('sms-category.data')}}",
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

        
        function saveCategory()
        {
            // if($('#add_item_modal .is-invalid').length > 0){
            //     return toastr.warning('تاكد من ادخال كافة الحقول');
            // }
            console.log($('#add_item_modal .is-invalid').length);
            url = '{{ route('sms-category.store') }}'
            var form = new FormData($('#sms_data_form')[0]);
			form.append('company_id', $('#company_id').val());

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

        

        function edit(url) {

            $.ajax({
                type: 'get',
                url :url,
                data:{
                    _token : "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function(data){

                if(data.success==false){
                    toastr.warning(data.msg);
                }
                $('#edit_item_modal .modal-dialog').html('');
                $('#edit_item_modal').modal("show").on('shown.bs.modal', function () {
                    $('#edit_item_modal .modal-dialog').html(data.view);
                });

            });

        }

        function closeItemModal()
        {
            $('#add_item_modal').on('hidden.bs.modal', function () {
                //$('#add_item_modal .modal-body').html('');
            });
            $('#add_item_modal').modal('hide');
        }

        function resetSearch()
        {
            $('#search_input').find('input:text').val('');    
        }

        function deletItem($uuid)
        {
            url = '{{ route('sms-category.delete') }}';
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

