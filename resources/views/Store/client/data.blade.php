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
                        @if($job_permission->app_menu_id == 61 && $job_permission->permission_add)
                            <div class="header-action">
                                <a href="{{route('store-client.create')}}"  class="btn btn-primary" >
                                    <i class="fe fe-plus mr-2"></i>  @lang('home.add_customer')
                                </a>
                            </div> 
                        @endif
                    @endforeach
                    <div class="col-md-3">
                                <h4 class="modal-title" style="text-align:right">
                                 @lang('sales.sales_cus') 
                                 </h4>
                            </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped  item_table">
                        <thead  class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>@lang('customer.customer_no')</th>
                            <th>@lang('customer.customer_name')</th>
                            <th>@lang('customer.vat_no')</th>
                            <th>@lang('customer.customer_id')</th>
                            <th>@lang('customer.customer_mobile')</th>
                            <th>@lang('customer.customer_email')</th>
                            <th>@lang('customer.customer_account')</th>
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

        url = "{{ route('store-client.data.table', ':id') }}";
        url = url.replace(':id', company_id);
        search_param = { 
            company_id: $('#company_id').val(), 
            branch_id: $('#branch_id').val(),
        };
        
        var table = $('.item_table').DataTable({
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
                {data: 'customer_id', name: 'customer_id'},
                {data: 'customer_name_full_ar', name: 'customer_name_full_ar'},
                {data: 'customer_vat_no', name: 'customer_vat_no'},
                {data: 'customer_identity', name: 'customer_identity'},
                {data: 'customer_mobile', name: 'customer_mobile'},
                {data: 'customer_email', name: 'customer_email'},
                {data: 'account_id', name: 'account_id'},
                
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