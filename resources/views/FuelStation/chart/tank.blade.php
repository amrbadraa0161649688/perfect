@if(count($tanked_data_arr) != 0)
<div class="row clearfix">
@foreach($tanked_data_arr as $td)
    @if($td['number'] != 4)
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">مستوي الخزان {{$td['name']}} خزان رقم {{$td['number']}} </h3>
                </div>
                <div class="card-body text-center">
                    <div>
                        <div>
                            <svg id="{{$td['chart_name']}}"></svg>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-lg-4">
                            <h4 class="mb-1"><i class="mdi mdi-trending-down text-danger"></i> {{$td['minLimit']}} </h4>
                            <div class="text-muted-dark" style="background-color: #EF4444; color: #f4f4f4 !important">الحد الادني</div>
                        </div>
                        <div class="col-lg-4">
                            <h4 class="mb-1"><i class="mdi mdi-trending-up text-success"></i> {{$td['maxLimit']}} </h4>
                            <div class="text-muted-dark" style="background-color: #eab308; color: #f4f4f4 !important">الحد الاعلى</div>
                        </div>
                        <div class="col-lg-4">
                            <h4 class="mb-1"><i class="mdi mdi-trending-neutral text-warning"></i> {{$td['currentVolume']}} </h4>
                            <div class="text-muted-dark" style="background-color: #65A30D; color: #f4f4f4 !important"> الحالي</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif 
    @endforeach       
    </div>
  
@endif

