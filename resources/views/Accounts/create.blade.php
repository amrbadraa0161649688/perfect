@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <form class="card" action="{{ route('accounts.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <h3 class="card-title">@lang('home.add_account')</h3>
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
                                               placeholder="@lang('home.account_name_ar')" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_name_en')</label>
                                        <input type="text" class="form-control" name="acc_name_en"
                                               placeholder="@lang('home.account_name_en')" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.acc_level')</label>
                                        <select class="form-control" name="acc_level" v-model="acc_level"
                                                @change="getParents(); getSerial()" required>
                                            <option value="">@lang('home.choose')</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5"> 5</option>
                                        </select>
                                        <small class="text-danger" v-if="error_message">@{{ error_message }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.parents')</label>

                                        <select class="form-control" name="acc_parent" :required="parent_required"
                                                v-model="acc_parent" @change="getSerial()">
                                            <option value="">@lang('home.choose')</option>
                                            <option v-for="account in parent_accounts" :value="account.acc_id"
                                                    :selected="account.acc_id == parent_account.acc_id"
                                                    @if(isset($parent_account)) :selected="account.acc_id == {!! $parent_account->acc_id  !!}"
                                                    @endif>
                                                @if(app()->getLocale()=='ar')
                                                    @{{account.acc_name_ar}} - @{{ account.acc_code }}
                                                @else
                                                    @{{account.acc_name_en}} - @{{ account.acc_code }}
                                                @endif
                                            </option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.acc_code')</label>


                                        <input type="text" class="form-control" placeholder="@lang('home.acc_code')"
                                               required v-model="acc_code" disabled="">

                                        <input type="hidden" class="form-control"
                                               name="acc_code" required :value="acc_code">

                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_sheet')</label>
                                        <select class="form-control" name="acc_sheet" required v-model="acc_sheet">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($account_sheets as $account_sheet)
                                                <option value="{{$account_sheet->system_code_id}}"
                                                        @if(isset($parent_account))
                                                        @if($parent_account->acc_sheet == $account_sheet->system_code_id)
                                                        selected @endif @endif>{{ app()->getLocale() == 'ar' ?
                                                 $account_sheet->system_code_name_ar : $account_sheet->system_code_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.account_type')</label>
                                        <select class="form-control" name="acc_type" required v-model="acc_type">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($account_types as $account_type)
                                                <option value="{{$account_type->system_code_id}}"
                                                        @if(isset($parent_account))
                                                        @if($parent_account->acc_type == $account_type->system_code_id)
                                                        selected @endif @endif>{{ app()->getLocale() == 'ar' ?
                                                 $account_type->system_code_name_ar : $account_type->system_code_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.status')</label>
                                        <select class="form-control custom-select" name="acc_is_active" required>
                                            <option value="1">on</option>
                                            <option value="0">off</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label">@lang('home.companies')</label>
                                        <select class="form-control custom-select" name="company_id[]" required
                                                multiple>
                                            <option value="">@lang('home.companies')</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}">{{app()->getLocale() == 'ar' ?
                                                $company->company_name_ar : $company->company_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" id="submit" class="btn btn-primary">
                                @lang('home.add_account')</button>
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
                {{--parent_account:{!! isset($parent_account) ? $parent_account : '' !!},--}}
                parent_account: '',
                acc_level: '',
                parent_accounts: {},
                error_message: '',
                acc_parent: {!! request()->account_id !!},
                acc_code: '',
                acc_sheet: '',
                acc_type: '',
            },
            mounted() {
                if (this.acc_parent > 0) {
                    this.getParentAccount()
                }
            },
            methods: {
                getParentAccount() {
                    $.ajax({
                        type: 'GET',
                        data: {acc_parent: this.acc_parent},
                        url: '{{ route("api.accounts.getSerial") }}'
                    }).then(response => {
                        this.acc_code = response.data
                        this.acc_sheet = response.account_parent.acc_sheet
                        this.acc_type = response.account_parent.acc_type
                        this.acc_level = parseInt(response.account_parent.acc_level) + 1
                        if (this.acc_level) {
                            this.getParents()
                        }
                    })
                },
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
                getSerial() {
                    this.acc_code = ''
                    this.acc_sheet = ''
                    this.acc_type = ''
                    if (this.acc_level == 1) {
                        $.ajax({
                            type: 'GET',
                            data: {acc_level: this.acc_level},
                            url: '{{ route("api.accounts.getSerial") }}'
                        }).then(response => {
                            this.acc_code = response.data
                        })
                    } else if (this.acc_level && this.acc_level > 1 && this.acc_parent) {
                        $.ajax({
                            type: 'GET',
                            data: {acc_level: this.acc_level, acc_parent: this.acc_parent},
                            url: '{{ route("api.accounts.getSerial") }}'
                        }).then(response => {
                            this.acc_code = response.data
                            this.acc_sheet = response.account_parent.acc_sheet
                            this.acc_type = response.account_parent.acc_type
                        })
                    }
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
