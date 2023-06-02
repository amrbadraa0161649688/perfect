<!-- PRICE WIDGET -->
<div class="row clearfix">
    <div class="col-lg-4 col-md-4">
        <div class="card text-center" style="background: #65A30D;">
            <div class="card-body ribbon text-light">
                @if(optional($benzine_91_data['price'])->status !='PENDING')
                    <h3> 
                        {{$benzine_91_data['fees']}} ريال
                    </h3>
                    <span>بنزين 91</span>
                    <i class="fa fa-rotate-left" data-toggle="tooltip" title="" data-original-title="" onclick="editPrice('{{$benzine_91->system_code}}',{{$branch->station_id}})"></i>
                @else
                    <h3>
                        {{$benzine_91_data['fees']}} ريال 
                        <div class="ribbon-box orange" >  PENDING </div>
                    </h3>
                    <span>بنزين 91</span>
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 149 && $job_permission->permission_update)
                                <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_91_data['id'] }}')"></i>
                                <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_91_data['id'] }}')"></i>
                            @endif
                        @endforeach
                    @else
                        <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_91_data['id'] }}')"></i>
                        <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_91_data['id'] }}')"></i>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card text-center" style="background: #EF4444;">
            <div class="card-body ribbon text-light">
               
                @if($benzine_95_data['price']->status !='PENDING')
                    <h3>
                        {{$benzine_95_data['fees']}} ريال
                    </h3>
                    <span>بنزين 95</span>
                    <i class="fa fa-rotate-left" data-toggle="tooltip" title="" data-original-title="" onclick="editPrice('{{$benzine_95->system_code}}',{{$branch->station_id}})"></i>
                @else
                    <h3>
                        {{$benzine_95_data['fees']}} ريال
                        <div class="ribbon-box orange" >  PENDING </div>
                       
                    </h3>
                    <span>بنزين 95</span>
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 149 && $job_permission->permission_update)
                            <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_95_data['id'] }}')"></i>
                            <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_95_data['id'] }}')"></i>
                            @endif
                        @endforeach
                    @else
                        <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_95_data['id'] }}')"></i>
                        <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_95_data['id'] }}')"></i>
                    @endif
                @endif
                
                
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card text-center" style="background: #eab308;">
            <div class="card-body ribbon text-light">
                @if($diesel_data['price']->status !='PENDING')
                    <h3> 
                        {{$diesel_data['fees']}} ريال
                        </h3>
                    <span>ديزل</span>
                    <i class="fa fa-rotate-left" data-toggle="tooltip" title="" data-original-title="" onclick="editPrice({{$diesel->system_code}},{{$branch->station_id}})"></i>
                @else
                    <h3> 
                        {{$diesel_data['fees']}} ريال
                        <div class="ribbon-box orange" >  PENDING </div>
                    </h3>
                    <span>ديزل</span>
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 149 && $job_permission->permission_update)
                                <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $diesel_data['id'] }}')"></i>
                                <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $diesel_data['id'] }}')"></i>
                            @endif
                        @endforeach
                    @else
                    <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $diesel_data['id'] }}')"></i>
                    <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $diesel_data['id'] }}')"></i>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<!-- END price WIDGET -->
<!-- THANK WIDGET -->
    @include('FuelStation.chart.tank')
<!-- End THANK WIDGET -->
<!-- DUEL SALES -->
    @include('FuelStation.chart.sales')
<!-- END FUEL SALES -->

    

    <!-- DATA TABLE -->
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> سجل العمليات </h3>
                    <div class="card-options">
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped transaction_table">
                            <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th> رقم العملية </th>
                                <th> نوع الوقود </th>
                                <th> الية الدفع </th>
                                <th> السعر  </th>
                                <th> المضخة </th>
                                <th> القيمة </th>
                                <th> عددالليترات </th>
                                <th> الموظف </th>
                                <th> تاريخ العملية </th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END DATATABLE -->
</div>



<div id="update_price_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    </div>
</div> 


