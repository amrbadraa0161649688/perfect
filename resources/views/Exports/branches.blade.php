<div class="table-responsive">
    <table class="table table-bordered" style="width:100%!important">
        <thead style="background-color: #ece5e7">
        <tr class="red" style="font-size: 16px;font-style: inherit">
            <th>كود الفرع</th>
            <th>اسم الفرع</th>
        </tr>
        </thead>
        <tbody>
        @foreach($branches as $branch)
            <tr>
                <td>{{$branch->branch_id}}</td>
                <td>{{$branch->branch_name_ar}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
