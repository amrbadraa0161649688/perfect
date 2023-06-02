<div class="tab-content mt-3" id="app">
    <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="card-options">

                </div>
            </div>
            <div class="col-md-12">
                <div class="header-action">
                    @foreach(session('job')->permissions as $job_permission)
                        @if($job_permission->app_menu_id == 64 && $job_permission->permission_add)
                            <div class="header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#create_receive_modal">
                                    <i class="fe fe-plus mr-2"></i> @lang('purchase.add_receive_button')
                                </button>

                                <a href="{{route('stations-storePurchaseReceiving.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i> @lang('purchase.add_receive_stations')
                                </a>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-md-3">
                                <h4 class="modal-title" style="text-align:right">
                                 @lang('sales.sales_ins') 
                                 </h4>
                            </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped receiving_table">
                        <thead  class="thead-light">
                        <tr>
                            <th>No</th>
                            <th> @lang('purchase.branch') </th>
                            <th> @lang('purchase.warehouse_type') </th>
                            <th> @lang('purchase.receive_no') </th>
                            <th> @lang('purchase.create_date') </th>
                            <th> @lang('purchase.vendor_name') </th>
                            <th> @lang('purchase.payment_method') </th>
                           
                            <th> @lang('purchase.status') </th>
                            <th> @lang('purchase.total') </th>
                            <th>@lang('home.paid')</th>
                            <th>@lang('home.bond')</th>
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
@include('store.purchase.receiving.create_receive')


<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            company_id = '{{ $company->company_id }}';
            console.log(company_id);

            url = "{{ route('store-purchase-receiving.data.table', ':id') }}";
            url = url.replace(':id', company_id);

            search_param = {
                company_id: $('#company_id').val(),
                warehouses_type: $('#warehouses_type').val(),
                branch_id: $('#branch_id').val(),
            };

            var table = $('.receiving_table').DataTable({
                dom: 'Blfrtip',

                buttons: [
                    //{ extend: 'csv',  charset: 'UTF-8', bom: true, filename: 'item export', title: 'item export' },
                    {
                        extend: 'excel',
                        text: '{{ trans('purchase.excel_button') }}',
                        charset: 'UTF-8',
                        fieldSeparator: ';',
                        bom: true,
                        filename: 'item export',
                        title: 'item export'
                    },
                    {
                        extend: 'print',
                        text: '{{ trans('purchase.print_button') }}',
                        charset: 'UTF-8',
                        fieldSeparator: ';',
                        bom: true,
                        filename: 'item export',
                        title: 'الاصناف'
                    },
                    {
                        extend: 'colvis',
                        text: '{{ trans('purchase.colvis') }}',
                        charset: 'UTF-8',
                        fieldSeparator: ';',
                        bom: true,
                        filename: 'item export',
                        title: 'item export'
                    }
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
                    url: url,
                    data: {
                        search: search_param,
                    },
                },

                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'branch', name: 'branch'},
                    {data: 'warahouse_type', name: 'warahouse_type'},
                    {data: 'store_hd_code', name: 'store_hd_code'},
                    {data: 'store_vou_date', name: 'store_vou_date'},
                    {data: 'vendor', name: 'vendor'},
                    {data: 'payment_method', name: 'payment_method'},
                  
                    {data: 'store_vou_status', name: 'store_vou_status'},
                    {data: 'store_vou_total', name: 'store_vou_total'},
                    {data: 'payment', name: 'payment'},
                    {data: 'bond', name: 'bond'},

                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
            table.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    })
</script>
