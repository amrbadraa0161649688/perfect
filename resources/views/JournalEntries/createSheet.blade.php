@extends('Layouts.master')

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <form action="{{route('journal-entries.storeFromSheet')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <p class="">@lang('home.add_daily_restrictions')</p>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('journal-entries.export') }}" class="btn btn-lg btn-primary">
                                        @lang('home.export_file')</a>
                                </div>
                            </div>

                            <div class="row">
                                {{--الشركات--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.companies')</label>
                                        <select class="form-control" name="company_id"
                                                v-model="company_id" @change="getBranches()" required>
                                            <option>@lang('home.choose')</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}">{{app()->getLocale() == 'ar' ?
                                                $company->company_name_ar : $company->company_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{--الفروع--}}
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.branch')</label>
                                        <select name="branch_id" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            <option v-for="branch in branches" :value="branch.branch_id">
                                                @if(app()->getLocale()=='ar')
                                                    @{{ branch.branch_name_ar }}
                                                @else
                                                    @{{  branch.branch_name_en }}
                                                @endif
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                {{--انواع يوميات قيود الحسابات--}}
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.daily_accounts_type')</label>
                                        <select name="journal_type_id" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($journal_types as $journal_type)
                                                <option value="{{ $journal_type->system_code_id }}">
                                                    {{ app()->getLocale()=='ar' ?
                                                $journal_type->system_code_name_ar :
                                                $journal_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{--تاريخ اليوميه--}}
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.date')</label>
                                        <input type="date" class="form-control" name="journal_hd_date" required>
                                    </div>
                                </div>

                                {{--رقم الملف--}}
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.file_serial')</label>
                                        <input type="number" class="form-control" placeholder="10001"
                                               name="journal_file_no" required>
                                    </div>
                                </div>

                                {{--المستخدم--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.user')</label>
                                        <input type="text" class="form-control"
                                               value="{{ app()->getLocale() == 'ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}" disabled="">
                                    </div>
                                </div>

                                {{--تاريخ اليوم--}}
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.date')</label>
                                        <input type="text" id="restriction_date"
                                               class="form-control" disabled="">
                                    </div>
                                </div>

                                {{--حاله القيود اليوميه--}}
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.restriction_account_status')</label>
                                        <select class="custom-select" name="journal_status" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($journal_statuses as $journal_status)
                                                <option value="{{$journal_status->system_code_id}}">
                                                    {{app()->getLocale()=='ar' ? $journal_status->system_code_name_ar
                                                    : $journal_status->system_code_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.file')</label>
                                        <input type="file" class="form-control" name="file">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <label>@lang('home.notes')</label>
                                <textarea class="form-control" name="journal_hd_notes"
                                          required> </textarea>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" id="submit"
                                        class="btn btn-primary">@lang('home.add')</button>

                                <div class="spinner-border" role="status" style="display: none">
                                    <span class="sr-only">Loading...</span>
                                </div>

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

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#restriction_date').val(output)
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                company_id: '',
                branches: {},
            },
            methods: {
                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.branches") }}'
                    }).then(response => {
                        this.branches = response.data
                    })
                },
            }
        })
    </script>
@endsection
