

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

<div class="section-body mt-3">



<div class="container-fluid">



    <div class="row">

    <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('employees') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-address-card"></i>
                                <span>@lang('home.human_resources')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="journal-entries" class="my_sort_cut text-muted">
                                <i class="fa fa-share-alt"></i>
                                <span>@lang('home.public_accounts')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('customers') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-users"></i>
                                <span>@lang('home.customers')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a  href="{{ route('invoices-acc') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-files-o"></i>
                                <span>@lang('home.invoices')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div  class="card-body ribbon">
                            <a href="{{ route('users') }}" class="my_sort_cut text-muted">
                                <i class="icon-users"></i>
                                <span>@lang('home.users')</span>
                            </a>
                        </div>
                    </div>
                </div>

        <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('home') }}"  class="my_sort_cut text-muted">
                                <i class="fa fa-share-square-o"></i>
                                <span>@lang('home.home')</span>
                            </a>
                        </div>
                    </div>
                </div>


    </div>
</div>
</div>

<div class="container-fluid">
                    <div class="row mb-12">

                    <div hidden class="col-md-4">
                        <label>@lang('home.company_group')</label>
                        @if(auth()->user()->user_type_id  == 1)
                            <input type="text" class="form-control"
                                value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :
                                        session('company_group')['company_group_en'] }}" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </div>

                    <div hidden class="col-md-4">
                        <label>@lang('home.companies')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                name="company_id[]" data-actions-box="true" required>

                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                        @if(request()->company_id)
                                        @foreach(request()->company_id  as $company_id)
                                        @if($company_id == $company->company_id) selected @endif
                                        @endforeach @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>@lang('home.branch')</label>
                        <select class="selectpicker" multiple data-live-search="true"
                                name="branch_id[]" data-actions-box="true">

                            @foreach($branches as $branch)
                                <option value="{{$branch->branch_id}}"
                                        @if(request()->branch_id)
                                        @foreach(request()->branch_id  as $branch_id)
                                        @if($branch_id == $branch->branch_id) selected @endif
                                        @endforeach @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$branch->branch_name_ar}}
                                    @else
                                        {{$branch->branch_name_en}}
                                    @endif
                                </option>

                            @endforeach

                        </select>
                    </div>

                    </div>
                    <div class="row mt-3">

                    </div>

                    <div class="row ">

                                    <div class="col-lg-2 col-md-6" style="width: 150px;height :100px">
                                        <div class="card" STYLE = "height :100px ;border: 2px solid #ccc; background-color: #0d8f67 ;">
                                            <div class="card-body w_sparkline">
                                            
                                            <div class="details" >
                                                    <span style=" font-weight: bold;color: white ">شاحنات جاهزة</span>
                                                    <h3 class="mb-0 counter" style=" font-weight: bold">{{$ready_truck}}</h3>
                                                </div>
                                            
                                                <div class="w_chart">
                                                <a href="{{route('Trucks')}}" class="my_sort_cut text-muted">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                
                                                </a>
                                                </div>  
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                                        <div class="card" STYLE = "height :100px ;border: 2px solid #ccc; background-color: #79589c ;">
                                            <div class="card-body w_sparkline">
                                                <div class="details">
                                                    <span style=" font-weight: bold;color: white ">شاحنات محمله</span>
                                                    <h3 class="mb-0 counter" style=" font-weight: bold">{{$loaded_truck}}</h3>
                                                </div>
                                            
                                                <div class="w_chart">
                                                <a href="{{route('Trips')}}" class="my_sort_cut text-muted">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                
                                                </a>
                                                </div>  
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                                        <div class="card" STYLE = "height :100px ;border: 2px solid #ccc; background-color: #edb052 ;">
                                            <div class="card-body w_sparkline">
                                                <div class="details">
                                                    <span style=" font-weight: bold;color: white "> في الطريق</span>
                                                    <h3 class="mb-0 counter" style=" font-weight: bold">{{$road_truck}}</h3>
                                                </div>
                                            
                                                <div class="w_chart">
                                                <a href="{{route('Trips')}}" class="my_sort_cut text-muted">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                
                                                </a>
                                                </div>  
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                                        <div class="card" STYLE = "height :100px ;border: 2px solid #ccc; background-color: #92aacc ;">
                                            <div class="card-body w_sparkline">
                                                <div class="details">
                                                    <span style=" font-weight: bold;color: white ">بوالص شحن  </span>
                                                    <h3 class="mb-0 counter" style=" font-weight: bold">{{$waybills}}</h3>
                                                </div>
                                            
                                                <div class="w_chart">
                                                <a href="{{route('WaybillCar')}}" class="my_sort_cut text-muted">
                                                    <i class="fa fa-files-o"></i>
                                                
                                                </a>
                                                </div>  
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                                        <div class="card" STYLE = "height :100px ;border: 2px solid #ccc; background-color: #d03104 ;">
                                            <div class="card-body w_sparkline">
                                                <div class="details">
                                                    <span style=" font-weight: bold;color: white "> متاخره   </span>
                                                    <h3 class="mb-0 counter" style=" font-weight: bold">{{$waybills_late_c}}</h3>
                                                </div>
                                            
                                                <div class="w_chart">
                                                <a href="{{route('WaybillCar')}}" class="my_sort_cut text-muted">
                                                    <i class="fa fa-hourglass-half"></i>
                                                
                                                </a>
                                                </div>  
                                                
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-2 col-md-6" style="width: 50px;height :100px">
                                        <div class="card" STYLE = "height :100px ;border: 2px solid #ccc; background-color: #bda2b8 ;">
                                            <div class="card-body w_sparkline">
                                                <div class="details">
                                                    <span style=" font-weight: bold;color: white ">للتسليم   </span>
                                                    <h3 class="mb-0 counter" style=" font-weight: bold">{{$arrived_waybills}}</h3>
                                                </div>
                                            
                                                <div class="w_chart">
                                                <a href="{{route('WaybillCar')}}" class="my_sort_cut text-muted">
                                                    <i class="fa fa-hand-paper-o" data-toggle="tooltip"></i>
                                                
                                                </a>
                                                </div>  
                                                
                                            </div>
                                        </div>
                                    </div>


                                
                    </div>
                    </div>

