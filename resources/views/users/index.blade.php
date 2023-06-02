@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}

@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            @include('Includes.form-errors')

            <div class="row mb-3">
                <div class="col-md-3">
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 25 && $job_permission->permission_add)
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal">
                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_user')
                                </button>
                                </button>
                            @endif
                        @endforeach
                    @else
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#exampleModal">
                            <i class="fe fe-plus mr-2"></i>@lang('home.add_user')
                        </button>
                    @endif
                </div>

                <div class="col-md-4">
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

                <div class="col-md-4">
                    <form action="">
                        <select class="form-control" name="company_id"
                                onchange="this.form.submit()">
                            <option value="">choose</option>
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
        </div>

        {{--pop up to create--}}
        <div class="modal fade" id="exampleModal" tabindex="-1"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="width: 800px">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('home.add_user')</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.store') }}" method="post"
                              enctype="multipart/form-data" id="submit_user_form">
                            @csrf

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col md 6">
                                        <label for="message-text"
                                               class="col-form-label"> @lang('home.sub_company')</label>
                                        <select class="form-select form-control is-invalid" name="company_id"
                                                aria-label="Default select example" id="company_id"
                                                required>
                                            <option value="">choose</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->company_id }}">
                                                    @if(app()->getLocale() == 'ar') {{ $company->company_name_ar }}
                                                    @else {{ $company->company_name_en }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">@lang('home.photo')</h3>
                                    </div>
                                    <div class="card-body">
                                        <input type="file" id="dropify-event"
                                               name="user_profile_url">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_ar') </label>
                                        <input type="text" class="form-control is-invalid" name="user_name_ar"
                                               value="{{ old('user_name_ar') }}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                               id="user_name_ar" placeholder="@lang('home.name_ar')">

                                    </div>
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_en') </label>
                                        <input type="text" class="form-control is-invalid" name="user_name_en"
                                               value="{{ old('user_name_en') }}"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                               id="user_name_en" placeholder="@lang('home.name_en')">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.email') </label>
                                        <input type="email" class="form-control is-invalid" name="user_email"
                                               value="{{ old('user_email') }}"
                                               id="user_email" placeholder="@lang('home.email')">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.mobile_number') </label>
                                        <input type="number" class="form-control is-invalid" name="user_mobile"
                                               value="{{ old('user_mobile') }}"
                                               placeholder="@lang('home.mobile_number')" id="user_mobile">
                                    </div>

                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.start_date') </label>
                                        <input type="date" class="form-control is-invalid" name="user_start_date"
                                               id="user_start_date" placeholder="@lang('home.start_date')">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.end_date')
                                        </label>
                                        <input type="date" class="form-control is-invalid" name="user_end_date"
                                               placeholder="@lang('home.end_date')" id="user_end_date">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col md 6">
                                        <label for="message-text"
                                               class="col-form-label"> @lang('home.code')</label>
                                        <input type="text" class="form-control is-invalid" name="user_code"
                                               value="{{ old('user_code') }}"
                                               id="user_code" placeholder="@lang('home.code')">
                                    </div>
                                    <div class="col md 6">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.password')
                                        </label>
                                        <input type="password" class="form-control is-invalid" name="user_password"
                                               placeholder="@lang('home.password')" id="user_password">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="create_user">@lang('home.save')</button>
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">@lang('home.close')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <div class="card-options">

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered yajra-datatable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th></th>
                                    <th>@lang('home.name_ar')</th>
                                    <th>@lang('home.name_en')</th>
                                    <th>@lang('home.sub_company')</th>
                                    <th>@lang('home.user_code')</th>
                                    <th>@lang('home.created_date')</th>
                                    <th>@lang('home.mobile_number')</th>
                                    <th>@lang('home.email')</th>
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

@endsection

@section('scripts')
    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
                @else
        var company_id =
                        {{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
                        @endif
                        $(function () {
                            var table = $('.yajra-datatable').DataTable({
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
                                ajax: "users-add?company_id=" + company_id,
                                columns: [
                                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                    {data: 'photo', name: 'photo'},
                                    {data: 'user_name_ar', name: 'user_name_ar'},
                                    {data: 'user_name_en', name: 'user_name_en'},
                                    {data: 'company', name: 'company'},
                                    {data: 'user_code', name: 'user_code'},
                                    {data: 'user_start_date', name: 'user_start_date'},
                                    {data: 'user_mobile', name: 'user_mobile'},
                                    {data: 'user_email', name: 'user_email'},
                                    {data: 'action', name: 'action'},
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

            $('#user_mobile_search').keyup(function () {
                if ($('#user_mobile_search').val().length >= 10) {
                    $('#search_form').submit()
                }
            });


            //    validation to create modal
            $('#user_name_ar').keyup(function () {
                if ($('#user_name_ar').val().length < 3) {
                    $('#user_name_ar').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_ar').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_name_en').keyup(function () {
                if ($('#user_name_en').val().length < 3) {
                    $('#user_name_en').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_en').removeClass('is-invalid')
                    $('#create_user').removeAttr('disabled');
                }
            });


            $('#user_email').keyup(function () {
                if (!validEmail($('#user_email').val())) {
                    $('#user_email').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_email').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });


            $('#user_mobile').keyup(function () {
                if ($('#user_mobile').val().length < 10) {
                    $('#user_mobile').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_mobile').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_code').keyup(function () {
                if ($('#user_code').val().length < 3) {
                    $('#user_code').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_code').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_password').keyup(function () {
                if ($('#user_password').val().length < 6) {
                    $('#user_password').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_password').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_start_date').change(function () {
                $('#user_start_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#user_end_date').change(function () {
                $('#user_end_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#company_group_id').change(function () {
                if (!$('#company_group_id').val()) {
                    $('#company_group_id').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#company_group_id').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#company_id').change(function () {
                if (!$('#company_id').val()) {
                    $('#company_id').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#company_id').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });


            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                companies: {},
                company_group_id: "",
            },
            methods: {
                getCompanies() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.company_group_id},
                        url: '{{ route("api.company-group.companies") }}'
                    }).then(response => {
                        this.companies = response.data
                    })

                }
            }
        })

    </script>
@endsection

