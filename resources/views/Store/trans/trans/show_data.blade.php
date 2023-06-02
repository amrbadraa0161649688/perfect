
<div >
    <div class="row" id="new">
        <div class="col-md-3">
             <label for="recipient-name" class="col-form-label "> @lang('purchase.item_category') </label>
            <select class="form-select form-control" name="store_category_type" id="store_category_type" disabled>
                @foreach($warehouses_type_list as $w_t)
                <option value="{{$w_t->system_code}}"  {{($purchase_request->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                @endforeach
            </select> 
        </div>
        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label">  @lang('purchase.vendor') </label>
            <select class="form-select form-control" name="store_acc_no" id="store_acc_no" disabled>
                <option value="" selected> choose</option>  
                @foreach($vendor_list as $vendor)
                <option value="{{$vendor->customer_id}}" data-vendorname="{{ $vendor->getCustomerName() }}" data-vendorvat="{{ $vendor->customer_vat_no }}" {{($purchase_request->store_acc_no == $vendor->customer_id ? 'selected': '' )}} > {{ $vendor->getCustomerName() }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label "> @lang('purchase.vendor_name') </label>
            <input type="text" class="form-control" name="store_acc_name" id="store_acc_name" value="{{$purchase_request->store_acc_name}}" readonly>
        </div>

        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label "> @lang('purchase.vat_no') </label>
            <input type="number" class="form-control" name="store_acc_tax_no" id="store_acc_tax_no" value="{{$purchase_request->store_acc_tax_no}}" readonly>
        </div>

        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label"> @lang('purchase.payment_method')  </label>
            <select class="form-select form-control" name="store_vou_pay_type" id="store_vou_pay_type" disabled>
                <option value="" selected> choose</option>    
                @foreach($payemnt_method_list as $p_method)
                <option value="{{$p_method->system_code}}" {{($purchase_request->store_vou_pay_type == $p_method->system_code_id? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
           <label for="recipient-name" class="col-form-label">@lang('purchase.note') </label>
            <textarea rows="1" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value="" readonly>{{$purchase_request->tore_vou_notes }}</textarea>
        </div>
        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label">  الفرع المستلم </label>
            <select class="form-select form-control is-invalid" name="from_req_dest_branch" id="from_req_dest_branch" required>
                <option value="" selected> choose</option>  
                @foreach($branch_list as $branch)
                <option value="{{$branch->branch_id}}" data-vendorname="{{ $branch->getBranchName() }}" > {{ $branch->getBranchName() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="recipient-name" class="col-form-label ">   المستودع المستلم </label>
            <select class="form-select form-control" name="from_req_dest_store" id="from_req_dest_store"  disabled>
                <option value="" selected> choose</option>  
                @foreach($warehouses_type_list as $w_t)
                <option value="{{$w_t->system_code}}" {{($purchase_request->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br>
    <div class="row card">
        <div class="col-md-12">
            <div class="mb-3"> 
                <div class="table-responsive">
                    @include('store.trans.trans.table.trans_req_item_table')  
                </div>
            </div>
        </div>
        
    </div>
</div>

