@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}

@endsection

@section('content')

<div class="container-fluid">



                <div class="section-body mt-3" id="app">
                    <div class="container-fluid">

                        @include('Includes.form-errors')

                        <div class="row mb-12">

                            <div class="col-md-3">


                                @if(auth()->user()->user_type_id != 1)
                                    @foreach(session('job')->permissions as $job_permission)
                                        @if($job_permission->app_menu_id == 51 && $job_permission->permission_add)
                                            <a href="{{route('PriceList.create')}}" class="btn btn-primary">
                                                <i class="fe fe-plus mr-2"></i>@lang('customer.add_price_list')
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <a href="{{route('PriceList.create')}}" class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>@lang('customer.add_price_list')
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-5">
                            </div>   
                            <div class="col-md-4">
                                            
                                            <a
                                                    href="{{config('app.telerik_server')}}?rpt={{$price_list_all}}&id={{$company_report->company_id}}&lang=ar&skinName=bootstrap"
                                                    title="{{trans('PRINT')}}" class="btn btn-primary m-1" id="showReport" target="_blank">
                                                @lang('waybill.price_list_report')
                                            </a>
                                           
                            </div>

                            <div hidden class="col-md-4">
                                <form action="">
                                    @if(auth()->user()->user_type_id  == 1)
                                        <select class="form-control" onchange="this.form.submit()"
                                                name="company_group_id">
                                            @foreach($main_companies as $main_company)
                                                <option value="{{$main_company->company_group_id}}"
                                                        @if(request()->company_group_id == $main_company->company_group_id) selected
                                                        @elseif(request()->company_id && App\Models\Company::where('company_id',request()->company_id)->first()->company_group_id ==  $main_company->company_group_id) selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$main_company->company_group_ar}}
                                                    @else
                                                        {{$main_company->company_group_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                        {{ auth()->user()->companyGroup->company_group_ar }} @else
                                        {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                    @endif
                                </form>
                         </div>

                            <div hidden class="col-md-4">
                                <form action="">
                                    <select class="form-control" name="company_id"
                                            onchange="this.form.submit()">
                                        @if(auth()->user()->user_type_id == 1)
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
                                        @else
                                            @foreach(auth()->user()->companyGroup->companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if(request()->company_id == $company->company_id)
                                                        selected
                                                        @elseif(auth()->user()->company->company_id == $company->company_id)
                                                        selected
                                                        @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$company->company_name_ar}}
                                                    @else
                                                        {{$company->company_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach
                                        @endif

                                    </select>
                                </form>
                            </div>
                        </div>
                 </div>



                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover  yajra-datatable">
                            <thead>
                            <tr class="red" style="background-color: #ece5e7" >
                              
                                <th>@lang('customer.price_list_no')</th>
                                <th>@lang('customer.customer_name')</th>
                                <th>@lang('customer.from_date')</th>
                                <th>@lang('customer.from_date')</th>
                                <th>@lang('customer.notes')</th>
                                <th>@lang('customer.customer_status')</th>


                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr >
                                    <td class="width45">
                                        <label class="custom-control custom-checkbox mb-0">
                                        <input type="checkbox" class="custom-control-input" name="example-checkbox1" value="option1" checked="">
                                        <span class="custom-control-label">&nbsp;</span>
                                        </label>
                                     </td>

                                    <td>
                                    <img src="" class="rounded" alt="">

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {

            var table = $('.yajra-datatable').DataTable({
                language: {
                    search: "بحث",
                    processing: "جاري البحث....",
                    info: " ",
                    entries: " ",
                    infoEmpty: "no data ",
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
                ajax: "{{ route('PriceList') }}",
                columns: [

                    {data: 'price_list_code', name: 'price_list_code'},
   
                    {data: 'customer', name: 'customer'},
                    {data: 'price_list_start_date', name: 'price_list_start_date'},

                    {data: 'price_list_end_date', name: 'price_list_end_date'},
                   
                    {data: 'price_list_notes', name: 'price_list_notes'},
                    {data: 'status', name: 'status'},



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

@endsection
