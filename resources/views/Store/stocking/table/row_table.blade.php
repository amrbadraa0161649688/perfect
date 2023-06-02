<?php 
    $item_row_count = 0;
?>
@foreach($header->details()->orderBy('updated_date')->get() as $key => $d)
    <?php 
        $item_row_count =  floatval($item_row_count) + 1;
    ?>
    <tr id="showResult">
        <td class="ctd"> {{ $key + 1 }} </td>
        <td class="ctd"> {{$d->item->item_code}}  </td>
        <td class="ctd"> {{$d->item->item_name_e}} <br> {{$d->item->item_name_a}}  </td>
        <td class="ctd"> 
            <input type="text" class="form-control" name="stocking_item_location_{{ $d->uuid }}" id="stocking_item_location_{{ $d->uuid }}" value="{{$d->store_vou_loc}}"> 
        </td>
        <td class="ctd"> 
            <input type="number" class="form-control" name="stocking_qty_{{ $d->uuid }}" id="stocking_qty_{{ $d->uuid }}" value="{{ $d->store_vou_qnt }}">
        </td>
        <td class="ctd"> 
            <button type="button"  class="btn  m-btn m-btn--icon m-btn--icon-only" onclick="updateQty('{{ $d->uuid }}')"><i class="fa fa-save"></i></button>
            <button type="button"  class="btn  m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem('{{ $d->uuid }}')"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
@endforeach