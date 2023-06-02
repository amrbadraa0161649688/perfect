@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
        
        * {
            margin: 0;
            padding: 0;
            }
            #chart-91 {
            position: relative;
            height:300px;
            width:300px;
            overflow: hidden;
            }
            .liquidFillGaugeText { font-family: Helvetica; font-weight: bold; }
            .ribbon .ribbon-box.gray{
                background: gray;
            }
            .h4, h4{
                font-size: 1rem;
            }
            .datepicker-dropdown {
  top: 0;
  left: 0;
  padding: 4px;
}
    </style>
    <style>
#chartdiv {
  width: 100%;
  height: 500px;
}

#sales_by_emp_chart {
  width: 100%;
  height: 500px;
}
#sales_by_nozzle_chart
{
    width: 100%;
    height: 500px;
}
</style>
@endsection

@section('content')

<div class="section-body mt-3" id="app">
    <div class="container-fluid">
        <!-- Filter  -->
        <div class="row">
        {{--<div class="col-md-3">
                <form action="">
                    @if(session('company_group'))
                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                        {{ session('company_group')['company_group_ar'] }} @else
                        {{ session('company_group')['company_group_en'] }} @endif" >
                    @else
                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                        {{ auth()->user()->companyGroup->company_group_ar }} @else
                        {{ auth()->user()->companyGroup->company_group_en }} @endif" >
                    @endif
                </form>
            </div>--}}
            <div class="col-md-3">
                <form action="">
                    <select class="form-control" name="company_id" id="company_id">
                        <option value="">@lang('home.choose')</option>
                        @foreach($companies as $company)
                            <option value="{{$company->company_id}}"
                                    @if($user_data['company']->company_id == $company->company_id) selected @endif>
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

            <div class="col-md-3">
                <form action="">
                    <select class="form-control" name="branch_id" id="branch_id">
                        @foreach($branch_list as $branch)
                            <option value="{{ $branch->branch_id }}" @if($user_data['branch']->branch_id == $branch->branch_id) selected @endif>
                                    {{ $branch->getBranchName() }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- <div class="col-md-2">
                <input type="date" class="form-control" name="start_date" id="start_date">
            </div>

            <div class="col-md-2">
                <input type="date" class="form-control" name="end_date" id="end_date">
            </div> -->

            <div class="col-md-4">
                <form action="">
                    <div class="form-group">
                        <div class=" input-group" >
                            <input  type="date" class="form-control"  data-date-format="mm/dd/yyyy" name="start_date" id="start_date" value='02/02/2023'>
                            <span class="input-group-addon range-to">to</span>
                            <input  type="date" class="form-control" data-date-format="mm/dd/yyyy" name="end_date" id="end_date">
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-2">
                <button onclick="getData()" class="btn btn-primary">
                    <i class="fe fe-search mr-2"></i> Search
                </button> 
            </div>
        </div>
        <!-- End Filter -->
        <br>

        <div id="showData"></div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5.4.1/dist/echarts.min.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    

    <script type="text/javascript">
        $(function () {
            lang = '{{app()->getLocale()}}';
            var today_date = new Date();
             //$('#start_date').datepicker();
            //  $('#start_date').datepicker('date', today_date);
            // $('#end_date').datepicker('setDate', 'today');
            //$('#start_date').val(today_date);
            var today_date = new Date().toISOString().split('T')[0]
            const start_date = document.getElementById('start_date');
            start_date.value = new Date().toISOString().split('T')[0];
            const end_date = document.getElementById('end_date');
            end_date.value = new Date().toISOString().split('T')[0];

            getData();

        }); 
        
        function getData(){
            company_id = ($('#company_id').val() ? $('#company_id').val() : {{ auth()->user()->company_id }});
            search = { 
                company_id: $('#company_id').val(), 
                branch_id: $('#branch_id').val(),
                start_date: $('#start_date').val(), 
                end_date: $('#end_date').val()
            };
            //console.log($('#branch_id').val())
            $.ajax({
                type: 'get',
                url :"{{route('fuel-station.data')}}",
                data:{
                    _token : "{{ csrf_token() }}",
                    company_id : company_id,
                    search : search,
                },
                // beforeSend: function () {
                   
                // }
            }).done(function(data){
                if(data.success==false){
                    toastr.warning(data.msg);
                }
                $('#showData').html(data.view);
            });
        }

        $('#company_id').on('change', function () {
            //console.log('changed');
            $("#branch_id option").remove();
            var company_id = $('#company_id').val();
            $.ajax({
                url: '{{ route( 'fuel-station.get.branch' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "company_id": company_id
                },
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    $('#branch_id').append($('<option>', {value: '', text: 'choose'}));
                    $.each(result.data, function (k) {
                        if (lang == 'ar') {
                            $('#branch_id').append($('<option>', {
                                value: result.data[k].branch_id,
                                text: result.data[k].branch_name_ar
                            }));
                        }
                        else {
                            $('#branch_id').append($('<option>', {
                                value: result.data[k].branch_id,
                                text: result.data[k].branch_name_en
                            }));
                        }
                    });
                    $('#branch_id').selectpicker('refresh');

                },
                error: function () {
                    //handle errors
                    alert('error...');
                }
            });
        });

    </script>

@endsection

