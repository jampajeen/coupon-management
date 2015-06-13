
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <!-- WARNING: for iOS 7, remove the width=device-width and height=device-height attributes. See https://issues.apache.org/jira/browse/CB-4323 -->
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <!-- <meta name="viewport" content="width=device-width,initial-scale=1"> -->
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.core.css" />
        <!-- include a theme, can be included into the core instead of 2 separate files -->
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.default.css" />
        
        <link rel="stylesheet" href="vendor/rome/dist/rome.css">
        <link rel="stylesheet" href="vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/coupon.css">
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <link rel="stylesheet" type="text/css" href="css/har.css" />

        <title>Har Services</title>

        <link rel="stylesheet" href="themes/Bootstrap.css">
        <link rel="stylesheet" href="css/jquery.mobile.structure-1.4.0.min.css" />
        <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="js/jquery.mobile-1.4.0.min.js"></script>
        <script src="js/har.js"></script>
<script src='js/jquery.rating.js' type="text/javascript" language="javascript"></script>
<link href='css/jquery.rating.css' type="text/css" rel="stylesheet"/>

    <script type="text/javascript" charset="utf-8">

        $('div').live('pageshow',function(event, ui){
            showPOI();
            //$('#fb_name').html('<img src="https://graph.facebook.com/10203219548183518/picture?type=small"/>'+ " " + localStorage.name);
        });

        var poi_id;
        var coupon_id; 
        var client_id;
        function showPOI(position) {
            var element = document.getElementById('poi_div');
            lat = '<?php echo $layout['loc']['lat'] ?>';
            lon = '<?php echo $layout['loc']['lon'] ?>';
            place = '<?php echo $layout['name']; ?>';
            coupon_id = '';
            
            if(coupon_id=='undefined'){ 
                $('#a_coupon').hide();
                $('#div_coupon_img').hide();
            }else{
                coupon = '<?php echo preg_replace( "/\r|\n/", "", $layout['coupon_layouts'][0]); ?>';
                //coupon = coupon.replace(/(\r\n|\n|\r)/gm," ");
                $('#div_coupon_img').html(coupon);
                $('#div_place_addr').html('Addr: <?php echo str_replace("'","\\'", $layout['addr']); ?>');
                $('#div_place_desc').html('Info: <?php echo str_replace("'","\\'", $layout['desc']); ?>');
            }

            var img_marker = "http://rak.rinxor.com/img/icons/pin2.png";
            var img_url = "http://maps.googleapis.com/maps/api/staticmap?center="
                +lat+","+lon+"&zoom=14&size=400x300&sensor=false&markers=icon:"+img_marker+"|"+lat + ',' + lon;
            element.innerHTML = "<img src='"+img_url+"'>";
            $('#h_place').html(decodeURIComponent(place));

        }
 
        </script>

    </head>
    <body>
