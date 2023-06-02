<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> Perfect :: @lang('home.login')</title>

    <!-- Bootstrap Core and vandor -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}"/>

    <!-- Core css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/theme1.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}"/>

</head>
<body class="font-montserrat sidebar_dark @if(app()->getLocale() == 'ar')) rtl @else '' @endif">

<div class="auth" id="app">
    <div class="auth_left">
        <div class="card" style="@if(app()->getLocale() == 'ar') min-width:400px; right: 10px; @endif">
            <div class="text-center mb-2">
                <a class="header-brand" href="#"><i class="Logn"></i></a>
            </div>
            <div class="card-body">
                <div class="card-title">@lang('home.login')</div>
                <div class="alert alert-danger alert-block" v-if="phone_error_message">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>@{{phone_error_message}}</strong>
                </div>


                @include('Includes.flash-messages')
                <form action="{{ route('login.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control is-invalid" placeholder="@lang('home.phone')"
                               value="{{ old('user_mobile') }}" v-model="user_mobile" name="user_mobile" required
                               id="user_mobile"
                               @keyup="getUserMainCompanyWithCompanies()">
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('home.password')<a href="forgot-password.html"
                                                                           class="float-right small">
                                @lang('home.i_forget_password')</a></label>
                        <input type="password" id="user_password" class="form-control is-invalid"
                               placeholder="@lang('home.password')" name="user_password"
                               value="{{ old('user_password') }}" required>
                    </div>

                    <div class="form-group">
                        <select class="form-control" v-if="companies_group.length > 0" @change="getCompanies()"
                                v-model="company_group_id" required>
                            <option :value="company_group.company_group_id" v-for="company_group in companies_group">
                                @{{ company_group.company_group_ar }}
                            </option>
                        </select>

                        <input type="text" readonly id="company_group" class="form-control"
                               v-model="main_company.company_group_ar"
                               v-bind:class="{ 'is-invalid' : company_group_message }" v-else>
                    </div>

                    <div class="form-group">
                        <select class="custom-select" v-model="company_id" name="company_id" required
                                id="company_id" @change="getCompanyBranches()" v-if="companies.length > 0">
                            <option value="">@lang('home.choose')</option>
                            <option v-for="company in companies" :value="company.company_id">@{{ company.company_name_ar
                                }}
                            </option>
                        </select>


                        <input type="text" id="" class="form-control" readonly
                               v-model="company.company_name_ar" v-bind:class="{ 'is-invalid' : company_message }"
                               v-else>

                        <input type="hidden" name="company_id" :value="company.company_id"
                               v-if="Object.keys(this.company).length">

                    </div>

                    <div class="form-group">
                        <select class="custom-select is-invalid" id="branch_id" required
                                :disabled="disable_branch" v-model="branch_id">

                            <option value="">@lang('home.choose')</option>
                            <option v-for="branch in branches" :value="branch.branch_id"
                                    :selected="branch.branch_id == user.user_default_branch_id">
                                @{{branch.branch_name_ar }}
                            </option>
                        </select>

                        <input type="hidden" name="branch_id" v-model="branch_id">
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary btn-block" title="">@lang('home.sign_in')</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="auth_right">
        <div class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/images/slider3.svg') }}" class="img-fluid" alt="login page"/>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/slider2.svg') }}" class="img-fluid" alt="login page"/>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/slider3.svg') }}" class="img-fluid" alt="login page"/>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/bundles/lib.vendor.bundle.js') }}"></script>
<script src="{{ asset('assets/js/core.js') }}"></script>


<script>
    $(document).ready(function () {

        $('#user_mobile').keyup(function () {
            if ($('#user_mobile').val().length < 10) {
                $(this).addClass('is-invalid')
            } else {
                $(this).removeClass('is-invalid')
            }
        })

        $('#user_password').keyup(function () {
            if ($('#user_password').val().length < 6) {
                $(this).addClass('is-invalid')
            } else {
                $(this).removeClass('is-invalid')
            }
        })

        $('#company_id').change(function () {
            if (!$('#company_id').val()) {
                $(this).addClass('is-invalid')
            } else {
                $(this).removeClass('is-invalid')
            }
        })

        $('#branch_id').change(function () {
            if (!$('#branch_id').val()) {
                $(this).addClass('is-invalid')
            } else {
                $(this).removeClass('is-invalid')
            }
        })

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script>
    new Vue({
        el: '#app',
        data: {
            companies: {},
            main_company: {},
            user_mobile: '',
            branches: {},
            user: {},
            company: {},
            company_id: '',
            phone_error_message: '',
            companies_group: {},
            company_group_id: '',
            disable_branch: false,
            branch_id: '',
            defaultBranch: {}
        },
        methods: {
            getUserMainCompanyWithCompanies() {
                this.disable_branch = false
                this.companies_group = {}
                this.companies = {}
                this.branches = {}
                this.company = {}
                this.main_company = {}
                this.branches = {}
                this.user = {}
                this.company_id = ''
                this.defaultBranch = false
                this.branch_id = ''

                if (this.user_mobile.length == 10) {
                    $.ajax({
                        type: 'GET',
                        data: {user_mobile: this.user_mobile},
                        url: "{{ route('get-user-mainCompany-Companies') }}"
                    }).then(response => {
                        if (response.status == '500') {
                            this.phone_error_message = response.data

                            this.companies_group = {}
                            this.companies = {}
                            this.branches = {}
                            this.company = {}
                            this.main_company = {}
                            this.branches = {}
                            this.user = {}
                            this.company_id = ''
                            this.disable_branch = false
                        } else {
                            if (response.companies_group) {
                                this.companies_group = response.companies_group

                                this.companies = {}
                                this.branches = {}
                                this.company = {}
                                this.main_company = {}
                                this.branches = {}
                                this.user = {}
                                this.company_id = ''
                                this.disable_branch = false
                            } else if (response.companies) {
                                this.main_company = response.company_group
                                this.companies = response.companies
                                this.user = response.data

                                this.branches = {}
                                this.company = {}
                                this.companies_group = {}
                                this.company_id = ''
                                this.disable_branch = false

                            } else {
                                console.log('a')
                                this.company = response.company
                                this.main_company = response.company_group
                                this.branches = response.branches
                                this.user = response.data
                                this.defaultBranch = response.defaultBranch


                                console.log(this.defaultBranch)

                                if (this.defaultBranch) {
                                    this.disable_branch = true
                                    this.branch_id = this.user.user_default_branch_id

                                }else{
                                    this.disable_branch = false
                                }

                                this.companies_group = {}
                                this.companies = {}
                                this.company_id = ''
                            }

                        }
                    })
                }
            },
            getCompanies() {
                $.ajax({
                    type: 'GET',
                    data: {id: this.company_group_id},
                    url: "{{ route('api.company-group.companies') }}"
                }).then(response => {
                    this.companies = response.data
                })
            },
            getCompanyBranches() {
                $.ajax({
                    type: 'GET',
                    data: {company_id: this.company_id},
                    url: "{{ route('get-company-branches') }}"
                }).then(response => {
                    this.branches = response.branches
                })
            }
        },
        computed: {
            company_group_message: function () {
                // `this` points to the vm instance
                if (Object.keys(this.main_company).length == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            company_message: function () {
                // `this` points to the vm instance
                if (Object.keys(this.company).length == 0) {
                    return true;
                } else {
                    return false;
                }
            },
        }
    });
</script>

</body>
</html>
