@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>  @lang('maintenanceType.m_cards') </h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_all}}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-car"></i>  </span> </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>@lang('maintenanceType.mntns_card_q_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_q}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> </span> </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_o_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_o}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> </span>  </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_c_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_card_c}}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-car"></i> </span> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            <div class="col-md-3">
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

            <div class="col-md-3">
                <form action="">
                    <select class="selectpicker" multiple data-live-search="true"
                            data-actions-box="true" name="mntns_cars_search" id="mntns_cars_seach"
                            onchange="this.form.submit()">
                        <option value="">@lang('home.choose')</option>
                        @foreach($car_list as $car_lists)
                            <option value="{{$car_lists->mntns_cars_id}}"
                                    @if(request()->mntns_cars_id == $car_lists->mntns_cars_plate_no) selected @endif>
                                @if(app()->getLocale() == 'ar')
                                    {{$car_lists->mntns_cars_type}}
                                @else
                                    {{$car_lists->mntns_cars_type}}
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
                

                    <div class="col-md-12">
                        <div class="header-action">
                        @if(auth()->user()->user_type_id != 1)
                            @foreach(session('job')->permissions as $job_permission)
                                @if($job_permission->app_menu_id == 58 && $job_permission->permission_add)
                                    <div class="header-action">
                                        <!-- <button onclick="addCard()" class="btn btn-primary">
                                            <i class="fe fe-plus mr-2"></i> كرت صيانة جديد
                                        </button> -->
                                        <a href="{{route('maintenanceCardCheck.create')}}" class="btn btn-primary btn-lg">{{__('Add New Card')}}</a>
                                    </div>
                                @endif
                            @endforeach

                            @else
                            <div class="header-action">
                                        <!-- <button onclick="addCard()" class="btn btn-primary">
                                            <i class="fe fe-plus mr-2"></i> كرت صيانة جديد
                                        </button> -->
                                        <a href="{{route('maintenanceCardCheck.create')}}" class="btn btn-primary btn-lg">{{__('Add New Card')}}</a>
                                    </div>
                                    @endif

                            <div class="col-md-3">
                                <h4 class="modal-title" style="text-align:right">
                                    @lang('maintenanceType.m_cards')
                                </h4>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped   card_table">
                                <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>@lang('maintenanceType.mntns_card_no')</th>
                                    <th>@lang('maintenanceType.mntns_card_types')</th>
                                    <th>@lang('maintenanceType.mntns_card_customer')</th>
                                    <th>@lang('maintenanceType.mntns_card_cus_type')</th>
                                    <th>@lang('maintenanceType.mntns_card_mobile')</th>
                                    <th></th>
                                    <th>@lang('maintenanceType.mntns_card_plate')</th>
                                    <th>@lang('maintenanceType.mntns_card_status')</th>
                                    <th>@lang('maintenanceType.mntns_card_paid')</th>
                                    <th>@lang('maintenanceType.mntns_card_amount')</th>


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

    @include('Maintenance.CarCheck.form.add_card')
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}

                @else
        var company_id = {{ request()->company_id ? request()->company_id : auth()->user()->company_id }}

                @endif

            $(function () {
                lang = '{{app()->getLocale()}}';
                console.log(lang);

                var table = $('.card_table').DataTable({
                    language: {
                        search: "البحث",
                        Show: "",
                        info: " ",
                        infoEmpty: " ",
                        paginate: {
                            first: "الاول",
                            previous: "السابق",
                            next: "التالي",
                            last: "الاخير",
                            processing: "جاري البحث...."
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
                        {data: 'card_no', name: 'card_no'},
                        {data: 'card_type', name: 'card_type'},
                        {data: 'customer', name: 'customer'},
                        {data: 'customer_type', name: 'customer_type'},
                        {data: 'customer_mobile', name: 'customer_mobile'},
                        {data: 'truckname', name: 'truckname'},
                        {data: 'car', name: 'car'},
                        {data: 'status', name: 'status'},
                        {data: 'payment', name: 'payment'},
                        {data: 'due', name: 'due'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        },
                    ]
                });

            });


        function addCard() {

            $.ajax({
                type: 'GET',
                url: "{{route('maintenance-card.card.create')}}",
                data: {
                    _token: "{{ csrf_token() }}",
                },
            }).done(function (data) {
                if (data.success) {
                    $('#add_card_modal').modal("show").on('shown.bs.modal', function () {
                        $('#add_card_modal .modal-body').html(data.view.content);
                    });
                }
                else {
                    toastr.warning('ERORR !');
                }
            });
        }

        function closeItemModal() {
            $('#add_card_modal').on('hidden.bs.modal', function () {
                //$('#add_card_modal .modal-body').html('');
            });
            $('#add_card_modal').modal('hide');
        }
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


    <script type="text/javascript">

        $('#mntns_cards_type').change(function () {
            if (!$('#mntns_cards_type').val()) {
                $('#mntns_cards_type').addClass('is-invalid')
            } else {
                $('#mntns_cards_type').removeClass('is-invalid')
            }
        });

        $('#mntns_cards_category').change(function () {
            if (!$('#mntns_cards_category').val()) {
                $('#mntns_cards_category').addClass('is-invalid')
            } else {
                $('#mntns_cards_category').removeClass('is-invalid')
            }
        });

        $('#mntns_cards_customer_type').change(function () {
            if (!$('#mntns_cards_customer_type').val()) {
                $('#mntns_cards_customer_type').addClass('is-invalid')
            } else {
                $('#mntns_cards_customer_type').removeClass('is-invalid')
            }
        });

        $('#customer_id').change(function () {
            console.log($('#customer_id').val());
            if (!$('#customer_id').val()) {
                $('#customer_id').addClass('is-invalid');
                $('.customer').addClass("is-invalid");
            } else {
                $('#customer_id').removeClass('is-invalid');
                $('.customer').removeClass("is-invalid");

            }
        });

        $('#mntns_cars_id').change(function () {
            if (!$('#mntns_cars_id').val()) {
                $('#mntns_cars_id').addClass('is-invalid');
                $('.car').addClass("is-invalid");
            } else {
                $('#mntns_cars_id').removeClass('is-invalid');
                $('.car').removeClass("is-invalid");
            }
        });

        $('#mntns_cars_meter').keyup(function () {
            if ($('#mntns_cars_meter').val().length < 2) {
                $('#mntns_cars_meter').addClass('is-invalid')
            } else {
                $('#mntns_cars_meter').removeClass('is-invalid');
            }
        });


        // get cusotmer registerd car 
        $('#customer_id').on('change', function () {
            //console.log('changed');
            $("#mntns_cars_id option").remove();
            var customer_id = $('#customer_id').val();
            $.ajax({
                url: '{{ route( 'maintenanced-car.get.car.list.by.customer.id' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer_id": customer_id
                },
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    $('#mntns_cars_id').append($('<option>', {value: '', text: 'choose'}));
                    $.each(result.data, function (k) {
                        data = result.data;
                        // console.log('changed'+ result.data[k].brand.system_code_name_ar + '-' + data[k].mntns_cars_color + '-' + data[k].mntns_cars_plate_no);
                        // console.log(lang);

                        if (lang == 'ar') {
                            $('#mntns_cars_id').append($('<option>', {
                                value: data[k].mntns_cars_id,
                                text: result.data[k].brand.system_code_name_ar + '-' + data[k].mntns_cars_plate_no + '-' + data[k].mntns_cars_type
                            }));
                        }
                        else {
                            $('#mntns_cars_id').append($('<option>', {
                                value: data[k].mntns_cars_id,
                                text: result.data[k].brand.system_code_name_en + '-' + data[k].mntns_cars_plate_no + '-' + data[k].mntns_cars_type
                            }));
                        }


                    });
                    $('#mntns_cars_id').selectpicker('refresh');
                },
                error: function () {
                    //handle errors
                    alert('error...');
                }
            });
        });

        //get customer list base on cusomter type
        $('#mntns_cards_customer_type').on('change', function () {
            //console.log('changed');
            $("#customer_id option").remove();
            var customer_type = $('#mntns_cards_customer_type').val();
            $.ajax({
                url: '{{ route( 'maintenance-card.get.customer.by.type' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "customer_type": customer_type
                },
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    $('#customer_id').append($('<option>', {value: '', text: 'choose'}));
                    $.each(result.data, function (k) {
                        //console.log(result.data[k].mntns_cars_id);
                        if (lang == 'ar') {
                            $('#customer_id').append($('<option>', {
                                value: result.data[k].customer_id,
                                text: result.data[k].customer_name_full_ar
                            }));
                        }
                        else {
                            $('#customer_id').append($('<option>', {
                                value: result.data[k].customer_id,
                                text: result.data[k].customer_name_full_en
                            }));
                        }
                    });
                    $('#customer_id').selectpicker('refresh');

                },
                error: function () {
                    //handle errors
                    alert('error...');
                }
            });
        });

        function saveCard() {
            if ($('.is-invalid').length > 0) {
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }

            url = '{{ route('maintenance-card.card.store') }}'
            var form = new FormData($('#card_data_form')[0]);
            var data = form;
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    url = '{{ route("maintenance-card.edit", ":id") }}';
                    url = url.replace(':id', data.uuid);
                    window.location.href = url;
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>





@endsection

