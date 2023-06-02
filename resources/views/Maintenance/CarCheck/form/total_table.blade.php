<table class="table table-bordered card_table">
    <tbody>
    <tr>
        <th colspan="12" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.total')
        </th>
    <tr>
    <tr>
        <th colspan="3" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.disc')
        </th>
        <th colspan="2" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.vat')
        </th>
        <th colspan="2" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.total')
        </th>

        <th colspan="1" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.mntns_card_paid')
        </th>

        <th colspan="1" style="text-align: center;background-color: #113f50;color: white;">
            @lang('maintenanceType.mntns_card_amount')
        </th>


    </tr>
    <tr>
        <td class="ctd" colspan="3">
            <div id="g_total_disc_amount_div"> {{ $card->internalSumDisc()  + $card->partSumDisc() }} </div>
        </td>
        <td class="ctd" colspan="2">
            <div id="g_total_vat_amount_div"> {{ $card->internalSumVat() + $card->externalSumVat() + $card->partSumVat() +
             $card->card_total_vat_from_inv}} </div>
        </td>
        <td class="ctd" colspan="2">
            <div id="g_total_amount_div"> {{ $card->internalSumTotal() + $card->externalSumTotal() + $card->partSumTotal()
             +  $card->card_total_val_from_inv}} </div>
        </td>

        <td class="ctd" colspan="1">
            <div id="g_total_payment_div">  {{ $card->mntns_cards_payment_amount }} </div>
        </td>


        <td class="ctd" colspan="1">
            <div id="g_total_due_div">  {{ $card->mntns_cards_due_amount }} </div>
        </td>

    </tr>
    </tbody>
</table>