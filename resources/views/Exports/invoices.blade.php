<div class="table-responsive">
    <table class="table table-bordered" style="width:100%!important">
        <thead style="background-color: #ece5e7">
        <tr class="red" style="font-size: 16px;font-style: inherit">
            <th></th>
            <th>@lang('invoice.invoice_no')</th>
            <th>@lang('waybill.waybill_ticket_no')</th>
            <th>@lang('invoice.invoice_date')</th>
            <th>@lang('invoice.sub_company')</th>
            <th>@lang('invoice.customer_name')</th>
            <th>@lang('waybill.waybill_type')</th>
            <th>@lang('invoice.invoice_due_date')</th>
            <th>@lang('waybill.waybill_qut_receved')</th>
        <th>@lang('waybill.waybill_price')</th>
            <th>@lang('waybill.waybill_amount')</th>
           <th>@lang('waybill.waybill_vat_amount')</th>
            <th>@lang('waybill.total')</th>

            
        </tr>
        </thead>
        <tbody>

        @foreach($invoices as $k=>$invoice)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{$invoice->Waybilltickno ? $invoice->Waybilltickno->waybill_ticket_no : ' '}}
                 </td>
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ app()->getLocale()=='ar' ? $invoice->company->company_name_ar :
                                            $invoice->company->company_name_en }}</td>

                <td>{{app()->getLocale()=='ar' ? $invoice->customer->customer_name_full_ar :
                                     $invoice->customer->customer_name_full_en }}</td>
               
                                     <td> @if ( $invoice->invoiceDetail->invoice_item_id  == 76 )
                                        {{'diesel'}} 
                                        @elseif ( ($invoice->invoiceDetail ? $invoice->invoiceDetail->invoice_item_id :'' ) == 77)
                                         {{'petrol 91'}}
                                         @elseif ( ( $invoice->invoiceDetail ? $invoice->invoiceDetail->invoice_item_id :'' ) == 92)
                                         {{'petrol 95'}}
                                         @else {{'----'}} 
                                         @endif </td>

              <td>{{ $invoice->invoice_due_date }}</td>
                <td>{{ $invoice->invoiceDetail ? $invoice->invoiceDetail->invoice_item_quantity :'' }}</td>
                <td>{{ $invoice->invoiceDetail ? $invoice->invoiceDetail->invoice_item_price :'' }}</td>
                <td>{{ $invoice->invoice_amount -  $invoice->invoice_vat_amount }}</td>
                <td>{{ $invoice->invoice_vat_amount }}</td>
                <td>{{ $invoice->invoice_amount }}</td>
               
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
