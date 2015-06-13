@extends('dashboard.layout')

@section('content')

<!--<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {

  var data = google.visualization.arrayToDataTable([
    ['Week', 'Total Coupon', 'Customer Use'],
    ['3 Last week',  50,      40],
    ['2 Last week',  50,      46],
    ['Last week',  100,       50],
    ['This week',  50,      50]
  ]);

  var options = {
    title: 'Coupon Use',
    hAxis: {title: 'Week', titleTextStyle: {color: 'red'}}
  };

  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

  chart.draw(data, options);

}
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Social', 'Shared'],
          ['Facebook',     21],
          ['Instragram',      12],
          ['Twitter',  12],
          ['Email', 5]
        ]);

        var options = {
          title: 'Social media share'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
//      alertify.log("<a class=\"uk-close uk-float-right\"></a><div>You must create at least one shop before adding a coupon  <a href=\"/shop/addshop\"><br><button type=button class=\"uk-button uk-button-small\">Create Now</button></a></div>", "success", 0);
    </script>
    <script src="vendor/uikit/js/addons/sticky.js"></script>
    
    <div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-1-3">
        <div class="uk-grid">
            <div class="uk-width-1-6">
                <i class="uk-icon-cog uk-icon-large uk-text-primary"></i>
            </div>
            <div class="uk-width-5-6">
                <h2 class="uk-h3">Overview</h2>
                <p>Shop (<a href="/shop"><?php echo $additional['poi_count']; ?></a>)</p>
                <p>Coupon (<a href="/coupon"><?php echo $additional['coupon_count']; ?></a>)</p>
                <?php foreach($coupons as $coupon) { ?>
                <a href="#view_modal-<?php echo $coupon['_id']; ?>" data-uk-modal></a>
                                <div id="view_modal-<?php echo $coupon['_id']; ?>" class="uk-modal"> 

                                    <div class="uk-modal-dialog uk-text-center">
                                        <a class="uk-modal-close uk-close"></a>
                                        <iframe width="440" height="235" scrolling="no" src="/coupontemplate/<?php echo $coupon['_id']  ?>.html"> </iframe>
                                    </div>

                                </div>
                                
                <p>&nbsp; - <a data-uk-modal="{target:'#view_modal-<?php echo $coupon['_id']; ?>'}"><?php echo $coupon['coupon_name']; ?></a> (0/<?php echo $coupon['coupon_amt']; ?>)</p>
                
                <?php } ?>
                
                <!--<div id="piechart" style="width: 900px; height: 500px;"></div>-->
            </div>
        </div>
    </div>

    <div class="uk-width-medium-2-3">
        <div class="uk-grid">
    <!--            <div class="uk-width-1-6">
                    <i class="uk-icon-cog uk-icon-large uk-text-primary"></i>
                </div>-->
            <div class="uk-width-5-6">
                <!--<div id="container2" style="min-width: 200px; height: 300px; max-width: 600px;"></div>-->
                <!--<div id="chart_div" style="width: 700px; height: 500px;"></div>-->
                <?php if($additional['poi_count'] == 0) { ?>
                <div id="floating" class=" uk-container-center">
                    <div class="uk-text-center" style="z-index: 999; background-color: #d9edf7; padding: 15px;" data-uk-sticky="{top:100}"> 
                        <a class="uk-close uk-float-right" onclick="javascript:{ $('#floating').hide(); }"></a>
                        <div style="color: #000"> <strong>You must create at least one shop before adding a coupon  <a href="/shop/addshop"> - Create Now</a> </strong></div>
                    </div>
                </div>
                <?php } ?>
                <?php if($additional['poi_count'] != 0 && $additional['coupon_count'] == 0) { ?>
                <div id="floating" class=" uk-container-center">
                    <div class="uk-text-center" style="z-index: 999; background-color: #d9edf7; padding: 15px;" data-uk-sticky="{top:100}"> 
                        <a class="uk-close uk-float-right" onclick="javascript:{ $('#floating').hide(); }"></a>
                        <div style="color: #000"> <strong>The shop has already created, you can create a coupon now  <a href="/coupon/addcoupon"> - Create Coupon</a> </strong></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
    <hr>
    <div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-1-2">
        <div class="uk-grid">
            <div class="uk-width-6-6">
                <?php if($additional['poi_count'] != 0 && $additional['coupon_count']) { ?>
                <div id="piechart" style="width: 500px; height: 500px;"></div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="uk-width-medium-2-4">
        <div class="uk-grid">
            <div class="uk-width-6-6">
                <?php if($additional['poi_count'] != 0 && $additional['coupon_count']) { ?>
                <!--<div id="container2" style="min-width: 200px; height: 300px; max-width: 600px;"></div>-->
                <div id="chart_div" style="width: 500px; height: 400px;"></div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<br>


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

<!--<hr class="uk-grid-divider">-->


    @stop