<script type="text/javascript">
    function editPrice(fuelType,station) {

        $.ajax({
            type: 'get',
            url : '{{ route('fuel-station.edit.price')}}',
            data:{
                _token : "{{ csrf_token() }}",
                fuel_type : fuelType,
                station_id : station,
            },
            beforeSend: function () {
                //App.startPageLoading({animate: true});
            }
        }).done(function(data){

            if(data.success==false){
                toastr.warning(data.msg);
            }
            $('#update_price_modal .modal-dialog').html('');
            $('#update_price_modal').modal("show").on('shown.bs.modal', function () {
                $('#update_price_modal .modal-dialog').html(data.view);
            });

        });

    }

    function updatePrice()
    {
        // if($('#update_price_modal .is-invalid').length > 0){
        //     return toastr.warning('تاكد من ادخال كافة الحقول');
        // }
        //console.log($('#update_price_modal .is-invalid').length);
        url = '{{ route('fuel-station.update.price') }}'
        var form = new FormData($('#fuel_price_form')[0]);
        form.append('company_id', $('#company_id').val());

        var data = form  ; 
        $.ajax({
            type: 'POST',
            url : url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            
        }).done(function(data){
            if(data.success)
            {
                toastr.success(data.msg);
                getData();
                closeItemModal();
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function confirmPrice(status,priceDtId)
    {
        url = '{{ route('fuel-station.confirm.price') }}'
        $.ajax({
            type: 'POST',
            url : url,
            data: {
                "_token": "{{ csrf_token() }}",
                'status_m' : status,
                'price_list_dt_id_m' : priceDtId,
            },
            
            
        }).done(function(data){
            if(data.success)
            {
                toastr.success(data.msg);
                getData();
                closeItemModal();
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function closeItemModal()
    {
        $('#update_price_modal').on('hidden.bs.modal', function () {
            //$('#update_price_modal .modal-body').html('');
        });
        $('#update_price_modal').modal('hide');
    }

</script> 
<!-- tabel -->
<script type="text/javascript">
    $(function () 
    {
        url = "{{ route('fuel-station.data.table') }}";
        search_param = { 
            company_id: $('#company_id').val(), 
            branch_id: $('#branch_id').val(),
            start_date: $('#start_date').val(), 
            end_date: $('#end_date').val()
        };

        var table = $('.transaction_table').DataTable({
            dom: 'Blfrtip',
            
            buttons: [
                //{ extend: 'csv',  charset: 'UTF-8', bom: true, filename: 'item export', title: 'item export' },
                { extend: 'excel', text: '{{ trans('purchase.excel_button') }}' , charset: 'UTF-8', fieldSeparator: ';', bom: true, filename: 'item export', title: 'item export' },
                { extend: 'print', text: '{{ trans('purchase.print_button') }}' , charset: 'UTF-8', fieldSeparator: ';', bom: true, filename: 'item export', title: 'الاصناف' },
                { extend: 'colvis', text: '{{ trans('purchase.colvis') }}' , charset: 'UTF-8', fieldSeparator: ';', bom: true, filename: 'item export', title: 'item export' }
            ],
            language: {
                search: "بحث",
                processing: "جاري البحث....",
                info: " ",
                infoEmpty: " ",
                paginate: {
                    first: "الاول",
                    previous: "السابق",
                    next: "التالي",
                    last: "الاخير"
                },
                aria: {
                    sortAscending: ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            },
            processing: true,
            serverSide: true,
            ajax: {
                url :  url,
                data :{
                    search : search_param,
                },
            },
            
            columns: [
                
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'transactionId', name: 'transactionId'},
                {data: 'fuelType', name: 'fuelType'},
                {data: 'paymentMethod', name: 'paymentMethod'},
                {data: 'price', name: 'price'},
                {data: 'nozzleId', name: 'nozzleId'},
                {data: 'amount', name: 'amount'},
                {data: 'volume', name: 'volume'},
            
                {data: 'employeeId', name: 'employeeId'},
                {data: 'transactionDate', name: 'transactionDate'},
                
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
        table.buttons().container().appendTo( '#example_wrapper .col-md-6:eq(0)' );
    });
</script>
<!-- End Table -->









