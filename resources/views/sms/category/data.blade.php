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
                                    <i class="fe fe-plus mr-2"></i>  @lang('sms.add_sms_button')
                                </button>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-md-3">
                        <h4 class="modal-title" style="text-align:right">
                            @lang('sms.sms_data') 
                        </h4>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped category_table">
                        <thead  class="thead-light">
                        <tr>
                            <th>No</th>
                            <th> @lang('sms.company') </th>
                            <th> @lang('sms.provider_name') </th>
                            <th> @lang('sms.sms_category_type') </th>
                            <th> @lang('sms.sms_name_ar') </th>
                            <th> @lang('sms.sms_name_en') </th>
                            
                            <th> @lang('sms.sms_is_sms') </th>
                            <th> @lang('sms.sms_is_whatsapp') </th>
                            <th> @lang('sms.sms_is_email') </th>
                            <th> @lang('sms.sms_is_notification') </th>
                            <th> @lang('sms.sms_status') </th>
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
@include('sms.category.create')
<div id="edit_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    </div>
</div> 

<script type="text/javascript">
    
    $(function () {
        company_id = '{{ $company->company_id }}';
        console.log(company_id);

        url = "{{ route('sms-category.data.table', ':id') }}";
        url = url.replace(':id', company_id);
        search_param = { 
            company_id: $('#company_id').val(),
            sms_provider_id: $('#sms_provider_id').val(), 
        };
        
        var table = $('.category_table').DataTable({
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
                {data: 'company', name: 'company'},
                {data: 'provider', name: 'provider'},
                {data: 'sms_category_type', name: 'sms_category_type'},
                {data: 'sms_name_ar', name: 'sms_name_ar'},
                {data: 'sms_name_en', name: 'sms_name_en'},
                

                {data: 'sms_is_sms', name: 'sms_is_sms'},
                {data: 'sms_is_whatsapp', name: 'sms_is_whatsapp'},
                {data: 'sms_is_email', name: 'sms_is_email'},
                {data: 'sms_is_notification', name: 'sms_is_notification'},
                {data: 'sms_is_active', name: 'sms_is_active'},
                

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