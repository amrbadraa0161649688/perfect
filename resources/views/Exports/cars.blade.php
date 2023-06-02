<div class="table-responsive">
    <table class="table table-bordered" style="width:100%!important">
        <thead style="background-color: #ece5e7">
        <tr class="red" style="font-size: 16px;font-style: inherit">
            <th>رقم السياره</th>
            <th>كود السياره</th>
            <th>اسم السياره</th>
            <th>رقم اللوحه</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cars as $car)
            <tr>
                <td>{{$car->truck_id}}</td>
                <td>{{$car->truck_code}}</td>
                <td>{{$car->truck_name}}</td>
                <td>{{$car->truck_plate_no}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>