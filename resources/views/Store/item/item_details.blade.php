<div class="col-md-12">
    <div class="mb-3">
        <div class="card-header">
            <h3 class="card-title"> @lang('storeItem.balance_summary')</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered item_table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th> @lang('storeItem.branch') </th>
                        <th> @lang('storeItem.item_code') </th>
                        <th> @lang('storeItem.item_name_a') </th>
                        <th> @lang('storeItem.item_name_e') </th>
                        <th> @lang('storeItem.item_desc') </th>
                        <th> @lang('storeItem.item_code_1') </th>
                        <th> @lang('storeItem.item_code_2') </th>
                        <th> @lang('storeItem.item_location') </th>
                        <th> @lang('storeItem.item_price_cost') </th>
                        <th> @lang('storeItem.item_price_sales') </th>
                        <th> @lang('storeItem.item_balance')  </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $key => $r)
                        <tr>
                            <td> {{ $key + 1 }} </td>
                            <td> {{ optional($r->branch)->getBranchName() }} </td>
                            <td> {{ $r->item_code }} </td>
                            <td> {{ $r->item_name_a }} </td>
                            <td> {{ $r->item_name_e }} </td>
                            <td> {{ $r->item_desc }} </td>
                            <td> {{ $r->item_code_1 }} </td>
                            <td> {{ $r->item_code_2 }} </td>
                            <td> {{ $r->item_location }} </td>
                            <td> {{ $r->item_price_cost }} </td>
                            <td> {{ $r->item_price_sales }} </td>
                            <td> {{ $r->item_balance }} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="card-header">
            <h3 class="card-title"> @lang('storeItem.receving_summary') </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered item_table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th> @lang('storeItem.purchase_receving_no')   </th>
                        <th> @lang('storeItem.vendor')   </th>
                        <th> @lang('storeItem.qty') </th>
                        <th> @lang('storeItem.reciveing_date') </th>
                        <th> @lang('storeItem.reciving_price') </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($recining_history as $key => $r)
                        <tr>
                            <td> {{ $key + 1 }} </td>
                            <td>  {{ $r->purchase->store_hd_code}} </td>
                            <td> {{ optional($r->purchase->vendor)->getCustomerName() }} <!-- - {{ $r->purchase->store_acc_no}}--> </td>
                            <td> 
                            @if($r->storeVouType->system_code =='62003')
                                <?php $qty = $r->store_vou_qnt_i ?>
                                {{ $r->store_vou_qnt_i }} 
                            @else
                                <?php $qty = $r->store_vou_qnt_t_i ?>
                                {{ $r->store_vou_qnt_t_i }} 
                            @endif
                            </td>
                            <td> {{ $r->purchase->created_date->format('Y-m-d H:m') }} </td>
                            <td> {{ (($r->store_vou_price_net-$r->store_vou_vat_amount)/$qty) }} </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>