<div class="table-responsive">
    <table class="table table-bordered" style="width:100%!important">
        <thead style="background-color: #ece5e7">
        <tr class="red" style="font-size: 16px;font-style: inherit">
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employees as $employee)
            <tr>
                <td>{{$employee->emp_id}}</td>
                <td>{{$employee->emp_name_full_ar}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
