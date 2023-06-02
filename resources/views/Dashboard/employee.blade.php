@extends('Layouts.master')
@section('content')

    <x-dashboard.links-component>

    </x-dashboard.links-component>

    <div class="section-body mt-3">
    <div class="container-fluid">
                        <div class="row clearfix row-deck">

                            {{--عدد الموظفين حسب الادارات--}}
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">  {{ __('home.employee_count') }}</h3>
                                        <div class="card-options">
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div id="chart-bar"
                                             style="height: 350px; max-height: 349.969px; position: relative;"
                                             class="c3">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                    </div>
                                </div>
                            </div>

                            
                        </div>


                        <div class="row clearfix row-deck">
                            {{--عدد الاناث والذكور--}}
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"> {{__('home.male_female_count')}}</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="GROWTH" style="height: 240px; max-height: 240px; position: relative;"
                                             class="c3">
                                            <div class="c3-tooltip-container"
                                                 style="position: absolute; pointer-events: none; display: none;"></div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-center">
                                        <div class="row clearfix">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$data_gender->columns[0][1]. '%'}}</h6>
                                                <small class="text-muted">{{__('home.male')}}</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$data_gender->columns[1][1] . '%'}}</h6>
                                                <small class="text-muted">{{__('home.female')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--سعودي وغير سعودي--}}
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">@lang('home.nationality')</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="chart-bar-stacked"
                                             style="height: 280px; max-height: 279.938px; position: relative;"
                                             class="c3">

                                            <div class="c3-tooltip-container"
                                                 style="position: absolute; pointer-events: none; display: none;"></div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$employees_saudi}}</h6>
                                                <small class="text-muted">{{__('home.saudis')}}</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">{{$employees_non_saudi}}</h6>
                                                <small class="text-muted">{{__('home.none_saudis')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Employee Satisfaction</h3>
                                    </div>
                                    <div class="card-body text-center">
                                             <div id="chart-area-spline-sracked"
                                             style="height: 280px; max-height: 279.938px; position: relative;"
                                             class="c3">
                                            
                                            </div>
                                       
                                        <div class="row clearfix">
                                            <div class="col-6">
                                                <h6 class="mb-0">195</h6>
                                                <small class="text-muted">مبيعات النقليات</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">163</h6>
                                                <small class="text-muted">مبيعات البضائع</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row clearfix row-deck">
                            
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Growth</h3>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="GROWTH" style="height: 240px; max-height: 240px; position: relative;"
                                             class="c3">
                                            <svg width="581.25" height="240" style="overflow: hidden;">
                                                <defs>
                                                    <clipPath id="c3-1634152339118-clip">
                                                        <rect width="581.25" height="216"></rect>
                                                    </clipPath>
                                                    <clipPath id="c3-1634152339118-clip-xaxis">
                                                        <rect x="-31" y="-20" width="643.25" height="40"></rect>
                                                    </clipPath>
                                                    <clipPath id="c3-1634152339118-clip-yaxis">
                                                        <rect x="-29" y="-4" width="20" height="240"></rect>
                                                    </clipPath>
                                                    <clipPath id="c3-1634152339118-clip-grid">
                                                        <rect width="581.25" height="216"></rect>
                                                    </clipPath>
                                                    <clipPath id="c3-1634152339118-clip-subchart">
                                                        <rect width="581.25"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g transform="translate(0.5,4.5)">
                                                    <text class="c3-text c3-empty" text-anchor="middle"
                                                          dominant-baseline="middle" x="290.625" y="108"
                                                          style="opacity: 0;"></text>
                                                    <rect class="c3-zoom-rect" width="581.25" height="216"
                                                          style="opacity: 0;"></rect>
                                                    <g clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip)"
                                                       class="c3-regions" style="visibility: hidden;"></g>
                                                    <g clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip-grid)"
                                                       class="c3-grid" style="visibility: hidden;">
                                                        <g class="c3-xgrid-focus">
                                                            <line class="c3-xgrid-focus" x1="-10" x2="-10" y1="0"
                                                                  y2="216" style="visibility: hidden;"></line>
                                                        </g>
                                                    </g>
                                                    <g clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip)"
                                                       class="c3-chart">
                                                        <g class="c3-event-rects c3-event-rects-single"
                                                           style="fill-opacity: 0;">
                                                            <rect class=" c3-event-rect c3-event-rect-0" x="0.375" y="0"
                                                                  width="581.25" height="216"></rect>
                                                        </g>
                                                        <g class="c3-chart-bars">
                                                            <g class="c3-chart-bar c3-target c3-target-data1"
                                                               style="pointer-events: none;">
                                                                <g class=" c3-shapes c3-shapes-data1 c3-bars c3-bars-data1"
                                                                   style="cursor: pointer;"></g>
                                                            </g>
                                                            <g class="c3-chart-bar c3-target c3-target-data2"
                                                               style="pointer-events: none;">
                                                                <g class=" c3-shapes c3-shapes-data2 c3-bars c3-bars-data2"
                                                                   style="cursor: pointer;"></g>
                                                            </g>
                                                        </g>
                                                        <g class="c3-chart-lines">
                                                            <g class="c3-chart-line c3-target c3-target-data1"
                                                               style="opacity: 1; pointer-events: none;">
                                                                <g class=" c3-shapes c3-shapes-data1 c3-lines c3-lines-data1"></g>
                                                                <g class=" c3-shapes c3-shapes-data1 c3-areas c3-areas-data1"></g>
                                                                <g class=" c3-selected-circles c3-selected-circles-data1"></g>
                                                                <g class=" c3-shapes c3-shapes-data1 c3-circles c3-circles-data1"
                                                                   style="cursor: pointer;"></g>
                                                            </g>
                                                            <g class="c3-chart-line c3-target c3-target-data2"
                                                               style="opacity: 1; pointer-events: none;">
                                                                <g class=" c3-shapes c3-shapes-data2 c3-lines c3-lines-data2"></g>
                                                                <g class=" c3-shapes c3-shapes-data2 c3-areas c3-areas-data2"></g>
                                                                <g class=" c3-selected-circles c3-selected-circles-data2"></g>
                                                                <g class=" c3-shapes c3-shapes-data2 c3-circles c3-circles-data2"
                                                                   style="cursor: pointer;"></g>
                                                            </g>
                                                        </g>
                                                        <g class="c3-chart-arcs" transform="translate(290.625,103)">
                                                            <text class="c3-chart-arcs-title"
                                                                  style="text-anchor: middle; opacity: 1;"></text>
                                                            <g class="c3-chart-arc c3-target c3-target-data1">
                                                                <g class=" c3-shapes c3-shapes-data1 c3-arcs c3-arcs-data1">
                                                                    <path class=" c3-shape c3-shape c3-arc c3-arc-data1"
                                                                          transform=""
                                                                          style="fill: rgb(90, 82, 120); cursor: pointer;"
                                                                          d="M5.991584464828425e-15,-97.85A97.85,97.85 0 1,1 -71.32958019318512,66.98293431512217L-42.79774811591108,40.189760589073295A58.709999999999994,58.709999999999994 0 1,0 3.5949506788970546e-15,-58.709999999999994Z"></path>
                                                                </g>
                                                                <text dy=".35em" class=""
                                                                      transform="translate(71.84183209854204,31.088736878890636)"
                                                                      style="opacity: 1; text-anchor: middle; pointer-events: none;">
                                                                    63.0%
                                                                </text>
                                                            </g>
                                                            <g class="c3-chart-arc c3-target c3-target-data2">
                                                                <g class=" c3-shapes c3-shapes-data2 c3-arcs c3-arcs-data2">
                                                                    <path class=" c3-shape c3-shape c3-arc c3-arc-data2"
                                                                          transform=""
                                                                          style="fill: rgb(232, 118, 159); cursor: pointer;"
                                                                          d="M-71.32958019318512,66.98293431512217A97.85,97.85 0 0,1 -1.7974753394485276e-14,-97.85L-1.0784852036691164e-14,-58.709999999999994A58.709999999999994,58.709999999999994 0 0,0 -42.79774811591108,40.189760589073295Z"></path>
                                                                </g>
                                                                <text dy=".35em" class=""
                                                                      transform="translate(-71.84183209854204,-31.08873687889064)"
                                                                      style="opacity: 1; text-anchor: middle; pointer-events: none;">
                                                                    37.0%
                                                                </text>
                                                            </g>
                                                        </g>
                                                        <g class="c3-chart-texts">
                                                            <g class="c3-chart-text c3-target c3-target-data1"
                                                               style="opacity: 1; pointer-events: none;">
                                                                <g class=" c3-texts c3-texts-data1"></g>
                                                            </g>
                                                            <g class="c3-chart-text c3-target c3-target-data2"
                                                               style="opacity: 1; pointer-events: none;">
                                                                <g class=" c3-texts c3-texts-data2"></g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip-grid)"
                                                       class="c3-grid c3-grid-lines">
                                                        <g class="c3-xgrid-lines"></g>
                                                        <g class="c3-ygrid-lines"></g>
                                                    </g>
                                                    <g class="c3-axis c3-axis-x"
                                                       clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip-xaxis)"
                                                       transform="translate(0,216)"
                                                       style="visibility: visible; opacity: 0;">
                                                        <text class="c3-axis-x-label" transform=""
                                                              style="text-anchor: end;" x="581.25" dx="-0.5em"
                                                              dy="-0.5em"></text>
                                                        <g class="tick" transform="translate(291, 0)"
                                                           style="opacity: 1;">
                                                            <line x1="0" x2="0" y2="6"></line>
                                                            <text x="0" y="9" transform=""
                                                                  style="text-anchor: middle; display: block;">
                                                                <tspan x="0" dy=".71em" dx="0">0</tspan>
                                                            </text>
                                                        </g>
                                                        <path class="domain" d="M0,6V0H581.25V6"></path>
                                                    </g>
                                                    <g class="c3-axis c3-axis-y"
                                                       clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip-yaxis)"
                                                       transform="translate(0,0)"
                                                       style="visibility: visible; opacity: 0;">
                                                        <text class="c3-axis-y-label" transform="rotate(-90)"
                                                              style="text-anchor: end;" x="0" dx="-0.5em"
                                                              dy="1.2em"></text>
                                                        <g class="tick" transform="translate(0,212)"
                                                           style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">35</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,178)"
                                                           style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">40</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,143)"
                                                           style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">45</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,109)"
                                                           style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">50</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,75)" style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">55</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,40)" style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">60</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,6)" style="opacity: 1;">
                                                            <line x2="-6"></line>
                                                            <text x="-9" y="0" style="text-anchor: end;">
                                                                <tspan x="-9" dy="3">65</tspan>
                                                            </text>
                                                        </g>
                                                        <path class="domain" d="M-6,1H0V216H-6"></path>
                                                    </g>
                                                    <g class="c3-axis c3-axis-y2" transform="translate(581.25,0)"
                                                       style="visibility: hidden; opacity: 0;">
                                                        <text class="c3-axis-y2-label" transform="rotate(-90)"
                                                              style="text-anchor: end;" x="0" dx="-0.5em"
                                                              dy="-0.5em"></text>
                                                        <g class="tick" transform="translate(0,216)"
                                                           style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,195)"
                                                           style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.1</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,173)"
                                                           style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.2</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,152)"
                                                           style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.3</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,130)"
                                                           style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.4</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,109)"
                                                           style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.5</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,87)" style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.6</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,66)" style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.7</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,44)" style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.8</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,23)" style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">0.9</tspan>
                                                            </text>
                                                        </g>
                                                        <g class="tick" transform="translate(0,1)" style="opacity: 1;">
                                                            <line x2="6" y2="0"></line>
                                                            <text x="9" y="0" style="text-anchor: start;">
                                                                <tspan x="9" dy="3">1</tspan>
                                                            </text>
                                                        </g>
                                                        <path class="domain" d="M6,1H0V216H6"></path>
                                                    </g>
                                                </g>
                                                <g transform="translate(0.5,240.5)" style="visibility: hidden;">
                                                    <g clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip-subchart)"
                                                       class="c3-chart">
                                                        <g class="c3-chart-bars"></g>
                                                        <g class="c3-chart-lines"></g>
                                                    </g>
                                                    <g clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip)"
                                                       class="c3-brush"
                                                       style="pointer-events: all; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                                        <rect class="background" x="0" width="341.25"
                                                              style="visibility: hidden; cursor: crosshair;"></rect>
                                                        <rect class="extent" x="0" width="0"
                                                              style="cursor: move;"></rect>
                                                        <g class="resize e" transform="translate(0,0)"
                                                           style="cursor: ew-resize; display: none;">
                                                            <rect x="-3" width="6" height="6"
                                                                  style="visibility: hidden;"></rect>
                                                        </g>
                                                        <g class="resize w" transform="translate(0,0)"
                                                           style="cursor: ew-resize; display: none;">
                                                            <rect x="-3" width="6" height="6"
                                                                  style="visibility: hidden;"></rect>
                                                        </g>
                                                    </g>
                                                    <g class="c3-axis-x" transform="translate(0,0)"
                                                       clip-path="url(https://nsdbytes.com/template/epic/main/#c3-1634152339118-clip-xaxis)"
                                                       style="visibility: hidden; opacity: 0;">
                                                        <g class="tick" transform="translate(291, 0)"
                                                           style="opacity: 1;">
                                                            <line x1="0" x2="0" y2="6"></line>
                                                            <text x="0" y="9" transform=""
                                                                  style="text-anchor: middle; display: block;">
                                                                <tspan x="0" dy=".71em" dx="0">0</tspan>
                                                            </text>
                                                        </g>
                                                        <path class="domain" d="M0,6V0H581.25V6"></path>
                                                    </g>
                                                </g>
                                                <g transform="translate(0,240)" style="visibility: hidden;"></g>
                                                <text class="c3-title" x="290.625" y="0"></text>
                                            </svg>
                                            <div class="c3-tooltip-container"
                                                 style="position: absolute; pointer-events: none; display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="row clearfix">
                                            <div class="col-6">
                                                <h6 class="mb-0">$3,095</h6>
                                                <small class="text-muted">Last Year</small>
                                            </div>
                                            <div class="col-6">
                                                <h6 class="mb-0">$2,763</h6>
                                                <small class="text-muted">This Year</small>
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
    <script>
        // SALARY STATISTICS

        var data =
                {!! json_encode($data, JSON_HEX_TAG) !!}

        var chart_e = c3.generate({
                bindto: '#chart-bar', // id of chart wrapper
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
                    show: true, //hide legend
                },
                padding: {
                    bottom: 0,
                    top: 0
                },
            });


        // Male and Female
        var data_g =
                {!! json_encode($data_gender, JSON_HEX_TAG) !!}
        var chart_g = c3.generate({
                bindto: '#GROWTH', // id of chart wrapper
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
                {!! json_encode($data_nationality, JSON_HEX_TAG) !!}

        var chart = c3.generate({
                bindto: '#chart-bar-stacked', // id of chart wrapper
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