<!-- SALES WIDGET -->
<?php 
    $amount_91 = $fuel_sales['by_amount']['91'];
    $amount_95 = $fuel_sales['by_amount']['95'];
    $amount_diesel = $fuel_sales['by_amount']['diesel'];
    $total_amount = ( $amount_91 + $amount_95 + $amount_diesel);


    $volum_91 = $fuel_sales['by_volum']['91'];
    $volum_95 = $fuel_sales['by_volum']['95'];
    $volum_diesel = $fuel_sales['by_volum']['diesel'];
    $total_volum = $volum_91 + $volum_95 + $volum_diesel;  
?>
<div class="row clearfix">  
    <div class="col-lg-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">  المبيعات بالريال</h3>  
                <span class="tag tag-success"> 
                    <h4 class="font-30 font-weight-bold text-col-blue"> {{number_format($total_amount,2)}}   </h4>
                </span> 
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="d-block">بنزين 91 <span class="float-right"> {{number_format($amount_91,2)}}  SAR</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="77" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($total_amount > 0 ? $amount_91/$total_amount *100 : 0) }}%;background-color: #65A30D;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">بنزين 95 <span class="float-right">{{number_format($amount_95,2)}} SAR</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($total_amount > 0 ? $amount_95/$total_amount *100 : 0) }}%;background-color: #EF4444;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">الديزل <span class="float-right">{{number_format($amount_diesel,2)}} SAR</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($total_amount > 0 ? $amount_diesel/$total_amount *100 : 0) }}%;background-color: #eab308;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">الاجمالي <span class="float-right">{{number_format($total_amount,2)}}  SAR</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100" style="width: 100%;background-color: green;"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-lg-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">  المبيعات باللتر </h3> 
                <span class="tag tag-success"> 
                    <h6 class="font-30 font-weight-bold text-col-blue"> {{number_format($total_volum,2)}}   </h6>
                </span> 
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="d-block">بنزين 91 <span class="float-right"> {{number_format($volum_91,2)}}  لتر</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="77" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($total_volum > 0 ? $volum_91/$total_volum *100 : 0) }}%;background-color: #65A30D;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">بنزين 95 <span class="float-right"> {{number_format($volum_95,2)}} لتر</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($total_volum > 0 ? $volum_95/$total_volum *100 : 0) }}%;background-color: #EF4444;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">الديزل <span class="float-right"> {{number_format($volum_diesel,2)}} لتر</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="23%" aria-valuemin="0" aria-valuemax="100" style="width:{{ ($total_volum > 0 ? $volum_diesel/$total_volum *100 : 0) }}%;background-color: #eab308;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="d-block">الاجمالي <span class="float-right">{{number_format($total_volum,2)}}  لتر</span></label>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100" style="width: 100%;background-color: green;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SALES WIDGET -->

<!-- SALES BY PAYMENT WIDGET -->
<div class="row clearfix">  
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div id="chartdiv"></div>
        </div>
    </div>
</div>
<!-- SALES BY PAYMENT WIDGET -->

<!-- SALES BY PAYMENT WIDGET -->
@if($fuel_sales_by_emp['count'] > 0)
<div class="row clearfix">  
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div id="sales_by_emp_chart"></div>
        </div>
    </div>
</div>
@endif
<!-- SALES BY PAYMENT WIDGET -->


<!-- SALES BY NOZZLE WIDGET -->
@if($fuel_sales_by_nozzle['count'] > 0)
<div class="row clearfix">  
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div id="sales_by_nozzle_chart"></div>
        </div>
    </div>
</div>
@endif
<!-- SALES BY NOZZLE WIDGET -->