<div class="row mt-3">

</div>
<div class="section-body">
<div class="container-fluid">

    


    <div class="row clearfix row-deck">

    {{--عدد  --}}
        <div class="col-xl-3 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> حالات بوالص الشحن</h3>
                </div>
                <div class="card-body text-center">
                    <div id="GROWTH_1" style="height: 240px; max-height: 240px; position: relative;"
                         class="c3">
                        <div class="c3-tooltip-container"
                             style="position: absolute; pointer-events: none; display: none;"></div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <div class="row clearfix">
                        
                        <div class="col-3">
                            
                            <small class="text-muted">الاجمالي    </small>
                        </div>
                        <div class="col-3">
                        <h6 class="mb-0">{{$waybills_total}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{--عقود  --}}
        

        <div class="col-xl-6 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">   بوالص الشحن  </h3>
                    <div class="card-options">
                    </div>
                </div>

                <div class="card-body">
                    <div id="chart-bar-emp"
                         style="height: 280; max-height: 280px; position: relative;"
                         class="c3">
                         <svg width="1000.25" height="240" style="overflow: hidden;">
                           
                          
                           </svg>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>

        {{--السيارات  --}}
        <div class="col-xl-3 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> حالات الشاحنات  </h3>
                </div>
                <div class="card-body text-center">
                    <div id="GROWTHtruck" style="height: 240px; max-height: 240px; position: relative;"
                         class="c3">
                        <svg width="200" height="240" style="overflow: hidden;">
                           
                          
                        </svg>
                        <div class="c3-tooltip-container"
                             style="position: absolute; pointer-events: none; display: none;"></div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <div class="row clearfix">
                         <div class="col-6">
                           
                            <small class="text-muted">الاجمالي      </small>
                        </div>
                        <div class="col-2">
                        <h6 class="mb-0">{{$all_trucks}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        

        
    </div>

</div>

</div>

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
var data =
{!! json_encode($data_sales_amount, JSON_HEX_TAG) !!}
var chart = c3.generate({
bindto: '#chart-area-spline-sracked-1', // id of chart wrapper
data: data,
axis: {
x: {
    type: 'category',
    // name of each category
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'August',
        'Sep', 'Auc', 'Nov', 'Dec']
},
y: {
    tick: {
        format: d3.format("")
    }
}
},
bar: {
width: 15
},
legend: {
show: false, //hide legend
},
padding: {
bottom: 0,
top: 0
},
});
///////////////////الموظفين
var data =
{!! json_encode($data_sales, JSON_HEX_TAG) !!}

var chart = c3.generate({
bindto: '#chart-bar-emp', // id of chart wrapper
data: data,
axis: {
x: {
    type: 'category',
    // name of each category
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'August',
        'Sep', 'Auc', 'Nov', 'Dec']
},
y: {
    tick: {
        format: d3.format("")
    }
}
},
bar: {
width: 15
},
legend: {
show: false, //hide legend
},
padding: {
bottom: 0,
top: 0
},
});


// Gender
var data_g =
{!! json_encode($emp_gen, JSON_HEX_TAG) !!}
var chart_g = c3.generate({
bindto: '#GROWTH_1', // id of chart wrapper
data: data_g,
axis: {},
legend: {
show: false, //hide legend
},
padding: {
bottom: 20,
top: 0
},
});

// truck
var data_g =
{!! json_encode($truck_status, JSON_HEX_TAG) !!}
var chart_g = c3.generate({
bindto: '#GROWTHtruck', // id of chart wrapper
data: data_g,
axis: {},
legend: {
show: false, //hide legend
},
padding: {
bottom: 20,
top: 0
},
});


// Employee Nationality
var data_n =
{!! json_encode($emp_nationality, JSON_HEX_TAG) !!}

var chart_n = c3.generate({
bindto: '#chart-bar-stacked_1', // id of chart wrapper
data: data_n,
axis: {
x: {
    type: 'category',
    // name of each category
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep',
        'Oct', 'Nov', 'Dec']
},
},
bar: {
width: 15
},
legend: {
show: false, //hide legend
},
padding: {
bottom: -20,
top: 0,
left: -6,
},
});


</script>

@endsection