<!-- jQuery Bootstrap -->
      <div data-role="page" data-theme="a" id="checkin_page" hidden>
            <div data-role="header" class="header"  data-tap-toggle="false" data-position="fixed" >
                <h1 id="h_place">GeoLocation</h1>
                <div class="ui-btn-right" data-theme="f">
                    <a href="#" data-role="button" data-icon="back" data-iconpos="notext" data-theme="f" id="a_close">Close</a>
                </div>
            </div>
            <div id='poi_div' data-role='header' data-theme="a" data-divider-theme="a" data-content-theme="a"></div>
            <div>
                <input class="star" type="radio" name="place_rated" value="1" disabled="disabled" />
                <input class="star" type="radio" name="place_rated" value="2" disabled="disabled"/>
                <input class="star" type="radio" name="place_rated" value="3" disabled="disabled"/>
                <input class="star" type="radio" name="place_rated" value="4" checked="checked" disabled="disabled"/>
                <input class="star" type="radio" name="place_rated" value="5" disabled="disabled"/>
                <h3>&nbsp;4.0 (1 rates)</h3>
            </div>
            <div>
                
            </div>
            <ul data-role="listview" data-inset="false" data-divider-theme="a">
                <!-- <li data-role="list-divider" id='h_place'></li> -->
                <li style="padding-left: 0px;" id='div_coupon_img'></li>
                <!-- <li id='div_rating'></li> -->
                <li id='div_place_addr'></li>
                <li id="div_place_desc" style="white-space:normal;"></li>
            </ul>

            <div data-role="footer" data-theme="f" data-position="fixed">
                <!-- <a href="#popupDialogPlace" id="a_coupon" data-role="button" data-icon="save" data-iconpos="left" hidden>Coupon</a> -->
                <a href="#popupDialogCoupon" data-rel="popup" data-position-to="window" data-role="button" data-icon="save" data-iconpos="left" data-transition="pop" id="a_coupon">Coupon</a>
                <!-- <a href="dialog.html" data-role="button" data-inline="true" data-rel="dialog" data-transition="pop">data-transition="pop"</a> -->
                <!-- <a href="#popupDialog" data-rel="popup" data-position-to="window" data-role="button" data-inline="true" data-transition="pop">Dialog</a> -->
                <a href="#popupDialogPlace" data-rel="popup" data-position-to="window" data-role="button" data-icon="save" data-iconpos="left" data-transition="pop">Place</a>
                <a href="#popupDialogRate" data-rel="popup" data-position-to="window" data-role="button" data-icon="star" data-iconpos="left" data-transition="pop">Rate</a>
                <!-- <a href="#" data-role="button" data-icon="back" data-iconpos="left" data-theme="f" id="a_close">Close</a> -->
            </div>
            <div data-role="popup" id="popupDialogCoupon" data-overlay-theme="a" data-theme="a" style="max-width:400px;" class="ui-corner-all">
                <div data-role="header" data-theme="a" class="ui-corner-top">
                    <h1>Save Coupon?</h1>
                </div>
                <div role="main" class="ui-corner-bottom ui-content">
                    <h3 class="ui-title">Are you sure you want to save this coupon?</h3>
                    <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="a">Cancel</a>
                    <a href="#" data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="f" id="a_save_coupon">Save</a>
                </div>
            </div>

            <div data-role="popup" id="popupDialogPlace" data-overlay-theme="a" data-theme="a" style="max-width:400px;" class="ui-corner-all">
                <div data-role="header" data-theme="a" class="ui-corner-top">
                    <h1>Save Place?</h1>
                </div>
                <div role="main" class="ui-corner-bottom ui-content">
                    <h3 class="ui-title">Are you sure you want to save this place?</h3>
                    <!-- <p>This action cannot be undone.</p> -->
                    <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="a">Cancel</a>
                    <a href="#" data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="f" id='a_save_place'>Save</a>
                </div>
            </div>

            <div data-role="popup" id="popupDialogRate" data-overlay-theme="a" data-theme="a" style="max-width:400px;" class="ui-corner-all">
                <div data-role="header" data-theme="a" class="ui-corner-top">
                    <h2>Rate this place</h2>
                </div>
                <div role="main" class="ui-corner-bottom ui-content" width="200px">
<form name="api-select" id='frm_rate_place'>
                    <div class="ui-title">
                        <input class="star" type="radio" name="rate_the_place" value="1"/>
                        <input class="star" type="radio" name="rate_the_place" value="2"/>
                        <input class="star" type="radio" name="rate_the_place" value="3"/>
                        <input class="star" type="radio" name="rate_the_place" value="4"/>
                        <input class="star" type="radio" name="rate_the_place" value="5"/>
                    </div></br>
                        <a href="#" data-role="button" data-inline="true" data-rel="back" data-theme="a">Cancel</a>
                        <a href="#" data-role="button" data-inline="true" data-rel="back" data-transition="flow" data-theme="f" id='a_rate_place'>Save</a>
</form>
                </div>
            </div>

      </div>

    </body>
</html>
