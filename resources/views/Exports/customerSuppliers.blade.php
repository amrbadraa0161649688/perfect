<div class="table-responsive">
    <table class="table table-bordered" style="width:100%!important">
        <thead style="background-color: #ece5e7">
        <tr class="red" style="font-size: 16px;font-style: inherit">
            <th>رقم العميل</th>
            <th>اسم العميل</th>
            <th>الفئه</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr>
                <td>{{$customer->customer_id}}</td>
                <td>{{$customer->customer_name_full_ar}}</td>
                <td>{{$customer->customer_category == 2 ? 'customer' : 'supplier'}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>