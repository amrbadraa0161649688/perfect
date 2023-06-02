<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="card-options">
                    
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered car_table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th> @lang('sales_car.car_branch') </th>
                            <th> @lang('sales_car.car_brand') </th>
                            <th> @lang('sales_car.car_sales_cars_brand_dt_id') </th>
                            <th> @lang('sales_car.car_model') </th>
                            <th> @lang('sales_car.car_color') </th>
                            <th> @lang('sales_car.car_plate_no') </th>
                            <th> @lang('sales_car.car_chasie_no') </th>
                            <th> @lang('sales_car.car_add_amount') </th>
                            <th> @lang('sales_car.car_disc_amount') </th>
                            <th> @lang('sales_car.car_total_amount') </th>
                            <th> @lang('sales_car.car_sales_amount') </th>
                            <th> @lang('sales_car.car_status') </th>
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

<script type="text/javascript">
    
    $(function () {
        company_id = '{{ $company->company_id }}';
        console.log(company_id);

        url = "{{ route('sales-car.data.table', ':id') }}";
        url = url.replace(':id', company_id);
        search_param = { 
            company_id : $('#company_id').val(), 
            warehouses_type : $('#warehouses_type').val(), 
            branch_id : $('#branch_id').val(),

            sales_cars_brand_id : $('#car_brand_s').val(),
            sales_cars_brand_dt_id : $('#car_sales_cars_brand_dt_id_s').val(),
            sales_car_status : $('#car_status_s').val(),

        };
        
        var table = $('.car_table').DataTable({
            dom: 'Blfrtip',
            
            buttons: [
                //{ extend: 'csv',  charset: 'UTF-8', bom: true, filename: 'item export', title: 'item export' },
                { extend: 'excel', text: '{{ trans('storeItem.excel_button') }}' , charset: 'UTF-8', fieldSeparator: ';', bom: true, filename: 'item export', title: 'item export' },
                { extend: 'print', text: '{{ trans('storeItem.print_button') }}' , charset: 'UTF-8', fieldSeparator: ';', bom: true, filename: 'item export', title: 'الاصناف' },
                { extend: 'colvis', text: '{{ trans('storeItem.colvis') }}' , charset: 'UTF-8', fieldSeparator: ';', bom: true, filename: 'item export', title: 'item export' }
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
                {data: 'branch', name: 'branch'},
                {data: 'brand', name: 'brand'},
                {data: 'brand_dt', name: 'brand_dt'},
               
               
                {data: 'sales_cars_model', name: 'sales_cars_model'},
                {data: 'sales_cars_color', name: 'sales_cars_color'},
                {data: 'sales_cars_plate_no', name: 'sales_cars_plate_no'},
                {data: 'sales_cars_chasie_no', name: 'sales_cars_chasie_no'},
                {data: 'sales_cars_add_amount', name: 'sales_cars_add_amount'},
                {data: 'sales_cars_disc_amount', name: 'sales_cars_disc_amount'},
                {data: 'sales_cars_total_amount', name: 'sales_cars_total_amount'},
                {data: 'sales_cars_sales_amount', name: 'sales_cars_sales_amount'},
                {data: 'sales_cars_status', name: 'sales_cars_status'},
               
                
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