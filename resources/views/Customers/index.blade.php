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
                                <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">

                                    <a href="{{route('Customers.create')}}"  class="btn btn-primary" >
                                        <i class="fe fe-plus mr-2"></i>@lang('home.add_customer')
                                    </a>


                                </button>
                            </div>

                            <div class="col-md-4">
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

                            <div class="col-md-4">
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
                                <th class="sorting" style = "width": 10px
                               ></th>

                                <th>@lang('customer.customer_no')</th>
                                <th>@lang('customer.customer_name')</th>
                                <th>@lang('customer.vat_no')</th>
                                <th>@lang('customer.customer_id')</th>
                                <th>@lang('customer.customer_mobile')</th>
                                <th>@lang('customer.customer_email')</th>


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
                ajax: "{{ route('customers') }}",
                columns: [
                    {data: 'photo', name: 'photo'},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'customer_name_full_ar', name: 'customer_name_full_ar'},
                    {data: 'customer_vat_no', name: 'customer_vat_no'},
                    {data: 'customer_identity', name: 'customer_identity'},
                    {data: 'customer_mobile', name: 'customer_mobile'},
                    {data: 'customer_email', name: 'customer_email'},

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