<script type="text/javascript">
    $(function () {
      
        tank_data = {!! json_encode($tanked_data_arr) !!};

        //percentage_91 = (tank_data[0]['chart_data'] != null ? (tank_data[0]['chart_data']['currentVolume']/tank_data[0]['chart_data']['capacity'] *100) : 'N/A');
        //percentage_95 = (tank_data[1]['chart_data'] != null ? (tank_data[1]['chart_data']['currentVolume']/tank_data[1]['chart_data']['capacity'] *100) : 'N/A');
        //percentage_diesel = (tank_data[2]['chart_data'] != null ? (tank_data[2]['chart_data']['currentVolume']/tank_data[2]['chart_data']['capacity'] *100):  'N/A');

        //chart_91_gauge.update(percentage_91);
        //chart_95_gauge.update(percentage_95);
        //chart_diesel_gauge.update(percentage_diesel);
        // function myFunction(item, index) {
        //  console.log( index + ": " + stationId + "<br>"); 
        // }

        // tank_data.forEach()
        // {
        //     console.log(tank_data);
        // };

        for (i = 0; i < tank_data.length; i++) {
            if(tank_data[i]['number'] != 4)
            {
                if(tank_data[i]['fuel_type'] == 1)
                {
                    chart_91_gauge = loadLiquidFillGauge(tank_data[i]['chart_name'], 10, chart_91);
                    percentage = (tank_data[i]['currentVolume']/tank_data[i]['maxLimit'] *100);
                    chart_91_gauge.update(percentage);
                }
                else if(tank_data[i]['fuel_type'] == 2)
                {
                    chart_95_gauge = loadLiquidFillGauge(tank_data[i]['chart_name'], 10, chart_95);
                    percentage = (tank_data[i]['currentVolume']/tank_data[i]['maxLimit'] *100);
                    chart_95_gauge.update(percentage);
                }else if(tank_data[i]['fuel_type'] == 3)
                {
                    chart_diesel_gauge = loadLiquidFillGauge(tank_data[i]['chart_name'], 10, chart_diesel);
                    percentage = (tank_data[i]['currentVolume']/tank_data[i]['maxLimit'] *100);
                    chart_diesel_gauge.update(percentage);
                }
            }
        }
        
    });

    function liquidFillGaugeDefaultSettings(){
        return {
            minValue: 0, // The gauge minimum value.
            maxValue: 100, // The gauge maximum value.
            circleThickness: 0.05, // The outer circle thickness as a percentage of it's radius.
            circleFillGap: 0.05, // The size of the gap between the outer circle and wave circle as a percentage of the outer circles radius.
            circleColor: "#178BCA", // The color of the outer circle.
            waveHeight: 0.05, // The wave height as a percentage of the radius of the wave circle.
            waveCount: 1, // The number of full waves per width of the wave circle.
            waveRiseTime: 1000, // The amount of time in milliseconds for the wave to rise from 0 to it's final height.
            waveAnimateTime: 18000, // The amount of time in milliseconds for a full wave to enter the wave circle.
            waveRise: true, // Control if the wave should rise from 0 to it's full height, or start at it's full height.
            waveHeightScaling: true, // Controls wave size scaling at low and high fill percentages. When true, wave height reaches it's maximum at 50% fill, and minimum at 0% and 100% fill. This helps to prevent the wave from making the wave circle from appear totally full or empty when near it's minimum or maximum fill.
            waveAnimate: true, // Controls if the wave scrolls or is static.
            waveColor: "#178BCA", // The color of the fill wave.
            waveOffset: 0, // The amount to initially offset the wave. 0 = no offset. 1 = offset of one full wave.
            textVertPosition: .5, // The height at which to display the percentage text withing the wave circle. 0 = bottom, 1 = top.
            textSize: 1, // The relative height of the text to display in the wave circle. 1 = 50%
            valueCountUp: true, // If true, the displayed value counts up from 0 to it's final value upon loading. If false, the final value is displayed.
            displayPercent: true, // If true, a % symbol is displayed after the value.
            textColor: "#045681", // The color of the value text when the wave does not overlap it.
            waveTextColor: "#A4DBf8" // The color of the value text when the wave overlaps it.
        };
    }

    function loadLiquidFillGauge(elementId, value, config) {
        if(config == null) config = liquidFillGaugeDefaultSettings();

        var gauge = d3.select("#" + elementId);
        var radius = Math.min(parseInt(gauge.style("width")), parseInt(gauge.style("height")))/2;
        var locationX = parseInt(gauge.style("width"))/2 - radius;
        var locationY = parseInt(gauge.style("height"))/2 - radius;
        var fillPercent = Math.max(config.minValue, Math.min(config.maxValue, value))/config.maxValue;

        var waveHeightScale;
        if(config.waveHeightScaling){
            waveHeightScale = d3.scale.linear()
                .range([0,config.waveHeight,0])
                .domain([0,50,100]);
        } else {
            waveHeightScale = d3.scale.linear()
                .range([config.waveHeight,config.waveHeight])
                .domain([0,100]);
        }

        var textPixels = (config.textSize*radius/2);
        var textFinalValue = parseFloat(value).toFixed(2);
        var textStartValue = config.valueCountUp?config.minValue:textFinalValue;
        var percentText = config.displayPercent?"%":"";
        var circleThickness = config.circleThickness * radius;
        var circleFillGap = config.circleFillGap * radius;
        var fillCircleMargin = circleThickness + circleFillGap;
        var fillCircleRadius = radius - fillCircleMargin;
        var waveHeight = fillCircleRadius*waveHeightScale(fillPercent*100);

        var waveLength = fillCircleRadius*2/config.waveCount;
        var waveClipCount = 1+config.waveCount;
        var waveClipWidth = waveLength*waveClipCount;

        // Rounding functions so that the correct number of decimal places is always displayed as the value counts up.
        var textRounder = function(value){ return Math.round(value); };
        if(parseFloat(textFinalValue) != parseFloat(textRounder(textFinalValue))){
            textRounder = function(value){ return parseFloat(value).toFixed(1); };
        }
        if(parseFloat(textFinalValue) != parseFloat(textRounder(textFinalValue))){
            textRounder = function(value){ return parseFloat(value).toFixed(2); };
        }

        // Data for building the clip wave area.
        var data = [];
        for(var i = 0; i <= 40*waveClipCount; i++){
            data.push({x: i/(40*waveClipCount), y: (i/(40))});
        }

        // Scales for drawing the outer circle.
        var gaugeCircleX = d3.scale.linear().range([0,2*Math.PI]).domain([0,1]);
        var gaugeCircleY = d3.scale.linear().range([0,radius]).domain([0,radius]);

        // Scales for controlling the size of the clipping path.
        var waveScaleX = d3.scale.linear().range([0,waveClipWidth]).domain([0,1]);
        var waveScaleY = d3.scale.linear().range([0,waveHeight]).domain([0,1]);

        // Scales for controlling the position of the clipping path.
        var waveRiseScale = d3.scale.linear()
            // The clipping area size is the height of the fill circle + the wave height, so we position the clip wave
            // such that the it will overlap the fill circle at all when at 0%, and will totally cover the fill
            // circle at 100%.
            .range([(fillCircleMargin+fillCircleRadius*2+waveHeight),(fillCircleMargin-waveHeight)])
            .domain([0,1]);
        var waveAnimateScale = d3.scale.linear()
            .range([0, waveClipWidth-fillCircleRadius*2]) // Push the clip area one full wave then snap back.
            .domain([0,1]);

        // Scale for controlling the position of the text within the gauge.
        var textRiseScaleY = d3.scale.linear()
            .range([fillCircleMargin+fillCircleRadius*2,(fillCircleMargin+textPixels*0.7)])
            .domain([0,1]);

        // Center the gauge within the parent SVG.
        var gaugeGroup = gauge.append("g")
            .attr('transform','translate('+locationX+','+locationY+')');

        // Draw the outer circle.
        var gaugeCircleArc = d3.svg.arc()
            .startAngle(gaugeCircleX(0))
            .endAngle(gaugeCircleX(1))
            .outerRadius(gaugeCircleY(radius))
            .innerRadius(gaugeCircleY(radius-circleThickness));
        gaugeGroup.append("path")
            .attr("d", gaugeCircleArc)
            .style("fill", config.circleColor)
            .attr('transform','translate('+radius+','+radius+')');

        // Text where the wave does not overlap.
        var text1 = gaugeGroup.append("text")
            .text(textRounder(textStartValue) + percentText)
            .attr("class", "liquidFillGaugeText")
            .attr("text-anchor", "middle")
            .attr("font-size", textPixels + "px")
            .style("fill", config.textColor)
            .attr('transform','translate('+radius+','+textRiseScaleY(config.textVertPosition)+')');

        // The clipping wave area.
        var clipArea = d3.svg.area()
            .x(function(d) { return waveScaleX(d.x); } )
            .y0(function(d) { return waveScaleY(Math.sin(Math.PI*2*config.waveOffset*-1 + Math.PI*2*(1-config.waveCount) + d.y*2*Math.PI));} )
            .y1(function(d) { return (fillCircleRadius*2 + waveHeight); } );
        var waveGroup = gaugeGroup.append("defs")
            .append("clipPath")
            .attr("id", "clipWave" + elementId);
        var wave = waveGroup.append("path")
            .datum(data)
            .attr("d", clipArea)
            .attr("T", 0);

        // The inner circle with the clipping wave attached.
        var fillCircleGroup = gaugeGroup.append("g")
            .attr("clip-path", "url(#clipWave" + elementId + ")");
        fillCircleGroup.append("circle")
            .attr("cx", radius)
            .attr("cy", radius)
            .attr("r", fillCircleRadius)
            .style("fill", config.waveColor);

        // Text where the wave does overlap.
        var text2 = fillCircleGroup.append("text")
            .text(textRounder(textStartValue) + percentText)
            .attr("class", "liquidFillGaugeText")
            .attr("text-anchor", "middle")
            .attr("font-size", textPixels + "px")
            .style("fill", config.waveTextColor)
            .attr('transform','translate('+radius+','+textRiseScaleY(config.textVertPosition)+')');

        // Make the value count up.
        if(config.valueCountUp){
            var textTween = function(){
                var i = d3.interpolate(this.textContent, textFinalValue);
                return function(t) { this.textContent = textRounder(i(t)) + percentText; }
            };
            text1.transition()
                .duration(config.waveRiseTime)
                .tween("text", textTween);
            text2.transition()
                .duration(config.waveRiseTime)
                .tween("text", textTween);
        }

        // Make the wave rise. wave and waveGroup are separate so that horizontal and vertical movement can be controlled independently.
        var waveGroupXPosition = fillCircleMargin+fillCircleRadius*2-waveClipWidth;
        if(config.waveRise){
            waveGroup.attr('transform','translate('+waveGroupXPosition+','+waveRiseScale(0)+')')
                .transition()
                .duration(config.waveRiseTime)
                .attr('transform','translate('+waveGroupXPosition+','+waveRiseScale(fillPercent)+')')
                .each("start", function(){ wave.attr('transform','translate(1,0)'); }); // This transform is necessary to get the clip wave positioned correctly when waveRise=true and waveAnimate=false. The wave will not position correctly without this, but it's not clear why this is actually necessary.
        } else {
            waveGroup.attr('transform','translate('+waveGroupXPosition+','+waveRiseScale(fillPercent)+')');
        }

        if(config.waveAnimate) animateWave();

        function animateWave() {
            wave.attr('transform','translate('+waveAnimateScale(wave.attr('T'))+',0)');
            wave.transition()
                .duration(config.waveAnimateTime * (1-wave.attr('T')))
                .ease('linear')
                .attr('transform','translate('+waveAnimateScale(1)+',0)')
                .attr('T', 1)
                .each('end', function(){
                    wave.attr('T', 0);
                    animateWave(config.waveAnimateTime);
                });
        }

        function GaugeUpdater(){
            this.update = function(value){
                var newFinalValue = parseFloat(value).toFixed(2);
                var textRounderUpdater = function(value){ return Math.round(value); };
                if(parseFloat(newFinalValue) != parseFloat(textRounderUpdater(newFinalValue))){
                    textRounderUpdater = function(value){ return parseFloat(value).toFixed(1); };
                }
                if(parseFloat(newFinalValue) != parseFloat(textRounderUpdater(newFinalValue))){
                    textRounderUpdater = function(value){ return parseFloat(value).toFixed(2); };
                }

                var textTween = function(){
                    var i = d3.interpolate(this.textContent, parseFloat(value).toFixed(2));
                    return function(t) { this.textContent = textRounderUpdater(i(t)) + percentText; }
                };

                text1.transition()
                    .duration(config.waveRiseTime)
                    .tween("text", textTween);
                text2.transition()
                    .duration(config.waveRiseTime)
                    .tween("text", textTween);

                var fillPercent = Math.max(config.minValue, Math.min(config.maxValue, value))/config.maxValue;
                var waveHeight = fillCircleRadius*waveHeightScale(fillPercent*100);
                var waveRiseScale = d3.scale.linear()
                    // The clipping area size is the height of the fill circle + the wave height, so we position the clip wave
                    // such that the it will overlap the fill circle at all when at 0%, and will totally cover the fill
                    // circle at 100%.
                    .range([(fillCircleMargin+fillCircleRadius*2+waveHeight),(fillCircleMargin-waveHeight)])
                    .domain([0,1]);
                var newHeight = waveRiseScale(fillPercent);
                var waveScaleX = d3.scale.linear().range([0,waveClipWidth]).domain([0,1]);
                var waveScaleY = d3.scale.linear().range([0,waveHeight]).domain([0,1]);
                var newClipArea;
                if(config.waveHeightScaling){
                    newClipArea = d3.svg.area()
                        .x(function(d) { return waveScaleX(d.x); } )
                        .y0(function(d) { return waveScaleY(Math.sin(Math.PI*2*config.waveOffset*-1 + Math.PI*2*(1-config.waveCount) + d.y*2*Math.PI));} )
                        .y1(function(d) { return (fillCircleRadius*2 + waveHeight); } );
                } else {
                    newClipArea = clipArea;
                }

                var newWavePosition = config.waveAnimate?waveAnimateScale(1):0;
                wave.transition()
                    .duration(0)
                    .transition()
                    .duration(config.waveAnimate?(config.waveAnimateTime * (1-wave.attr('T'))):(config.waveRiseTime))
                    .ease('linear')
                    .attr('d', newClipArea)
                    .attr('transform','translate('+newWavePosition+',0)')
                    .attr('T','1')
                    .each("end", function(){
                        if(config.waveAnimate){
                            wave.attr('transform','translate('+waveAnimateScale(0)+',0)');
                            animateWave(config.waveAnimateTime);
                        }
                    });
                waveGroup.transition()
                    .duration(config.waveRiseTime)
                    .attr('transform','translate('+waveGroupXPosition+','+newHeight+')')
            }
        }

        return new GaugeUpdater();
    }

    var chart_91 = liquidFillGaugeDefaultSettings();
    chart_91.circleThickness = 0.15;
    chart_91.circleColor = "#65A30D ";
    chart_91.textColor = "#65A30D ";
    chart_91.waveTextColor = "#65A30D ";
    chart_91.waveColor = "#a1f56c";
    chart_91.textVertPosition = 0.8;
    chart_91.waveAnimateTime = 1000;
    chart_91.waveHeight = 0.05;
    chart_91.waveAnimate = true;
    chart_91.waveRise = false;
    chart_91.waveHeightScaling = false;
    chart_91.waveOffset = 0.25;
    chart_91.textSize = 0.75;
    //var chart_91_gauge = loadLiquidFillGauge("fill-chart_91_gauge", 10, chart_91);


    var chart_95 = liquidFillGaugeDefaultSettings();
    chart_95.circleThickness = 0.15;
    chart_95.circleColor = "#EF4444";
    chart_95.textColor = "#EF4444";
    chart_95.waveTextColor = "#EF4444";
    chart_95.waveColor = "#faa2a2";
    chart_95.textVertPosition = 0.8;
    chart_95.waveAnimateTime = 1000;
    chart_95.waveHeight = 0.05;
    chart_95.waveAnimate = true;
    chart_95.waveRise = false;
    chart_95.waveHeightScaling = false;
    chart_95.waveOffset = 0.25;
    chart_95.textSize = 0.75;
    //var chart_95_gauge = loadLiquidFillGauge("fill-chart_95_gauge", 20, chart_95);


    var chart_diesel = liquidFillGaugeDefaultSettings();
    chart_diesel.circleThickness = 0.15;
    chart_diesel.circleColor = "#eab308";
    chart_diesel.textColor = "#eab308";
    chart_diesel.waveTextColor = "#eab308";
    chart_diesel.waveColor = "#f0ed97";
    chart_diesel.textVertPosition = 0.8;
    chart_diesel.waveAnimateTime = 1000;
    chart_diesel.waveHeight = 0.05;
    chart_diesel.waveAnimate = true;
    chart_diesel.waveRise = false;
    chart_diesel.waveHeightScaling = false;
    chart_diesel.waveOffset = 0.25;
    chart_diesel.textSize = 0.75;
    //var chart_diesel_gauge = loadLiquidFillGauge("fill-chart_diesel_gauge", 30, chart_diesel);

    function NewValue(){
        if(Math.random() > .5){
            return Math.round(Math.random()*100);
        } else {
            return (Math.random()*100).toFixed(1);
        }
    }

</script>