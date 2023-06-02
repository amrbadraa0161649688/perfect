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
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">

                            <a href="{{route('Waybill.create_cargo')}}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('waybill.add_new_waybill')
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
                    <table class="table table-hover table-striped table-vcenter  yajra-datatable">
                        <thead>
                        <tr class="red" style="background-color: #2b#2b3035 !important3035">


                            <th>@lang('waybill.waybill_no')</th>
                            <th>@lang('waybill.company_name')</th>
                            <th>@lang('waybill.customer_name')</th>
                            <th>@lang('waybill.waybill_type')</th>
                            <th>@lang('waybill.waybill_date')</th>
                            <th>@lang('waybill.waybill_expect')</th>
                            <th>@lang('waybill.waybill_amount')</th>
                            <th>@lang('waybill.waybill_status')</th>

                            <th></th>
                        </tr>
                        </thead>

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
                        ajax: "{{ route('WaybillCargo') }}",
                        columns: []
                    });

                });
            </script>

@endsection
