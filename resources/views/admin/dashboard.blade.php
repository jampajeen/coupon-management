@extends('admin.layout')

@section('content')

<script>
    $(document).ready(function(){
    $('#container').highcharts({
        title: {
            text: 'Monthly Coupon View/Use',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Count '
            },
            plotLines: [{ 
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '°C'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Coupon View',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'Coupon Use',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    }); 
    
    // Make monochrome colors and set them as default for all pies
    Highcharts.getOptions().plotOptions.pie.colors = (function () {
        var colors = [],
            base = Highcharts.getOptions().colors[0],
            i;

        for (i = 0; i < 10; i += 1) {
            // Start out with a darkened base color (negative brighten), and end
            // up with a much brighter color
            colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
        }
        return colors;
    }());

    // Build the chart
    $('#container2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Social Media Share - September, 2014'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Social share',
            data: [
                ['Facebook',   45.0],
                ['Instragram',       31.5],
                {
                    name: 'Twitter',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['Email',   0.7]
            ]
        }]
    });
    
    });
</script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<br>
<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-1-2">
        <div class="uk-grid">
            <div class="uk-width-1-6">
                <i class=" uk-icon-large uk-text-primary"></i>
            </div>
            <div class="uk-width-5-6">
<!--                <h2 class="uk-h3">Information</h2>
                <p>1. Fender</p>
                <p>2. Gibson</p>
                <p>3. PRS</p>
                <p>4. Ibanez</p>
                <p>5. Taylor</p>-->
            </div>
        </div>
    </div>

    <div class="uk-width-medium-1-2">
        <div class="uk-grid">
            <div class="uk-width-1-6">
                <!--<i class="uk-icon-thumbs-o-up uk-icon-large uk-text-primary"></i>-->
            </div>
            <div class="uk-width-6-6">
                <div id="container2" style="min-width: 200px; height: 300px; max-width: 600px;"></div>
            </div>
        </div>
    </div>

</div>

<!--<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-1-2">
        <div class="uk-grid">
            <div class="uk-width-1-6">
                <i class="uk-icon-dashboard uk-icon-large uk-text-primary"></i>
            </div>
            <div class="uk-width-5-6">
                <h2 class="uk-h3">Header</h2>
                <p>content.</p>
            </div>
        </div>
    </div>

    <div class="uk-width-medium-1-2">
        <div class="uk-grid">
            <div class="uk-width-1-6">
                <i class="uk-icon-comments uk-icon-large uk-text-primary"></i>
            </div>
            <div class="uk-width-5-6">
                <h2 class="uk-h3">Header</h2>
                <p>content.</p>
            </div>
        </div>
    </div>

</div>-->

<hr class="uk-grid-divider">


    @stop