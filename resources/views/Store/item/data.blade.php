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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_item_modal">
                                    <i class="fe fe-plus mr-2"></i>  @lang('storeItem.add_item_button')
                                </button>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-md-3">
                                <h4 class="modal-title" style="text-align:right">
                                 @lang('sales.sales_item') 
                                 </h4>
                            </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped item_table">
                        <thead  class="thead-light">
                        <tr>
                            <th>No</th>
                            <th> @lang('storeItem.branch') </th>
                            <th> @lang('storeItem.item_code') </th>
                            <th> @lang('storeItem.item_name_a') </th>
                            <th> @lang('storeItem.item_name_e') </th>
                            <th> @lang('storeItem.item_desc') </th>
                            <th> @lang('storeItem.item_code_1') </th>
                            <th> @lang('storeItem.item_code_2') </th>
                            <th> @lang('storeItem.item_location') </th>
                            <th> @lang('storeItem.item_price_cost') </th>
                            <th> @lang('storeItem.item_price_sales') </th>
                            <th> @lang('storeItem.item_balance')  </th>
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
@include('store.item.create') 

<script type="text/javascript">
    
    $(function () {
        company_id = '{{ $company->company_id }}';
        console.log(company_id);

        url = "{{ route('store-item.data.table', ':id') }}";
        url = url.replace(':id', company_id);
        search_param = { 
            company_id: $('#company_id').val(), 
            warehouses_type: $('#warehouses_type').val(), 
            branch_id: $('#branch_id').val(),
            item_code : $('#item_code_s').val(),
            item_vendor_code : $('#item_vendor_code_s').val(),
            item_code_1 : $('#item_code_1_s').val(),
            item_code_2 : $('#item_code_2_s').val(),
            item_name_a: $('#item_name_a_s').val(),
            item_name_e: $('#item_name_e_s').val(),

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
                {data: 'branch', name: 'branch'},
                {data: 'item_code', name: 'item_code'},
                {data: 'item_name_a', name: 'item_name_a'},
                {data: 'item_name_e', name: 'item_name_e'},
                {data: 'item_desc', name: 'item_desc'},
                {data: 'item_code_1', name: 'item_code_1'},
                {data: 'item_code_2', name: 'item_code_2'},
                {data: 'item_location', name: 'item_location'},
                {data: 'item_price_cost', name: 'item_price_cost'},
                {data: 'item_price_sales', name: 'item_price_sales'},
                {data: 'item_balance', name: 'item_balance'},
               
                
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