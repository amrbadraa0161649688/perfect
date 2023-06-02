@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="section-body mt-3">

            <div class="container-fluid">
                <div class="card">

                    <div class="card-body">

                        <div class="row clearfix">

                            {{--العملاء--}}
                            <div class="col-lg-4 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{__('customers')}} </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$customers_j_total}}</span>
                                            </h3>
                                            <a href="{{route('accounts.indexMain')}}?qr=customers" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-user"></i> {{$customers_j_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--الموردين--}}
                            <div class="col-lg-4 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{__('suppliers')}} </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$suppliers_j_total}}</span>
                                            </h3>
                                            <a href="{{route('accounts.indexMain')}}?qr=suppliers" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-user"></i> {{$suppliers_j_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--البنوك--}}
                            <div class="col-lg-4 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{__('banks')}} </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$banks_j_total}}</span>
                                            </h3>
                                            <a href="{{route('accounts.indexMain')}}?qr=banks" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-bank"></i> {{$banks_j_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--الصناديق--}}
                            <div class="col-lg-4 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{__('branches')}} </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$branches_j_total}}</span>
                                            </h3>
                                            <a href="{{route('accounts.indexMain')}}?qr=branches" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-home"></i> {{$branches_j_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--ضريبه القيمه المضافه المحصله--}}
                            <div class="col-lg-4 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{__('vat collect')}} </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$vat_collect_j_total}}</span>
                                            </h3>
                                            <a href="{{route('accounts.indexMain')}}?qr=vatCollect" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-money"></i> {{$vat_collect_j_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{--ضريبه القيمه المضافه المدفوعه--}}
                            <div class="col-lg-4 col-md-6">
                                <form action="">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{__('vat paid')}} </h6>
                                            <h3 class="pt-2">
                                                <span class="counter">{{$vat_paid_j_total}}</span>
                                            </h3>
                                            <a href="{{route('accounts.indexMain')}}?qr=vatPaid" class="text-danger">
                            <span><span class="text-danger mr-2">
                            <i class="fa fa-money"></i> {{$vat_paid_j_count}}</span>  </span>
                                            </a>

                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>


                    </div>
                </div>


                @if(request()->qr)
                    <div class="card">

                        <div class="card-body">
                            <form action="">

                                @if(request()->qr=='customers')
                                    <input type="hidden" name="qr" value="customers">
                                @elseif(request()->qr=='suppliers')
                                    <input type="hidden" name="qr" value="suppliers">
                                @elseif(request()->qr=='banks')
                                    <input type="hidden" name="qr" value="banks">
                                @elseif(request()->qr=='branches')
                                    <input type="hidden" name="qr" value="branches">
                                @elseif(request()->qr=='vatPaid')
                                    <input type="hidden" name="qr" value="vatPaid">
                                @elseif(request()->qr=='vatCollect')
                                    <input type="hidden" name="qr" value="vatCollect">
                                @endif
                                <div class="row clearfix">

                                    {{--الشركات الفرعيه--}}
                                    <div class="col-md-3">
                                        <label class="form-label">@lang('home.companies')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="company_id[]" data-actions-box="true" required>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if(request()->company_id) @foreach(request()->company_id as
                                                     $company_id) @if($company_id == $company->company_id)
                                                        selected @endif @endforeach @else selected @endif>
                                                    {{app()->getLocale()=='ar' ? $company->company_name_ar :
                                                    $company->company_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--الفروع--}}
                                    <div class="col-md-3">
                                        <label class="form-label">@lang('home.branches')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="branch_id[]" data-actions-box="true" required>
                                            @foreach($branches as $branch)
                                                <option value="{{$branch->branch_id}}"
                                                        {{--@if(!request()->branch_id) @if(session('branch')['branch_id'] == $branch->branch_id)--}}
                                                        {{--selected @endif @endif--}}
                                                        @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch_id == $branch->branch_id)
                                                        selected @endif @endforeach @endif>
                                                    {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                                    $branch->branch_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--تاريخ  من والي--}}
                                    <div class="col-md-2">
                                        <label>@lang('home.from')</label>
                                        <input type="date" class="form-control" name="created_date_from"
                                               @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                                @endif>
                                    </div>

                                    <div class="col-md-2">
                                        <label>@lang('home.to')</label>
                                        <input type="date" class="form-control" name="created_date_to"
                                               @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                                    </div>

                                    <div class="col-md-2">
                                        <br>
                                        <br>
                                        <button class="btn btn-primary" type="submit">{{__('filter')}}</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{--table--}}
                <div class="card">

                    <div class="card-body">

                        <div class="row clearfix">

                            @if(request()->qr == 'customers')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('customer name')}}</th>
                                        <th scope="col">{{__('credit')}}</th>
                                        <th scope="col">{{__('debit')}}</th>
                                        <th scope="col">{{__('balance')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customers_journal_dts as $k=>$customers_journal_dt)
                                        <tr>
                                            <td>#</td>
                                            <td>{{\App\Models\Customer::where('customer_id',$k)->first()->customer_name_full_ar}}</td>
                                            <td>{{$customers_journal_dt->sum('journal_dt_credit')}}</td>
                                            <td>{{$customers_journal_dt->sum('journal_dt_debit')}}</td>
                                            <td>{{$customers_journal_dt->sum('journal_dt_debit') - $customers_journal_dt->sum('journal_dt_credit')}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if(request()->qr == 'suppliers')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('supplier name')}}</th>
                                        <th scope="col">{{__('credit')}}</th>
                                        <th scope="col">{{__('debit')}}</th>
                                        <th scope="col">{{__('balance')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($suppliers_journal_dts as $k=>$suppliers_journal_dt)
                                        <tr>
                                            <td>#</td>
                                            <td>{{\App\Models\Customer::where('customer_id',$k)->first()->customer_name_full_ar}}</td>
                                            <td>{{$suppliers_journal_dt->sum('journal_dt_credit')}}</td>
                                            <td>{{$suppliers_journal_dt->sum('journal_dt_debit')}}</td>
                                            <td>{{$suppliers_journal_dt->sum('journal_dt_debit') - $suppliers_journal_dt->sum('journal_dt_credit')}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if(request()->qr == 'banks')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('bank name')}}</th>
                                        <th scope="col">{{__('credit')}}</th>
                                        <th scope="col">{{__('debit')}}</th>
                                        <th scope="col">{{__('balance')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banks_journal_dts as $k=>$banks_journal_dt)
                                        <tr>
                                            <td>#</td>
                                            <td>{{\App\Models\Account::where('acc_id',$k)->first()->acc_name_ar .
                                            \App\Models\Account::where('acc_id',$k)->first()->acc_code}}</td>
                                            <td>{{$banks_journal_dt->sum('journal_dt_credit')}}</td>
                                            <td>{{$banks_journal_dt->sum('journal_dt_debit')}}</td>
                                            <td>{{$banks_journal_dt->sum('journal_dt_debit') - $banks_journal_dt->sum('journal_dt_credit')}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if(request()->qr == 'branches')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('branch name')}}</th>
                                        <th scope="col">{{__('credit')}}</th>
                                        <th scope="col">{{__('debit')}}</th>
                                        <th scope="col">{{__('balance')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($branches_journal_dts as $k=>$branches_journal_dt)
                                        <tr>
                                            <td>#</td>
                                            <td>{{\App\Models\Branch::where('branch_id',$k)->first()->branch_name_ar}}</td>
                                            <td>{{$branches_journal_dt->sum('journal_dt_credit')}}</td>
                                            <td>{{$branches_journal_dt->sum('journal_dt_debit')}}</td>
                                            <td>{{$branches_journal_dt->sum('journal_dt_balance')}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if(request()->qr == 'vatPaid')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('invoice date')}}</th>
                                        <th scope="col">{{__('invoice code')}}</th>
                                        <th scope="col">{{__('invoice val')}}</th>
                                        <th scope="col">{{__('invoice val with vat')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vat_paid_journal_dts as $k=>$vat_paid_journal_dt)
                                        @php $invoice=\App\Models\InvoiceHd::where('invoice_id',$k)->first(); @endphp
                                        @if(isset($invoice))
                                            <tr>
                                                <td>#</td>
                                                <td>{{ $invoice->invoice_date }}</td>
                                                <td>{{$invoice->invoice_no}}</td>
                                                <td>{{$invoice->invoice_amount - $invoice->invoice_vat_amount}}</td>
                                                <td>{{$invoice->invoice_amount}}</td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if(request()->qr == 'vatCollect')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('invoice date')}}</th>
                                        <th scope="col">{{__('invoice code')}}</th>
                                        <th scope="col">{{__('invoice val')}}</th>
                                        <th scope="col">{{__('invoice val with vat')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vat_collect_journal_dts as $k=>$vat_collect_journal_dt)
                                        @php $invoice_2=\App\Models\InvoiceHd::where('invoice_id',$k)->first(); @endphp
                                        @if(isset($invoice_2))
                                            <tr>
                                                <td>#</td>
                                                <td>{{ $invoice_2->invoice_date }}</td>
                                                <td>{{$invoice_2->invoice_no}}</td>
                                                <td>{{$invoice_2->invoice_amount - $invoice_2->invoice_vat_amount}}</td>
                                                <td>{{$invoice_2->invoice_amount}}</td>

                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection