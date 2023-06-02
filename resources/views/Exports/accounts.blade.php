<div class="table-responsive">
    <table class="table table-bordered" style="width:100%!important">
        <thead style="background-color: #ece5e7">
        <tr class="red" style="font-size: 16px;font-style: inherit">
            <th>رقم الحساب</th>
            <th>كود الحساب</th>
            <th>اسم الحساب</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accounts as $account)
            <tr>
                <td>{{$account->acc_id}}</td>
                <td>{{$account->acc_code}}</td>
                <td>{{$account->acc_name_ar}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>