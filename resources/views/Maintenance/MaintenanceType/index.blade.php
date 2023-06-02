@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}

@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <form action="">
                        @if(session('company_group'))
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ session('company_group')['company_group_ar'] }} @else
                            {{ session('company_group')['company_group_en'] }} @endif" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="">
                        <select class="form-control" name="company_id" onchange="this.form.submit()">
                            <option value="">@lang('home.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                        @if(request()->company_id == $company->company_id) selected @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>

                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
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
                                    @if($job_permission->app_menu_id == 57 && $job_permission->permission_add)
                                        <div class="header-action">
                                            <a href="{{route('maintenance-type.create')}}" class="btn btn-primary">
                                                <i class="fe fe-plus mr-2"></i> @lang('maintenanceType.add_new_mntns_type')
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                       
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped maintenance_type_table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th> @lang('maintenanceType.company') </th>
                                        <th> @lang('maintenanceType.mntns_card_type') </th>
                                        <th> @lang('maintenanceType.mntns_type_name') </th>
                                        <th> @lang('maintenanceType.mntns_type') </th>
                                        <th> @lang('maintenanceType.mntns_type_code') </th>
                                        <th> @lang('maintenanceType.mntns_type_hours')</th>
                                        <th> @lang('maintenanceType.mntns_type_emp_no') </th>
                                        <th> @lang('maintenanceType.mntns_type_value') </th>
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
        </div>
    </div>
@endsection

@section('scripts')


    <script type="text/javascript">
        @if(session('company'))
            var company_id = {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
        @else
            var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif

        $(function () {

            var table = $('.maintenance_type_table').DataTable({
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
                ajax: "?company_id=" + company_id,
                columns: [
                    
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'company', name: 'company'},
                    {data: 'mntns_card_type', name: 'mntns_card_type'},
                    {data: 'mntns_type_name', name: 'mntns_type_name'},
                    {data: 'type_cat', name: 'type_cat'},
                    {data: 'mntns_type_code', name: 'mntns_type_code'},
                    {data: 'mntns_type_hours', name: 'mntns_type_hours'},
                    {data: 'mntns_type_emp_no', name: 'mntns_type_emp_no'},
                    {data: 'mntns_type_value', name: 'mntns_type_value'},
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

    <script>
        
        $(document).ready(function () {
            function show(el) {
                var x = el.id;
                $("#app-" + x).css("display", "block");
                $("#app-" + x).siblings().css('display', 'none')
            }
        })
    </script>
@endsection

