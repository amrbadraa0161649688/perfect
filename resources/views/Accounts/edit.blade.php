@extends('Layouts.master')

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <form class="card" action="{{ route('accounts.update',$account->acc_id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <h3 class="card-title">@lang('home.update_account')</h3>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.main_company')</label>
                                        @if(session('company'))
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{
                                        app()->getLocale()=='ar' ? session('company')->companyGroup->company_group_ar
                                       :session('company')->companyGroup->company_group_en }}">
                                        @else
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{
                                       app()->getLocale()=='ar' ? auth()->user()->companyGroup->company_group_ar
                                        : auth()->user()->companyGroup->company_group_en }}">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_name_ar')</label>
                                        <input type="text" class="form-control" name="acc_name_ar"
                                               value="{{ $account->acc_name_ar }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_name_en')</label>
                                        <input type="text" class="form-control" name="acc_name_en"
                                               value="{{ $account->acc_name_en }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.acc_code')</label>
                                        <input type="text" class="form-control" value="{{ $account->acc_code }}"
                                               name="acc_code" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.acc_level')</label>
                                        <select class="form-control" name="acc_level" v-model="acc_level"
                                                @change="getParents()" required>
                                            <option value="1" @if($account->acc_level == 1) selected @endif>1</option>
                                            <option value="2" @if($account->acc_level == 2) selected @endif>2</option>
                                            <option value="3" @if($account->acc_level == 3) selected @endif>3</option>
                                            <option value="4" @if($account->acc_level == 4) selected @endif>4</option>
                                            <option value="5" @if($account->acc_level == 5) selected @endif>5</option>
                                        </select>
                                        <small class="text-danger" v-if="error_message">@{{ error_message }}</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.parents')</label>
                                        <select class="form-control" name="acc_parent" :required="parent_required"
                                                v-model="acc_parent">
                                            <option value="">@lang('home.choose')</option>
                                            <option v-for="account in parent_accounts" :value="account.acc_id">
                                                @if(app()->getLocale()=='ar')
                                                    @{{account.acc_name_ar}}
                                                @else
                                                    @{{account.acc_name_en}}
                                                @endif
                                            </option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_sheet')</label>
                                        <select class="form-control" name="acc_sheet" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($account_sheets as $account_sheet)
                                                <option @if($account->acc_sheet == $account_sheet->system_code_id) selected
                                                        @endif
                                                        value="{{$account_sheet->system_code_id}}">{{ app()->getLocale() == 'ar' ?
                                                 $account_sheet->system_code_name_ar : $account_sheet->system_code_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_type')</label>
                                        <select class="form-control" name="acc_type" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($account_types as $account_type)
                                                <option @if($account->acc_type == $account_type->system_code_id) selected
                                                        @endif
                                                        value="{{$account_type->system_code_id}}">{{ app()->getLocale() == 'ar' ?
                                                 $account_type->system_code_name_ar : $account_type->system_code_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.status')</label>
                                        <select class="form-control custom-select" name="acc_is_active" required>
                                            <option value="1" @if($account->acc_is_active) selected @endif>on</option>
                                            <option value="0" @if(!$account->acc_is_active) selected @endif>off</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label">@lang('home.companies')</label>
                                        <select class="form-control custom-select" multiple name="company_id[]">
                                            <option value="">@lang('home.companies')</option>
                                            @foreach($companies_stat as $company)
                                                <option value="{{$company->company_id}}"
                                                        @foreach($account->companies as $account_company)
                                                        @if($account_company->company_id == $company->company_id) selected
                                                        @endif @endforeach>{{app()->getLocale() == 'ar' ?
                                                $company->company_name_ar : $company->company_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" id="submit" class="btn btn-primary">
                                @lang('home.update_account')</button>
                            <div class="spinner-border" role="status" style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('form').submit(function () {
                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                account:{!! $account !!},
                acc_level: '',
                parent_accounts: {},
                acc_parent: '',
                error_message: ''
            },
            mounted() {
                this.acc_level = this.account.acc_level
                this.acc_parent = this.account.acc_parent
                this.getParents()

            },
            methods: {
                getParents() {
                    this.error_message = ''
                    this.parent_accounts = {}
                    $.ajax({
                        type: 'GET',
                        data: {acc_level: this.acc_level},
                        url: '{{ route("api.accounts.getParents") }}'
                    }).then(response => {
                        if (response.status == 500) {
                            this.error_message = response.message
                        } else {
                            this.parent_accounts = response.data
                        }
                    })
                },
            },
            computed: {
                parent_required: function () {
                    if (this.acc_level > 1) {
                        return true
                    } else {
                        return false
                    }
                },
            }
        })
    </script>
@endsection
