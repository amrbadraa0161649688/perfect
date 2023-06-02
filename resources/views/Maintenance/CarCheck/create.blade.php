@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <style type="text/css">
		.ctd{
			text-align: center;
            
		}
        /* .table th ,td{
            border-color: #113f50;
        } */
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
                        <form id="card_data_form"  enctype="multipart/form-data">
                            @csrf  
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> نوع كرت الصيانة </label>
                                            <select class="form-select form-control is-invalid" name="mntns_cards_type" id="mntns_cards_type" required>
                                                <option value="" selected> choose</option>  
                                                @foreach($mntns_cards_type as $mct)
                                                <option value="{{$mct->system_code_id}}" > {{ $mct->system_code_name_ar }}-{{ $mct->system_code_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label"> نوع الصيانة </label>
                                            <select class="form-select form-control is-invalid" name="mntns_cards_category" id="mntns_cards_category" required>
                                                <option value="" selected> choose</option>  
                                                @foreach($mntns_cards_category as $mcc)
                                                <option value="{{$mcc->system_code_id}}" > {{ $mcc->system_code_name_ar }}-{{ $mcc->system_code_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label"> نوع العميل </label>
                                            <select class="form-select form-control is-invalid" name="mntns_cards_customer_type" id="mntns_cards_customer_type" required>
                                                <option value="" selected> choose</option>    
                                                @foreach($cards_customer_type as $customer_type)
                                                <option value="{{$customer_type->system_code_id}}"> {{ $customer_type->system_code_name_ar }}-{{ $customer_type->system_code_name_en }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> العميل </label>
                                            <select class="form-select form-control is-invalid" name="customer_id" id="customer_id" required>
                                                <option value="" selected> choose</option>  
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label"> المركبة </label>
                                            <select class="form-select form-control is-invalid" name="mntns_cars_id" id="mntns_cars_id" data-live-search="true" required>
                                                <option value="" selected> choose</option>  
                                            </select>

                                                
                                        </div>
                                        <div class="col-md-2">
                                           <br>
                                           <br>
                                            @foreach(session('job')->permissions as $job_permission)
                                                @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
                                                    <a href="{{route('maintenance-car.create')}}"  target="_blank" class="btn btn-primary">
                                                        <i class="fe fe-plus mr-2"></i> مركبة جديدة
                                                    </a>
                                                @endif
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label "> ممشي المركبة </label>
                                            <input type="number" class="form-control is-invalid" name="mntns_cars_meter" id="mntns_cars_meter" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="recipient-name" class="col-form-label"> ملاحظات </label>
                                            <textarea rows="2" class="form-control" name="mntns_cards_notes" id="mntns_cards_notes" placeholder="Here can be your note" value=""></textarea>
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
                                    @include('Maintenance.MaintenanceCard.form.internal_table')
                                    
                                    @include('Maintenance.MaintenanceCard.form.external_table')

                                    @include('Maintenance.MaintenanceCard.form.part_table')

                                    @include('Maintenance.MaintenanceCard.form.total_table')
                                </div>
                            </div>
                        </div>
                        <br>
                        
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
                                <div class="card-footer">
                                    <button onclick="saveCard()" class="btn btn-primary btn-block">
                                        <i class="fe fe-plus mr-2"></i> حفظ
                                    </button>
                                </div>
                            @endif
                        @endforeach
                       
                        <br>
                    </div>
                </div>
                <br>
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

    <script type="text/javascript">
        $('#mntns_cards_type').change(function () {
            if (!$('#mntns_cards_type').val()) {
                $('#mntns_cards_type').addClass('is-invalid')
            } else {
                $('#mntns_cards_type').removeClass('is-invalid')
            }
        });

        $('#mntns_cards_category').change(function () {
            if (!$('#mntns_cards_category').val()) {
                $('#mntns_cards_category').addClass('is-invalid')
            } else {
                $('#mntns_cards_category').removeClass('is-invalid')
            }
        });

        $('#mntns_cards_customer_type').change(function () {
            if (!$('#mntns_cards_customer_type').val()) {
                $('#mntns_cards_customer_type').addClass('is-invalid')
            } else {
                $('#mntns_cards_customer_type').removeClass('is-invalid')
            }
        });

        $('#customer_id').change(function () {
            if (!$('#customer_id').val()) {
                $('#customer_id').addClass('is-invalid')
            } else {
                $('#customer_id').removeClass('is-invalid')
            }
        });

        $('#mntns_cars_id').change(function () {
            if (!$('#mntns_cars_id').val()) {
                $('#mntns_cars_id').addClass('is-invalid')
            } else {
                $('#mntns_cars_id').removeClass('is-invalid')
            }
        });

        $('#mntns_cars_meter').keyup(function () {
            if ($('#mntns_cars_meter').val().length < 2) {
                $('#mntns_cars_meter').addClass('is-invalid')
            } else {
                $('#mntns_cars_meter').removeClass('is-invalid');
            }
        });


        // get cusotmer registerd car 
        $('#customer_id').on('change',function(){
            //console.log('changed');
            $("#mntns_cars_id option").remove();
            var customer_id = $('#customer_id').val();
            $.ajax({
                url : '{{ route( 'maintenanced-car.get.car.list.by.customer.id' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer_id": customer_id
                    },
                type: 'get',
                dataType: 'json',
                success: function( result )
                {
                    $('#mntns_cars_id').append($('<option>', {value:'', text:'choose'}));
                    $.each( result.data, function(k) {
                        data = result.data;
                        //console.log(result.data[k].brand);
                        $('#mntns_cars_id').append($('<option>', {value:data[k].mntns_cars_id, text: result.data[k].brand.system_code_name_ar + '-' + data[k].mntns_cars_color+ '-' + data[k].mntns_cars_plate_no}));
                    });
                },
                error: function()
                {
                    //handle errors
                    alert('error...');
                }
            });
        });
      
      //get customer list base on cusomter type 
        $('#mntns_cards_customer_type').on('change',function(){
            //console.log('changed');
            $("#customer_id option").remove();
            var customer_type = $('#mntns_cards_customer_type').val();
            $.ajax({
                url : '{{ route( 'maintenance-card.get.customer.by.type' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer_type": customer_type
                    },
                type: 'get',
                dataType: 'json',
                success: function( result )
                {
                    $('#customer_id').append($('<option>', {value:'', text:'choose'}));
                    $.each( result.data, function(k) {
                        //console.log(result.data[k].mntns_cars_id);
                        $('#customer_id').append($('<option>', {value:result.data[k].customer_id, text:result.data[k].customer_name_full_ar }));
                    });
                },
                error: function()
                {
                    //handle errors
                    alert('error...');
                }
            });
        });
 
        function saveCard()
        {
           
            
            if($('.is-invalid').length > 0){
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            
            if(internal_table_data.length == 0 &&  external_table_data.length == 0 && part_table_data.length == 0)
			{
				return toastr.warning('لم يتم ادخال اي بند');
			}

            url = '{{ route('maintenance-card.store') }}'
            var form = new FormData($('#card_data_form')[0]);
			form.append('internal_table_data', JSON.stringify(internal_table_data));
            form.append('external_table_data', JSON.stringify(external_table_data));
            form.append('part_table_data', JSON.stringify(part_table_data));

            form.append('mntns_cards_internal_repairs',internal_total_amount);
            form.append('mntns_cards_outside_repairs',external_total_amount);
            form.append('mntns_cards_spare_parts',part_total_amount);	
            form.append('mntns_cards_discount',0);	
            form.append('mntns_cards_vat_amount',0);
            form.append('mntns_cards_vat_amount', internal_total_vat_amount+external_total_vat_amount +part_total_vat_amount);

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
                    window.location.href = "{{route('maintenance-card.index')}}";
				}
				else
				{
					toastr.warning(data.msg);
				}
			});
        }
    </script>

    <script type="text/javascript">
        $mntns_cards_item_disc_type ='' ;
        $mntns_cards_item_disc_amount = 0;
        
        internalRowNo = 1;
        externalRowNo = 1;
        partRowNo = 1;
        internal_table_data = [];
        external_table_data = [];
        part_table_data = [];

        internal_total_disc_amount =  0;
        internal_total_vat_amount = 0;
        internal_total_amount =  0;

        external_total_vat_amount = 0;
        external_total_amount =  0;
        
        part_total_disc_amount =  0;
        part_total_vat_amount =0;
        part_total_amount =0

        function updateTotal()
        {
            $('#g_total_disc_amount_div').text(internal_total_disc_amount+part_total_disc_amount); 
            $('#g_total_amount_div').text(internal_total_amount+external_total_amount+part_total_amount);
            $('#g_total_vat_amount_div').text(internal_total_vat_amount+external_total_vat_amount +part_total_vat_amount);
        }

        // -- INTERNAL TABLE 
        $(document).ready(function () {
            $('#mntns_cards_item_id').on('change',function(){
                //console.log($('#mntns_cards_item_id').val());
                //console.log($('#mntns_cards_item_id :selected').data('mntnstypehours'));
                //console.log($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                //console.log($('#mntns_cards_item_id :selected').data('mntnstypeempno'));
                $('#mntns_type_hours').val($('#mntns_cards_item_id :selected').data('mntnstypehours'));
                $('#mntns_type_value').val($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                $('#mntns_type_emp_no').val($('#mntns_cards_item_id :selected').data('mntnstypeempno'));
            });
            
            $('#mntns_cards_item_disc_type').on('change',function(){
                $mntns_cards_item_disc_type = $('#mntns_cards_item_disc_type').val();
                $('#mntns_cards_item_disc_amount').val(0);
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                $vat_percantage = 15 /100 ;
                $('#vat_value').val($value_befor_vat*$vat_percantage);
                $vat_value = $('#vat_value').val();
                $value_after_vat =  $value_befor_vat + ($value_befor_vat*$vat_percantage);
                $('#total_after_vat').val( $value_after_vat );

                

            });
            $("#mntns_cards_item_disc_amount" ).change(function() {
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                if($('#mntns_cards_item_disc_type').val() =='')
                {
                    alert('الرجاء ادخال نوع الخصم');
                }

                $mntns_cards_item_disc_type = parseFloat($('#mntns_cards_item_disc_type').val());
                $mntns_cards_item_disc_amount = $('#mntns_cards_item_disc_amount').val();
                $vat_percantage = 15 /100 ;
                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue'));
                //console.log($('#mntns_cards_item_disc_type').val());
                if($mntns_cards_item_disc_type == 533)
                {
                    //console.log('per');
                    if($('#mntns_cards_item_disc_amount').val() > 100)
                    {
                        $('#mntns_cards_item_disc_amount').val(0)
                        return  alert('لايمكن تطبيق خصم اكثر من 100%');
                    }
                    $discount_amount =  ($value_befor_vat * parseFloat($('#mntns_cards_item_disc_amount').val())/100);
                    $('#mntns_cards_item_disc_value').val($discount_amount);
                    //console.log($discount_amount);
                }
                else
                {
                    //console.log('val');
                    if($('#mntns_cards_item_disc_amount').val() > $value_befor_vat)
                    {
                        $('#mntns_cards_item_disc_amount').val(0)
                        return  alert(' لا يمكن تطبيق خصم اكثر من القيمة');
                    }
                    $discount_amount =  $mntns_cards_item_disc_amount;
                    $('#mntns_cards_item_disc_value').val($discount_amount);
                    //console.log($discount_amount);
                }

                $value_befor_vat = parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue')) - $discount_amount ;
                
                $('#vat_value').val($value_befor_vat  * $vat_percantage);
                $vat_value = $('#vat_value').val();
                $value_after_vat =  $value_befor_vat + ($value_befor_vat*$vat_percantage);
                $('#total_after_vat').val( $value_after_vat );

                
            
            });
        });

        function saveInternalRow()
        {
            if( ($('#mntns_cards_item_id').val() =='') || ($('#mntns_cards_item_disc_type').val() ==''))
            {
                return toastr.warning('لا يوجد بيانات لاضافتها');
            }
            tableBody = $("#internal_maintenance_table");
            rowNo = internalRowNo ;
            row_data = {
                'id' : 'tr'+rowNo,
                'count' : rowNo,
                'mntns_cards_item_id': parseFloat($('#mntns_cards_item_id').val()),
                'mntns_cards_item' : $('#mntns_cards_item_id :selected').data('mntnscardsitem'),
                'mntns_type_hours' : parseFloat($('#mntns_cards_item_id :selected').data('mntnstypehours')),
                'mntns_type_emp_no': parseFloat($('#mntns_cards_item_id :selected').data('mntnstypeempno')),
                'mntns_type_value': parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue')),
                'vat_id' : (15/100),
                'vat_per': parseFloat((15/100)),
                'total_befor_vat' : parseFloat($('#mntns_cards_item_id :selected').data('mntnstypevalue')),
                'total_afte_vat' : parseFloat($('#total_after_vat').val()),
                'vat_value' : parseFloat($('#vat_value').val()),
                'mntns_cards_item_disc_type' :   parseFloat($('#mntns_cards_item_disc_type').val()), 
                'mntns_cards_item_disc_type name' : $('#mntns_cards_item_disc_type :selected').data('mntnsdisctype'),
                'mntns_cards_item_disc_value' : parseFloat($('#mntns_cards_item_disc_amount').val()),
                'mntns_cards_item_disc_amount' : parseFloat($('#mntns_cards_item_disc_value').val()),

            }; 

            internal_table_data.push(row_data);
            
            markup = 
            '<tr id='+'tr'+rowNo+'>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'count">' +row_data['count'] + '</td>' +
                '<td  class="ctd " id="'+'tr'+rowNo+'mntns_cards_item">' +row_data['mntns_cards_item'] + '</td>' +
                '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_hours">'+ row_data['mntns_type_hours'] +'</td>' +
                '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_emp_no">'+ row_data['mntns_type_emp_no'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_value">'+ row_data['mntns_type_value'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'mntns_cards_item_disc_type name">'+ row_data['mntns_cards_item_disc_type name'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'mntns_cards_item_disc_amount">'+ row_data['mntns_cards_item_disc_amount'] +'</td>'+
                '<td  class="ctd" id="'+'tr'+rowNo+'vat_value">'+ row_data['vat_value'] +'</td>'+
                
                '<td  class="ctd" id="'+'tr'+rowNo+'total_afte_vat">'+ row_data['total_afte_vat'] +'</td>'+
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="removeInternalRow('+'tr'+rowNo+')"><i class="fa fa-trash"></i></button></td>'+
            '<tr>'+
            '<div id="new_row"></div>';
            internalRowNo = internalRowNo+1; 
            
            tableBody.append(markup); 
            $('#card_modal').on('hidden.bs.modal', function () {
                $('#card_modal .modal-body').html('');
                
            });
            $('#card_modal').modal('hide');
            //console.log(row_data);
            internal_total_vat_amount = internal_total_vat_amount + row_data['vat_value'];
            internal_total_disc_amount =  internal_total_disc_amount + row_data['mntns_cards_item_disc_amount'];
            internal_total_amount =  internal_total_amount + row_data['total_afte_vat'];

            $('#internal_total_disc_amount_div').text(internal_total_disc_amount);
            $('#internal_total_disc_amount').val(internal_total_disc_amount);

            $('#internal_total_vat_amount_div').text(internal_total_vat_amount);
            $('#internal_total_vat_amount').val(internal_total_vat_amount);

            $('#internal_total_amount_div').text(internal_total_amount);
            $('#internal_total_amount').val(internal_total_amount);
            
            //reset all input after save row
            $("#mntns_cards_item_id").val("").change();
            $("#mntns_cards_item_disc_type").val("").change();
            $('#mntns_cards_item_disc_amount').val(0);
            $('#vat_value').val(0);
            $('#total_after_vat').val(0);

            updateTotal();

        }

        function removeInternalRow(id)
        {
            rmid = id;
            id = id['id'];
            //console.log('id = ' + id);
            for (var i = 0; i < internal_table_data.length; i++)
            {
                data = (internal_table_data[i]);
                if (internal_table_data[i].id === id) 
                {
                    internal_table_data.splice(i, 1);
                    //console.log('key ='+i)
                    break;
                }
            }

            internal_total_vat_amount = internal_total_vat_amount - data.vat_value;
            internal_total_disc_amount =  internal_total_disc_amount - data.mntns_cards_item_disc_amount;
            internal_total_amount =  internal_total_amount - data.total_afte_vat;

            $('#internal_total_disc_amount_div').text(internal_total_disc_amount);
            $('#internal_total_disc_amount').val(internal_total_disc_amount);

            $('#internal_total_vat_amount_div').text(internal_total_vat_amount);
            $('#internal_total_vat_amount').val(internal_total_vat_amount);

            $('#internal_total_amount_div').text(internal_total_amount);
            $('#internal_total_amount').val(internal_total_amount);
            
            $(rmid ).remove();
            updateTotal();
        }
        // END INTERNAL TABLE

        $('#mntns_type_value_external').on('change',function(){
            $('#vat_value_external').val($('#mntns_type_value_external').val() * 15/100);
            $('#total_after_vat_external').val( parseFloat($('#mntns_type_value_external').val()) + parseFloat($('#vat_value_external').val()) )
        });

        
        

        


    </script>
    <script>
    
    function saveExternalRow()
    {
        
        if(!checkExternalInput())   
        {
            return toastr.warning('الرجاء التاكد من ادخال كافة الحقول');
        }

        tableBody = $("#external_maintenance_table");
        rowNo = externalRowNo ;
        row_data = {
            'id' : 'tr'+rowNo,
            'count' : rowNo,
            'mntns_cards_item_id': parseFloat($('#mntns_cards_item_id_external').val()),
            'mntns_cards_item' : $('#mntns_cards_item_id_external :selected').data('mntnscardsitem'),
            'mntns_type_hours' : parseFloat($('#mntns_cards_item_hours_external').val()),
            'mntns_type_emp_no': 0,
            'mntns_type_value': parseFloat($('#mntns_type_value_external').val()),
            'vat_id' : (15/100),
            'vat_per': parseFloat((15/100)),
            'total_befor_vat' : parseFloat($('#mntns_type_value_external').val()),
            'total_afte_vat' : parseFloat($('#total_after_vat_external').val()) ,
            'vat_value' : parseFloat($('#vat_value_external').val()),
            'mntns_cards_item_disc_type' :   0, 
            'mntns_cards_item_disc_type name' : 0,
            'mntns_cards_item_disc_value' : 0,
            'mntns_cards_item_disc_amount' : 0,

            'mntns_customer_cost_external' : parseFloat($('#mntns_customer_cost_external').val()),
            'invoice_no_external' : $('#invoice_no_external').val(),
            'invoice_date_external' : $('#invoice_date_external').val(),
            'invoice_file_external' : 0,//$('$invoice_file_external').val()

        }; 

        external_table_data.push(row_data);
        
        markup = 
        '<tr id='+'tr'+rowNo+'>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'count">' +row_data['count'] + '</td>' +
            '<td  class="ctd " id="'+'tr'+rowNo+'mntns_cards_item">' +row_data['mntns_cards_item'] + '</td>' +
            '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_hours">'+ row_data['mntns_type_hours'] +'</td>' +
            // '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_emp_no">'+ row_data['mntns_type_emp_no'] +'</td>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'mntns_type_value">'+ row_data['mntns_type_value'] +'</td>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'vat_value">'+ row_data['vat_value'] +'</td>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'total_afte_vat">'+ row_data['total_afte_vat'] +'</td>'+

            '<td  class="ctd" id="'+'tr'+rowNo+'mntns_customer_cost_external">'+ row_data['mntns_customer_cost_external'] +'</td>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'invoice_no_external">'+ row_data['invoice_no_external'] +'</td>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'invoice_date_external">'+ row_data['invoice_date_external'] +'</td>'+
            '<td  class="ctd" id="'+'tr'+rowNo+'invoice_file_external">'+ row_data['invoice_file_external'] +'</td>'+
            '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="removeExternalRow('+'tr'+rowNo+')"><i class="fa fa-trash"></i></button></td>'+
        '<tr>'+
        '<div id="new_row"></div>';
        externalRowNo = externalRowNo+1; 
        
        tableBody.append(markup); 
        $('#card_modal').on('hidden.bs.modal', function () {
            $('#card_modal .modal-body').html('');
            
        });
        $('#card_modal').modal('hide');
        //console.log(row_data);

        external_total_vat_amount = external_total_vat_amount + row_data['vat_value'];
        external_total_amount =  external_total_amount + row_data['total_afte_vat'];

        $('#external_total_vat_amount_div').text(parseFloat(external_total_vat_amount));
        $('#external_total_vat_amount').val(parseFloat(external_total_vat_amount));

        $('#external_total_amount_div').text(parseFloat(external_total_amount));
        $('#external_total_amount').val(parseFloat(external_total_amount));
        

        //reset all input after save row
        // $("#mntns_cards_item_id_external").val("").change();
        // $("#mntns_cards_item_disc_type").val("").change();
        // $('#mntns_cards_item_disc_amount').val(0);
        // $('#vat_value').val(0);
        // $('#total_after_vat').val(0);

        updateTotal();

    

    }

    function removeExternalRow(id)
    {
        rmid = id;
        id = id['id'];
        
        //console.log('id = ' + id);
        for (var i = 0; i < external_table_data.length; i++)
        {
            data = (external_table_data[i]);
            if (external_table_data[i].id === id) 
            {
                external_table_data.splice(i, 1);
                //console.log('key ='+i)
                break;
            }
                
        }

        external_total_vat_amount = external_total_vat_amount - data.vat_value;
        external_total_amount =  external_total_amount - data.total_afte_vat;

        $('#external_total_vat_amount_div').text(parseFloat(external_total_vat_amount));
        $('#external_total_vat_amount').val(parseFloat(external_total_vat_amount));

        $('#external_total_amount_div').text(parseFloat(external_total_amount));
        $('#external_total_amount').val(parseFloat(external_total_amount));
        
        $(rmid ).remove();
        updateTotal();
    }

    function checkExternalInput()
    {
        
        if(
            $('#mntns_cards_item_id_external').val() == '' || 
            $('#mntns_cards_item_hours_external').val() == ''  || 
            $('#mntns_type_value_external').val() == ''  ||

            $('#mntns_customer_cost_external').val() == ''  ||
            $('#invoice_no_external').val() == ''  ||
            $('#invoice_date_external').val() == ''  )
        {
            return false;
        }

        return true;
        
    }

</script>

    

@endsection

