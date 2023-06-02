<div id="create_trans_modal" class="modal fade full" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:250%">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:right">
                     طلب تحويل جديد
                </h4>
                <div style="text-align:left">
                <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="trans_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="create_tyep" id="create_tyep" value="new" >
                                                <span class="custom-control-label">  طلب تحويل جديد </span>
                                            </label>
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="create_tyep" id="create_tyep" value="from_req">
                                                <span class="custom-control-label">استيراد طلب شراء</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="row" id="from_req" style="display:none;">
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> رقم طلب الشراء </label>
                                    <input type="text" class="form-control" name="req_code" id="req_code" value="" onchange="getDataByCode(this)">
                                </div>
                                
                            </div>
                            <div id="showResult"></div>
                            <div class="row" id="new" style="display:none;">
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label"> الفرع </label>
                                    <select class="form-select form-control is-invalid" name="source_branch" id="source_branch" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($branch_list as $branch)
                                        <option value="{{$branch->branch_id}}" data-vendorname="{{ $branch->getBranchName() }}" > {{ $branch->getBranchName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label ">  المستودع </label>
                                    <select class="form-select form-control is-invalid" name="source_store" id="source_store" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($warehouses_type_list as $w_t)
                                        <option value="{{$w_t->system_code}}" > {{ $w_t->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label">  الفرع المستلم </label>
                                    <select class="form-select form-control is-invalid" name="dest_branch" id="dest_branch" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($branch_list as $branch)
                                        <option value="{{$branch->branch_id}}" data-vendorname="{{ $branch->getBranchName() }}" > {{ $branch->getBranchName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient-name" class="col-form-label ">   المستودع المستلم </label>
                                    <select class="form-select form-control is-invalid" name="dest_store" id="dest_store" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($warehouses_type_list as $w_t)
                                        <option value="{{$w_t->system_code}}" > {{ $w_t->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeItemModal()">الغاء</button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6" id="from_button" style="display:none;" >	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveFromRequest()">اضافة </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6" id="new_button" style="display:none;">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveRequest()"> اضافة جديد </button>
                </div>		
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {
        $('#store_acc_no').on('change',function(){
            $('#store_acc_name').val($('#store_acc_no :selected').data('vendorname'));
            $('#store_acc_name').removeClass('is-invalid');
            $('#store_acc_tax_no').val($('#store_acc_no :selected').data('vendorvat'));
            $('#store_acc_tax_no').removeClass('is-invalid');
        });

        $('#source_branch').change(function () {
            if (!$('#source_branch').val()) {
                $('#source_branch').addClass('is-invalid');
                
            } else {
                $('#source_branch').removeClass('is-invalid');
            }
        });

        $('#source_store').change(function () {
            if (!$('#source_store').val()) {
                $('#source_store').addClass('is-invalid');
                
            } else {
                $('#source_store').removeClass('is-invalid');
            }
        });

        $('#dest_branch').change(function () {
            if (!$('#dest_branch').val()) {
                $('#dest_branch').addClass('is-invalid');
                
            } else {
                $('#dest_branch').removeClass('is-invalid');
            }
        });

        $('#dest_store').change(function () {
            if (!$('#dest_store').val()) {
                $('#dest_store').addClass('is-invalid');
                
            } else {
                $('#dest_store').removeClass('is-invalid');
            }
        });

        $("input[name='create_tyep']").click(function() {
            
            if($(this).val() == 'new')
            {
                $('#new').show();
                $('#from_req').hide();
                $('#showResult').html('');

                $('#from_button').hide();
                $('#new_button').show();
            }
            else if($(this).val() == 'from_req'){
                
                $('#new').hide();
              
                $('#from_req').show();
                $('#showResult').html('');

                $('#new_button').hide();
                $('#from_button').show();
            }
           

        });

    });

    function selectItem(e)
    {
        id = '#store_vou_qnt_t_o_'+e.value;
        store_vou_item_price_unit = '#store_vou_item_price_unit_'+e.value;
        store_voue_disc_value = '#store_voue_disc_value_'+e.value;
        store_vou_qnt_t_i_r = parseFloat($('#store_vou_qnt_t_i_r_'+e.value).val());
        if(store_vou_qnt_t_i_r == 0)
        {
            return toastr.warning('تم طلب جميع الكميات لا يمكن الطلب ');
            $(e).prop('checked',false) ;
        }
        if($(e).prop('checked') == true){
            $(id).prop('readonly', false);
            $(store_voue_disc_value).prop('readonly', false);
            $(store_vou_item_price_unit).prop('readonly', false);
        }
        else{
            $(id).prop('readonly', true);
            $(store_voue_disc_value).prop('readonly', true);
            $(store_vou_item_price_unit).prop('readonly', true);
        }
        calculateTotal(e);
        //console.log(e.value);
    }

    function calculateItem(e)
    {
        store_vou_qnt_r = parseFloat($('#store_vou_qnt_r_'+e.name).val());
        store_vou_qnt_i = parseFloat($('#store_vou_qnt_t_o_'+e.name).val());
        store_vou_qnt_t_i_r = parseFloat($('#store_vou_qnt_t_i_r_'+e.name).val());
        
        if(store_vou_qnt_i <= 0)
        {
            $('#store_vou_qnt_t_o_'+e.name).val(store_vou_qnt_r);
            $('#store_vou_qnt_t_i_r_'+e.name).val(0 );
            return toastr.warning('لايمكن اضافة كيمة اقل من صفر ');
        }

        if(store_vou_qnt_i>store_vou_qnt_r)
        {
            $('#store_vou_qnt_t_o_'+e.name).val(store_vou_qnt_r);
            toastr.warning('لايمكن اضافة كيمة اكبر من كمية الطلب');
        }
        else{   

            $('#store_vou_qnt_t_i_r_'+e.name).val(store_vou_qnt_r - store_vou_qnt_i );

        }
       
        calculateTotal(e);

    }

    function calculateTotal(e)
    {
        //console.log(e.value);
        unit_price = parseFloat($('#store_vou_item_price_unit_'+e.name).val());
        store_vou_item_total_price = '#store_vou_item_total_price_'+e.name;
        store_vou_vat_amount = '#store_vou_vat_amount_'+e.name;
        store_vou_price_net = '#store_vou_price_net_'+e.name;
       
        store_vou_qnt_i = parseFloat($('#store_vou_qnt_t_o_'+e.name).val());
        store_vou_item_total_price_amount = unit_price * parseFloat(store_vou_qnt_i);
        $(store_vou_item_total_price).val(store_vou_item_total_price_amount);
        //console.log('store_vou_item_total_price_amount = '+store_vou_item_total_price_amount );

        store_vou_disc_type = parseFloat($('#store_vou_disc_type_'+e.name).val());
        //console.log('disc type id'+store_vou_disc_type );

        store_voue_disc_value = parseFloat($('#store_voue_disc_value_'+e.name).val());
        store_vou_disc_amount = '#store_vou_disc_amount_'+e.name;

        if(store_vou_disc_type == 533)
        {
            //console.log('fix');
            //console.log(parseFloat($(store_vou_item_total_price).val()));
            //console.log(store_voue_disc_value);

            if( store_voue_disc_value  > 100)
            {
                $('#store_voue_disc_value_'+e.name).val(0)
                return  alert('لايمكن تطبيق خصم اكثر من 100%');
            }
            $discount_amount =  ( store_vou_item_total_price_amount * store_voue_disc_value/100);
            $(store_vou_disc_amount).val($discount_amount);
        }
        else
        {
            //console.log('fix');
            //console.log(parseFloat($(store_vou_item_total_price).val()));
            //console.log(store_voue_disc_value);
            if(store_voue_disc_value >  parseFloat($(store_vou_item_total_price).val()))
            {
                $('#store_voue_disc_value_'+e.name).val(0)
                return  alert(' لا يمكن تطبيق خصم اكثر من القيمة');
            }
            $discount_amount = store_voue_disc_value;
            $(store_vou_disc_amount).val($discount_amount);
        }
        vat_rate = 15/100;
        $(store_vou_vat_amount).val( vat_rate * (parseFloat($(store_vou_item_total_price).val()) - parseFloat($(store_vou_disc_amount).val())));
        $(store_vou_price_net).val(  ( parseFloat($(store_vou_item_total_price).val()) - parseFloat($(store_vou_disc_amount).val()) + parseFloat($(store_vou_vat_amount).val())) )

        calculateTotalOfAll();
        //console.log('cal');
    }

    function calculateTotalOfAll()
    {
        total_sum = 0;
        $('.total_sum').each(function(){
            selected = '#selected_item_'+ $(this)[0].name;
            if($(selected).prop('checked') == true)
            {
                total_sum = total_sum + parseFloat($(this).val());
            }
        });
        $('#total_sum_div').text(total_sum);

        total_disc = 0;
        $('.total_disc').each(function(){
            selected = '#selected_item_'+ $(this)[0].name;
            if($(selected).prop('checked') == true)
            {
                total_disc = total_disc+ parseFloat($(this).val());
            }
        });
        $('#total_disc_div').text(total_disc);

        total_sum_vat = 0;
        $('.total_sum_vat').each(function(){
            selected = '#selected_item_'+ $(this)[0].name;
            if($(selected).prop('checked') == true)
            {
                total_sum_vat = total_sum_vat+ parseFloat($(this).val());
            }
        });
        $('#total_sum_vat_div').text(total_sum_vat);

        total_sum_net = 0;
        $('.total_sum_net').each(function(){
            selected = '#selected_item_'+ $(this)[0].name;
            if($(selected).prop('checked') == true)
            {
                total_sum_net = total_sum_net+ parseFloat($(this).val());
            }
        });
        $('#total_sum_net_div').text(total_sum_net);
       
    }

    function checkAll(ele)
    {
        $('input:checkbox').not(ele).prop('checked', ele.checked);
        calculateTotalOfAll();
    }

    function getDataByCode(ele)
    {
        
        url = '{{ route('get-store-sales-by-code') }}';
        $page = 'trans_from_request';
        $request_code = $('#req_code').val();

        $('#showResult').html('');
        $.ajax({
            type: 'GET',
            url : url,
            data: {
                "_token": "{{ csrf_token() }}",
                company_id :  $('#company_id').val(),
                warehouses_type : $('#warehouses_type').val(),
                branch_id : $('#branch_id').val(),
                request_code :$request_code,
                page : $page,
            },
            dataType: 'json',
            
        }).done(function(data)
        {
            if(data.success)
            {
                toastr.success(data.msg);
                $('#showResult').html(data.view);
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function saveRequest()
    {
        if($('.is-invalid').length > 0){
            return toastr.warning('تاكد من ادخال كافة الحقول');
        }
        url = '{{ route('sales-car-transfer-trans.store') }}'
        var form = new FormData($('#trans_data_form')[0]);
        form.append('company_id', $('#company_id').val());
        //form.append('warehouses_type', $('#warehouses_type').val());
        //form.append('branch_id', $('#branch_id').val());

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
                url =  '{{ route("sales-car-transfer-trans.edit", ":id") }}';
                url = url.replace(':id',data.uuid);
                window.location.href = url;
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function saveFromRequest()
    {
        item_data = [];
        $('.item-qty').each(function(){
            
            uuid = $(this)[0].name;
            checked_item = '#selected_item_'+uuid;
            console.log(checked_item);
            checked_item = $(checked_item).prop('checked');
            if(checked_item == true){
                //console.log(uuid+ '='+checked_item);
                row ={
                    'uuid' :  uuid  ,
                    
                    'store_vou_qnt_t_o' : parseFloat($('#store_vou_qnt_t_o_'+ uuid).val()),
                    'store_vou_qnt_r' : parseFloat($('#store_vou_qnt_r_'+ uuid).val()),
                    'store_vou_qnt_t_i_r' : parseFloat($('#store_vou_qnt_t_i_r_'+ uuid).val()),

                    'store_brand_id':  $('#store_brand_id_'+ uuid).val(),
                    'store_brand_dt_id':  $('#store_brand_dt_id_'+ uuid).val(),
                    

                    'store_vou_item_price_unit' : parseFloat($('#store_vou_item_price_unit_'+ uuid).val()),
                    'store_vou_item_total_price' : parseFloat($('#store_vou_item_total_price_'+ uuid).val()),
                    
                    'store_vou_disc_type' : parseFloat($('#store_vou_disc_type_'+ uuid).val()),
                    'store_voue_disc_value' : parseFloat($('#store_voue_disc_value_'+ uuid).val()),
                    'store_vou_disc_amount' : parseFloat($('#store_vou_disc_amount_'+ uuid).val()),
                    
                    'store_vou_vat_rate' : (15/100),
                    'store_vou_vat_amount' : parseFloat($('#store_vou_vat_amount_'+ uuid).val()),
                    
                    'store_vou_price_net' : parseFloat($('#store_vou_price_net_'+ uuid).val()),


                };
                item_data.push(row);
            }
           
        });

        if(item_data.length == 0)
        {
            return  toastr.warning('لا بد من اختيار صنف واحد على الاقل');
        }
        
        url = '{{ route('sales-car-transfer-trans-req.store') }}'
        var form = new FormData($('#trans_data_form')[0]);
        form.append('company_id', $('#company_id').val());
        form.append('dest_branch', $('#from_req_dest_branch').val());
        form.append('dest_store', $('#from_req_dest_store').val());
        form.append('source_branch', $('#source_branch').val());
        form.append('item_data', JSON.stringify(item_data));
        

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
                closeItemModal();
                getData();
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }
</script>