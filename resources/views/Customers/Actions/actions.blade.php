<a class="btn btn-icon" href="{{route('Customers.edit',$row->customer_id)}}"
    title="@lang('home.edit')">
     <i class="fa fa-eye"></i>
 </a>

<a href="{{route('car-rent.customers.block' ,$row->customer_id )}}"
   class="btn btn-primary btn-sm"
   title="@lang('home.block')">
    <i class="fa fa-ban"></i>
</a>