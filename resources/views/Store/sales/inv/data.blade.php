{{--<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="card-options">

                </div>
            </div>
            <div class="col-md-12">
                <div class="header-action">
                    @foreach(session('job')->permissions as $job_permission)
                        @if($job_permission->app_menu_id == 65 && $job_permission->permission_add)
                            <div class="header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#create_inv_modal">
                                    <i class="fe fe-plus mr-2"></i> @lang('sales.add_inv_button')
                                </button>
                            </div>

                        @endif
                    @endforeach

                    <div class="col-md-3">
                        <h4 class="modal-title" style="text-align:right">
                            @lang('sales.sales_invoice')
                        </h4>
                    </div>
                </div>
            </div>
            <div class="row">

            </div>
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-striped table-lg inv_table">
                        <thead class="thead-light">
                        <tr>

                            <th>No</th>
                            <th> @lang('sales.branch') </th>
                            <th> @lang('sales.warehouse_type') </th>
                            <th> @lang('sales.inv_no') </th>
                            <th> @lang('sales.create_date') </th>
                            <th> @lang('sales.customer_name') </th>
                            <th> @lang('sales.customer_mobile') </th>
                            <th> @lang('sales.payment_method') </th>

                            <th style="font-size: 16px ;font-weight: bold; color: blue"> @lang('sales.status') </th>
                            <th> @lang('sales.total') </th>
                            <th> @lang('home.paid') </th>
                            <th> @lang('home.bond') </th>
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
</div>--}}

@include('store.sales.inv.create_inv')


<script type="text/javascript">

    {{--$(function () {--}}
    {{--company_id = '{{ $company->company_id }}';--}}
    {{--console.log(company_id);--}}

    {{--url = "{{ route('store-sales-inv.data.table', ':id') }}";--}}
    {{--url = url.replace(':id', company_id);--}}
    {{--search_param = {--}}
    {{--company_id: $('#company_id').val(),--}}
    {{--warehouses_type: $('#warehouses_type').val(),--}}
    {{--branch_id: $('#branch_id').val(),--}}
    {{--};--}}

    {{--var table = $('.inv_table').DataTable({--}}
    {{--dom: 'Blfrtip',--}}

    {{--buttons: [--}}
    {{--//{ extend: 'csv',  charset: 'UTF-8', bom: true, filename: 'item export', title: 'item export' },--}}
    {{--{--}}
    {{--extend: 'excel',--}}
    {{--text: '{{ trans('sales.excel_button') }}',--}}
    {{--charset: 'UTF-8',--}}
    {{--fieldSeparator: ';',--}}
    {{--bom: true,--}}
    {{--filename: 'item export',--}}
    {{--title: 'item export'--}}
    {{--},--}}
    {{--{--}}
    {{--extend: 'print',--}}
    {{--text: '{{ trans('sales.print_button') }}',--}}
    {{--charset: 'UTF-8',--}}
    {{--fieldSeparator: ';',--}}
    {{--bom: true,--}}
    {{--filename: 'item export',--}}
    {{--title: 'الاصناف'--}}
    {{--},--}}
    {{--{--}}
    {{--extend: 'colvis',--}}
    {{--text: '{{ trans('sales.colvis') }}',--}}
    {{--charset: 'UTF-8',--}}
    {{--fieldSeparator: ';',--}}
    {{--bom: true,--}}
    {{--filename: 'item export',--}}
    {{--title: 'item export'--}}
    {{--}--}}
    {{--],--}}

    {{--language: {--}}

    {{--search: "بحث",--}}

    {{--info: " ",--}}
    {{--infoEmpty: " ",--}}

    {{--paginate: {--}}
    {{--first: "الاول",--}}
    {{--previous: "السابق",--}}
    {{--next: "التالي",--}}
    {{--last: "الاخير"--}}
    {{--},--}}
    {{--processing: "جاري البحث....",--}}
    {{--aria: {--}}
    {{--sortAscending: ": activer pour trier la colonne par ordre croissant",--}}
    {{--sortDescending: ": activer pour trier la colonne par ordre décroissant"--}}
    {{--}--}}
    {{--},--}}


    {{--ajax: {--}}
    {{--url: url,--}}
    {{--data: {--}}
    {{--search: search_param,--}}
    {{--},--}}
    {{--},--}}

    {{--processing: true,--}}
    {{--serverSide: true,--}}

    {{--columns: [--}}

    {{--{data: 'DT_RowIndex', name: 'DT_RowIndex'},--}}
    {{--{data: 'branch', name: 'branch_name_ar'},--}}
    {{--{data: 'warahouse_type', name: 'warahouse_type'},--}}
    {{--{data: 'store_hd_code', name: 'store_hd_code'},--}}
    {{--{data: 'store_vou_date', name: 'store_vou_date'},--}}
    {{--{data: 'customer', name: 'customer'},--}}
    {{--{data: 'customer_mobile', name: 'customer_mobile'},--}}
    {{--{data: 'payment_method', name: 'payment_method'},--}}

    {{--{data: 'store_vou_status', name: 'store_vou_status'},--}}
    {{--{data: 'store_vou_total', name: 'store_vou_total'},--}}
    {{--{data: 'payment', name: 'payment'},--}}
    {{--{data: 'bond', name: 'bond'},--}}

    {{--{--}}
    {{--data: 'action',--}}
    {{--name: 'action',--}}
    {{--orderable: true,--}}

    {{--searchable: true--}}
    {{--},--}}
    {{--]--}}

    {{--});--}}
    {{--table.buttons().container().appendTo('#example_wrapper .col-md-8:eq(0)');--}}
    {{--});--}}
</script>