<!-- SALES Chart  -->
<script type="text/javascript">
    $(function () 
    {
        sales_by_payment = {!! json_encode($fuel_sales['by_payment']) !!}
        var chartDom = document.getElementById('chartdiv');
        var myChart = echarts.init(chartDom);
        var option;

        option = {
            title: {
                text: 'Paymet Method'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                type: 'shadow'
                }
            },
            legend: {},
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: ['Banzin 91', 'Banzin 95', 'Diseil']
            },

            series: [
                {
                name: 'Cash',
                type: 'bar',
                color: '#5470c6',
                data: [
                    sales_by_payment['91']['cash'], sales_by_payment['95']['cash'], sales_by_payment['diesel']['cash']
                ]
                },
                {
                name: 'Mada',
                type: 'bar',
                color: '#91cc75',
                data: [
                    sales_by_payment['91']['mada'], sales_by_payment['95']['mada'], sales_by_payment['diesel']['mada']
                ]
                }
            ]
        };

        option && myChart.setOption(option);

    });
</script> 
<script type="text/javascript">
    $(function () 
    {
        fuel_sales_by_emp = {!! json_encode($fuel_sales_by_emp) !!};
        emp = fuel_sales_by_emp['emp'];
        total_amount_by_emp = fuel_sales_by_emp['total_amount'];
        total_volume_by_emp = fuel_sales_by_emp['total_volume'];

        if(fuel_sales_by_emp['count'] > 0)
        {
            var chartDom = document.getElementById('sales_by_emp_chart');
            var myChart = echarts.init(chartDom);
            var option;

            option = {
                title: {
                    text: 'Total Amount and Volume',
                    subtext: 'By Employee'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Amount', 'Volume']
                },
                toolbox: {
                    show: true,
                    feature: 
                    {
                        dataView: { show: true, readOnly: false },
                        magicType: { show: true, type: ['bar'] },
                        restore: { show: true },
                        saveAsImage: { show: true }
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: emp
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: 'Amount',
                        type: 'bar',
                        data: total_amount_by_emp,
                        markPoint: 
                        {
                            data: [
                                { type: 'max', name: 'Max' },
                                { type: 'min', name: 'Min' }
                            ]
                        },
                        markLine: 
                        {
                            data: [{ type: 'average', name: 'Avg' }]
                        }
                    },
                    {
                        name: 'Volume',
                        type: 'bar',
                        data: total_volume_by_emp,
                        markPoint: {
                            data: [
                                { type: 'max', name: 'Max' },
                                { type: 'min', name: 'Min' }
                            ]
                        },
                        markLine: {
                            data: [{ type: 'average', name: 'Avg' }]
                        }
                    }
                ]
            };
            option && myChart.setOption(option);
        }

    });
</script> 

<script type="text/javascript">
    $(function () 
    {
        fuel_sales_by_nozzle = {!! json_encode($fuel_sales_by_nozzle) !!};
        nozzle = fuel_sales_by_nozzle['emp'];
        total_amount_by_nozzle = fuel_sales_by_nozzle['total_amount'];
        total_volume_by_nozzle = fuel_sales_by_nozzle['total_volume'];

        if(fuel_sales_by_nozzle['count'] > 0)
        {
            var chartDom = document.getElementById('sales_by_nozzle_chart');
            var myChart = echarts.init(chartDom);
            var option;

            option = {
                title: {
                    text: 'Total Amount and Volume',
                    subtext: 'By Nozzle'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Amount', 'Volume']
                },
                toolbox: {
                    show: true,
                    feature: 
                    {
                        dataView: { show: true, readOnly: false },
                        magicType: { show: true, type: ['bar'] },
                        restore: { show: true },
                        saveAsImage: { show: true }
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: nozzle
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: 'Amount',
                        type: 'bar',
                        data: total_amount_by_nozzle,
                        markPoint: 
                        {
                            data: [
                                { type: 'max', name: 'Max' },
                                { type: 'min', name: 'Min' }
                            ]
                        },
                        markLine: 
                        {
                            data: [{ type: 'average', name: 'Avg' }]
                        }
                    },
                    {
                        name: 'Volume',
                        type: 'bar',
                        data: total_volume_by_nozzle,
                        markPoint: {
                            data: [
                                { type: 'max', name: 'Max' },
                                { type: 'min', name: 'Min' }
                            ]
                        },
                        markLine: {
                            data: [{ type: 'average', name: 'Avg' }]
                        }
                    }
                ]
            };
            option && myChart.setOption(option);
        }

    });
</script> 