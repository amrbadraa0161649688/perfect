<table class="table table-hover table-striped table-vcenter  yajra-datatable">
    <thead style="background-color: #ece5e7">
    <tr class="red">
        <th>@lang('waybill.waybill_no')</th>

        <th>@lang('waybill.waybill_no')</th>
       
        <th>@lang('waybill.customer_name')</th>

        <th>@lang('waybill.waybill_date')</th>
        <th>@lang('waybill.waybill_expect')</th>

        <th>@lang('waybill.waybill_amount')</th>
        <th>@lang('waybill.waybill_vat_amount')</th>
        <th>@lang('waybill.waybill_total')</th>

        <th>@lang('waybill.waybill_fees_road')</th>

        <th>@lang('waybill.waybill_status')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($way_pills as $way_pill)
        <tr>
            <td>{{ $way_pill->waybill_ticket_no }}</td>

            <td>{{ $way_pill->waybill_code }}</td>
           
           
            <td>
                @if($way_pill->customer)
                    {{app()->getLocale()=='ar' ? $way_pill->customer->customer_name_full_ar
                : $way_pill->customer->customer_name_full_en }}
                @endif
            </td>

            <td>{{ $way_pill->waybill_load_date }}</td>
            <td>{{ $way_pill->waybill_delivery_expected }}</td>
            <td> {{ number_format($way_pill->waybill_total_amount  - $way_pill->waybill_vat_amount ,2) }} </td>
            <td>{{  number_format($way_pill->waybill_vat_amount,2) }}</td>
            <td>{{  number_format($way_pill->waybill_total_amount,2) }}</td>

            <td>{{  number_format($way_pill->waybill_fees_total,2) }}</td>

            <td>{{app()->getLocale()=='ar' ?  $way_pill->status->system_code_name_ar :
                                    $way_pill->status->system_code_name_en }}</td>

        </tr>
    @endforeach

    </tbody>

</table>
