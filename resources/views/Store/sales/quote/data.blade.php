<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="card-options">
                    
                </div>
            </div>
            <div class="col-md-12">
                <div class="header-action">
                    @foreach(session('job')->permissions as $job_permission)
                        @if($job_permission->app_menu_id == 93 && $job_permission->permission_add)
                            <div class="header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create_quote_modal">
                                    <i class="fe fe-plus mr-2"></i>  @lang('sales.add_quote_button')
                                </button>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-md-3">
                                <h4 class="modal-title" style="text-align:right">
                                 @lang('sales.sales_q') 
                                 </h4>
                            </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped quote_table">
                        <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th> @lang('sales.branch') </th>
                            <th> @lang('sales.warehouse_type') </th>
                            <th> @lang('sales.quote_no') </th>
                            <th> @lang('sales.create_date') </th>
                            <th> @lang('sales.customer_name') </th>
                            <th> @lang('sales.customer_mobile') </th>
                            <th> @lang('sales.payment_method') </th>
                            
                            <th> @lang('sales.quote_status') </th>
                            <th> @lang('sales.total') </th>
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
@include('store.sales.quote.create_quote') 


<script type="text/javascript">
    
    $(function () {
        company_id = '{{ $company->company_id }}';
        console.log(company_id);

        url = "{{ route('store-sales-quote.data.table', ':id') }}";
        url = url.replace(':id', company_id);
        search_param = { 
            company_id: $('#company_id').val(), 
            warehouses_type: $('#warehouses_type').val(), 
            branch_id: $('#branch_id').val(),
        };
        
        var table = $('.quote_table').DataTable({
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
                {data: 'branch', name: 'branch'},
                {data: 'warahouse_type', name: 'warahouse_type'},
                {data: 'store_hd_code', name: 'store_hd_code'},
                {data: 'store_vou_date', name: 'store_vou_date'},
                {data: 'customer', name: 'customer'},
                {data: 'customer_mobile', name: 'customer_mobile'},
                {data: 'payment_method', name: 'payment_method'},
               
                {data: 'store_vou_status', name: 'store_vou_status'},
                {data: 'store_vou_total', name: 'store_vou_total'},
                
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