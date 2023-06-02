
<div class="table-responsive">
    <table class="table table-bordered item_table">
        <thead>
        <tr>
            <th class="ctd">No</th>
            <th class="ctd"> @lang('storeItem.item_code') </th>
            <th class="ctd"> @lang('storeItem.item_name_a') </th>
            <th class="ctd"> @lang('storeItem.item_name_e') </th>
            <th class="ctd"> @lang('storeItem.item_desc') </th>
            <th class="ctd"> @lang('storeItem.item_vendor_code') </th>
            <th class="ctd"> @lang('storeItem.item_code_1') </th>
            <th class="ctd"> @lang('storeItem.item_code_2') </th>
            <th class="ctd"> @lang('storeItem.item_location') </th>
           
            <th class="ctd"> @lang('storeItem.item_price_sales') </th>
            <th class="ctd"> @lang('storeItem.item_balance')  </th>
        </tr>
        </thead>
        <tbody>
            @foreach($result as $key => $r)
                <tr>
                    <td class="ctd"> {{ $key +1 }} </td>
                    <td class="ctd"> {{ $r->item_code }} </td>
                    <td class="ctd"> {{ $r->item_name_a }} </td>
                    <td class="ctd"> {{ $r->item_name_e }} </td>
                    <td class="ctd"> {{ $r->item_desc }} </td>
                    <td class="ctd"> {{ $r->item_vendor_code }} </td>
                    <td class="ctd"> {{ $r->item_code_1 }} </td>
                    <td class="ctd"> {{ $r->item_code_2 }} </td>
                    <td class="ctd"> {{ $r->item_location }} </td>
                    
                    <td class="ctd"> {{ $r->item_price_sales }} </td>
                    <td class="ctd"> {{ $r->item_balance }}  </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>                             

