<div id="create_return_modal" class="modal fade full" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content" style="width:250%">
            <div class="modal-header"> 
               
                <h4 class="modal-title" style="text-align:right">
                    @lang('purchase.add_new_return') 
                </h4>
                <div style="text-align:left">
                <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="return_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-controls-stacked">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="create_tyep" id="create_tyep" value="return-sales" >
                                            <span class="custom-control-label">استيراد فاتورة المبيعات</span>
                                        </label>
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="create_tyep" id="create_tyep" value="return-purcahse">
                                            <span class="custom-control-label">استيراد اذن الاستلام</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="return-purcahse" style="display:none;">
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> رقم اذن الاستلام </label>
                                    <input type="text" class="form-control" name="request_code" id="request_code" value="" onchange="getDataByCode(this,'return-purcahse')">
                                </div>
                                <div id="showResult"></div>
                            </div>

                            <div class="row" id="return-sales" style="display:none;">
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> رقم فاتورة المبيعات </label>
                                    <input type="text" class="form-control" name="request_sales_code" id="request_sales_code" value="" onchange="getDataByCode(this,'return-sales')">
                                </div>
                                <div id="showResultSales"></div>
                            </div>

                            <div class="row" id="return-sales" style="display:none;">
                                <div class="col-md-4">
                                     <label for="recipient-name" class="col-form-label "> @lang('purchase.item_category') </label>
                                    <select class="form-select form-control is-invalid" name="store_category_type" id="store_category_type" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($warehouses_type_list as $w_t)
                                        <option value="{{$w_t->system_code}}" > {{ $w_t->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('purchase.vendor') </label>
                                    <select class="form-select form-control is-invalid" name="store_acc_no" id="store_acc_no" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($vendor_list as $vendor)
                                        <option value="{{$vendor->customer_id}}" data-vendorname="{{ $vendor->getCustomerName() }}" data-vendorvat="{{ $vendor->customer_vat_no }}" > {{ $vendor->getCustomerName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('purchase.vendor_name') </label>
                                    <input type="text" class="form-control is-invalid" name="store_acc_name" id="store_acc_name" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('purchase.vat_no') </label>
                                    <input type="number" class="form-control is-invalid" name="store_acc_tax_no" id="store_acc_tax_no" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('purchase.payment_method')  </label>
                                    <select class="form-select form-control is-invalid" name="store_vou_pay_type" id="store_vou_pay_type" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($payemnt_method_list as $p_method)
                                        <option value="{{$p_method->system_code}}"> {{ $p_method->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                
                                    <div class="col-md-8">
                                       <label for="recipient-name" class="col-form-label">@lang('purchase.note') </label>
                                        <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value=""></textarea>
                                    </div>
                                
                            
                            </div>

                            <div class="row" id="return-purcahse" style="display:none;">
                                <div class="col-md-4">
                                     <label for="recipient-name" class="col-form-label "> @lang('purchase.item_category') </label>
                                    <select class="form-select form-control is-invalid" name="store_category_type" id="store_category_type" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($warehouses_type_list as $w_t)
                                        <option value="{{$w_t->system_code}}" > {{ $w_t->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('purchase.vendor') </label>
                                    <select class="form-select form-control is-invalid" name="store_acc_no" id="store_acc_no" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($vendor_list as $vendor)
                                        <option value="{{$vendor->customer_id}}" data-vendorname="{{ $vendor->getCustomerName() }}" data-vendorvat="{{ $vendor->customer_vat_no }}" > {{ $vendor->getCustomerName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('purchase.vendor_name') </label>
                                    <input type="text" class="form-control is-invalid" name="store_acc_name" id="store_acc_name" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('purchase.vat_no') </label>
                                    <input type="number" class="form-control is-invalid" name="store_acc_tax_no" id="store_acc_tax_no" value="">
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('purchase.payment_method')  </label>
                                    <select class="form-select form-control is-invalid" name="store_vou_pay_type" id="store_vou_pay_type" required>
                                        <option value="" selected> choose</option>    
                                        @foreach($payemnt_method_list as $p_method)
                                        <option value="{{$p_method->system_code}}"> {{ $p_method->getSysCodeName() }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                
                                    <div class="col-md-8">
                                       <label for="recipient-name" class="col-form-label">@lang('purchase.note') </label>
                                        <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value=""></textarea>
                                    </div>
                                
                            
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" data-dismiss="modal"  onclick="closeItemModal()">الغاء</button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6"  id="return-purcahse-btn" style="display:none;">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="savePurcahseRequest()">اضافة مرتجع مشتريات </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6"  id="return-sales-btn" style="display:none;">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveSalesRequest()"> اضافة مرتجع مبيعات </button>
                </div>		
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {

        
        $("input[name='create_tyep']").click(function() {
            
            if($(this).val() == 'return-sales')
            {
                $('#return-sales').show();
                $('#return-sales-btn').show();

                $('#return-purcahse').hide();
                $('#return-purcahse-btn').hide();
            }
            else{
                
                $('#return-sales').hide();
                $('#return-sales-btn').hide();

                $('#return-purcahse').show();
                $('#return-purcahse-btn').show();
            }

        });

        $('#store_acc_no').on('change',function(){
            $('#store_acc_name').val($('#store_acc_no :selected').data('vendorname'));
            $('#store_acc_name').removeClass('is-invalid');
            $('#store_acc_tax_no').val($('#store_acc_no :selected').data('vendorvat'));
            $('#store_acc_tax_no').removeClass('is-invalid');
        });

        $('#store_category_type').change(function () {
            if (!$('#store_category_type').val()) {
                $('#store_category_type').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_category_type').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
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

    function selectItem(e)
    {
        id = '#store_vou_qnt_i_r_'+e.value;
        store_vou_item_price_unit = '#store_vou_item_price_unit_'+e.value;
        store_voue_disc_value = '#store_voue_disc_value_'+e.value;
        store_vou_qnt_t_i_r = parseFloat($('#store_vou_qnt_t_i_r_'+e.value).val());
        if(store_vou_qnt_t_i_r == 0)
        {
            return toastr.warning('تم طلب جميع الكميات لا يمكن الطلب ');
            $(e).prop('checked',false) ;
        }
        if($(e).prop('checked') == true){
            //$(id).prop('readonly', false);
            //$(store_voue_disc_value).prop('readonly', false);
            //$(store_vou_item_price_unit).prop('readonly', false);
        }
        else{
            //$(id).prop('readonly', true);
            //$(store_voue_disc_value).prop('readonly', true);
            //$(store_vou_item_price_unit).prop('readonly', true);
        }
        calculateTotal(e);
        //console.log(e.value);
    }

    function calculateItem(e)
    {
        store_vou_qnt_i = parseFloat($('#store_vou_qnt_i_'+e.name).val());
        store_vou_qnt_i_r = parseFloat($('#store_vou_qnt_i_r_'+e.name).val());
        store_vou_qnt_t_i_r = parseFloat($('#store_vou_qnt_t_i_r_'+e.name).val());
        
        if(store_vou_qnt_i_r <= 0)
        {
            $('#store_vou_qnt_i_r_'+e.name).val(store_vou_qnt_i);
            $('#store_vou_qnt_t_i_r_'+e.name).val(0 );
            return toastr.warning('لايمكن اضافة كيمة اقل من صفر ');
        }

        if(store_vou_qnt_i_r>store_vou_qnt_i)
        {
            $('#store_vou_qnt_i_r_'+e.name).val(store_vou_qnt_i);
            toastr.warning('لايمكن اضافة كيمة اكبر من كمية الطلب');
        }
        else{   

            $('#store_vou_qnt_t_i_r_'+e.name).val(store_vou_qnt_i - store_vou_qnt_i_r );

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
       
        store_vou_qnt_i_r = parseFloat($('#store_vou_qnt_i_r_'+e.name).val());
        store_vou_item_total_price_amount = unit_price * parseFloat(store_vou_qnt_i_r);
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

    function getDataByCode(ele,type)
    {
        url = '{{ route('get-store-sales-by-code') }}'
        $('#showResult').html('');
        $('#showResultSales').html('');
        $.ajax({
            type: 'GET',
            url : url,
            data: {
                "_token": "{{ csrf_token() }}",
                company_id :  $('#company_id').val(),
                warehouses_type : $('#warehouses_type').val(),
                branch_id : $('#branch_id').val(),
                request_code : (type == 'return-purcahse' ? $('#request_code').val() : $('#request_sales_code').val()),
                page : type,
            },
            dataType: 'json',
            
        }).done(function(data)
        {
            if(data.success)
            {
                toastr.success(data.msg);
                (type == 'return-purcahse' ? $('#showResult').html(data.view) : $('#showResultSales').html(data.view))
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function saveSalesRequest()
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
                    'store_vou_qnt_o' : parseFloat($('#store_vou_qnt_i_'+ uuid).val()),
                    'store_vou_qnt_o_r' : parseFloat($('#store_vou_qnt_i_r_'+ uuid).val()),
                    'store_vou_qnt_t_i_r' : parseFloat($('#store_vou_qnt_t_i_r_'+ uuid).val()),
                    'store_vou_item_id' : $('#store_vou_item_id_'+ uuid).val(),
                    
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

        
        url = "{{ route('sales-car-sales-return.store') }}";
        var form = new FormData($('#return_form')[0]);
        form.append('company_id', $('#company_id').val());
        form.append('warehouses_type', $('#warehouses_type').val());
        form.append('branch_id', $('#branch_id').val());
        form.append('item_data', JSON.stringify(item_data));
        form.append('store_vou_ref_before', $('#store_vou_ref_before').val());

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
                //url =  '{{ route("store-purchase-order.edit", ":id") }}';
                //url = url.replace(':id',data.uuid);
                //window.location.href = url;
                closeItemModal();
                getData();
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function savePurcahseRequest()
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
                    'store_vou_qnt_i' : parseFloat($('#store_vou_qnt_i_'+ uuid).val()),
                    'store_vou_qnt_i_r' : parseFloat($('#store_vou_qnt_i_r_'+ uuid).val()),
                    'store_vou_qnt_t_i_r' : parseFloat($('#store_vou_qnt_t_i_r_'+ uuid).val()),
                    'store_vou_item_id' : $('#store_vou_item_id_'+ uuid).val(),
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

        
        url = '{{ route('sales-car-purcahse-return.store') }}';
        var form = new FormData($('#return_form')[0]);
        form.append('company_id', $('#company_id').val());
        form.append('warehouses_type', $('#warehouses_type').val());
        form.append('branch_id', $('#branch_id').val());
        form.append('item_data', JSON.stringify(item_data));
        form.append('store_vou_ref_before', $('#store_vou_ref_before').val());

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
                //url =  '{{ route("store-purchase-order.edit", ":id") }}';
                //url = url.replace(':id',data.uuid);
                //window.location.href = url;
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