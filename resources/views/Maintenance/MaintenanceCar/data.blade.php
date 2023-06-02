<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="card-options">
                    
                </div>
            </div>
            <div class="col-md-2">
                <div class="header-action">
                    @foreach(session('job')->permissions as $job_permission)
                        @if($job_permission->app_menu_id == 59 && $job_permission->permission_add)
                            <div class="header-action">
                                <a href="{{route('maintenance-car.create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>  @lang('maintenanceCar.maintenance_car_add')
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped  car_table">
                        <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th> @lang('maintenanceCar.customer_id') </th>
                            <th> @lang('maintenanceCar.mntns_cars_brand_id') </th>
                            <th> @lang('maintenanceCar.mntns_cars_plate_no') </th>
                            <th> @lang('maintenanceCar.mntns_cars_mobile_no')</th>
                            <th> @lang('maintenanceCar.mntns_cars_con')</th>
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
    company_id = $('#company_id').val();
    $(function () {
        
        company_id = '{{ $company->company_id }}';
        console.log(company_id);
        let url = "{{ route('maintenance-car.data.table', ':id') }}";
        url = url.replace(':id', company_id);
        
        var table = $('.car_table').DataTable({
            dom: 'lfrtip',
            language: {
                search: "بحث",
               
                info: " ", 
                infoEmpty: " ",
                processing: "جاري البحث....",
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
           
            serverSide: true,
            processing: true,
            ajax: url,
            columns: [
                
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'customer', name: 'customer'},
                {data: 'brand', name: 'brand'},
                {data: 'mntns_cars_plate_no', name: 'mntns_cars_plate_no'},
                {data: 'mntns_cars_mobile_no', name: 'mntns_cars_mobile_no'},
                {data: 'truckname', name: 'truckname'},
                
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
    });
</script>