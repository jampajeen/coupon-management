
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

            $('div').live('pageshow', function (event, ui) {
                showPOI();

                $('#fb_name').html('<img src="https://graph.facebook.com/10203219548183518/picture?type=small"/>' + " " + localStorage.name);
            });

            var poi_id;
            var coupon_id;
            var client_id;
            function showPOI(position) {
                var element = document.getElementById('poi_div');
                lat = '<?php echo $poi['loc']['lat'] ?>';
                lon = '<?php echo $poi['loc']['lon'] ?>';
                //            lat = GetURLParameter('lat');// position.coords.latitude;
                //            lon = GetURLParameter('lon');// position.coords.longitude;
                place = '<?php echo $poi['name']; ?>';
<?php
foreach ($coupons as $coupon) {
    echo "coupon_id = '" . $coupon['_id'] . "'";
    break; // sample show 1 record
}
?>

                poi_id = '<?php echo $poi['_id']; ?>';

                // $("#hidden_lat").val(lat);
                // $("#hidden_lon").val(lon);
                if (coupon_id == 'undefined') {
                    $('#a_coupon').hide();
                    $('#div_coupon_img').hide();
                } else {
                    //$('#div_coupon_img').html('<img src="./img/coupons/'+coupon_id+'.png"'+' width="350px"></img>');
                    $('#div_coupon_img').html('<iframe width="440" height="235" scrolling="no" frameBorder="0" src="coupontemplate/' + coupon_id + '.html"> </iframe>');
                    $('#div_place_addr').html('Addr: <?php echo str_replace("'", "\\'", $poi['addr']); ?>');
                    $('#div_place_desc').html('Info: <?php echo str_replace("'", "\\'", $poi['desc']); ?>');
                }

                var img_marker = "http://rak.rinxor.com/img/icons/pin2.png";
                var img_url = "http://maps.googleapis.com/maps/api/staticmap?center="
                        + lat + "," + lon + "&zoom=14&size=400x300&sensor=false&markers=icon:" + img_marker + "|" + lat + ',' + lon;
                element.innerHTML = "<img src='" + img_url + "'>";
                $('#h_place').html(decodeURIComponent(place));

                getPlaceDesc(poi_id);
            }

            function getPlaceDesc(poi_id) {
                $.ajax({
                    type: "POST",
                    url: "http://rak.rinxor.com/services/mgm_db.php",
                    crossDomain: true,
                    beforeSend: function (request)
                    {
                        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf-8");
                        $.mobile.loading('show');
                    },
                    complete: function () {
                        $.mobile.loading('hide')
                    },
                    data: {poi_id: poi_id, table: 'poi', action: 'get_place_desc'},
                    dataType: 'json',
                    jsonp: 'jsoncallback',
                    // converters: {'text json':JSON.parse}, 
                    success: function (response) {
                        // console.error(JSON.stringify(response));
                        if (response != null) {
                            //                        $('#div_place_addr').html('Addr:'+response.addr);
                            //                        $('#div_place_desc').html('Info:'+response.desc);
                            $('#div_place_addr').html('Addr: <?php echo $poi['addr']; ?>');
                            $('#div_place_desc').html('Info: <?php echo $poi['desc']; ?>');
                            client_id = response._id.$id;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("error");
                        // alert('Error!'+textStatus);                  
                    }
                });

            }

            function savePlace() {
                $.ajax({
                    type: "POST",
                    url: "http://rak.rinxor.com/services/mgm_db.php",
                    crossDomain: true,
                    beforeSend: function (request)
                    {
                        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf-8");
                        $.mobile.loading('show');
                    },
                    complete: function () {
                        $.mobile.loading('hide')
                    },
                    data: {poi_id: poi_id, table: 'place_saved', action: 'save_place', user_id: localStorage.id, client_id: client_id},
                    dataType: 'json',
                    jsonp: 'jsoncallback',
                    // converters: {'text json':JSON.parse}, 
                    success: function (response) {
                        console.error(JSON.stringify(response));
                        if (response != null) {
                            $('#div_place_addr').html('Addr:' + response.addr);
                            $('#div_place_desc').html('Info:' + response.desc);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("error");
                        // alert('Error!'+textStatus);                  
                    }
                });

            }

            function saveCoupon() {
                $.ajax({
                    type: "POST",
                    url: "http://rak.rinxor.com/services/mgm_db.php",
                    crossDomain: true,
                    beforeSend: function (request)
                    {
                        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf-8");
                        $.mobile.loading('show');
                    },
                    complete: function () {
                        $.mobile.loading('hide')
                    },
                    data: {poi_id: poi_id, table: 'coupon_saved', action: 'save_coupon', user_id: localStorage.id, coupon_id: coupon_id},
                    dataType: 'json',
                    jsonp: 'jsoncallback',
                    // converters: {'text json':JSON.parse}, 
                    success: function (response) {
                        console.error(JSON.stringify(response));
                        if (response != null) {
                            $('#div_place_addr').html('Addr:' + response.addr);
                            $('#div_place_desc').html('Info:' + response.desc);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("error");
                        // alert('Error!'+textStatus);                  
                    }
                });

            }

            function saveRatingPlace() {
                // alert($('#frm_rate_place').serialize()+',rate='+$('#rating_place').rating().val()); return;
                var str = $('#frm_rate_place').serialize();
                var rating_point = str.split('=');

                $.ajax({
                    type: "POST",
                    url: "http://rak.rinxor.com/services/mgm_db.php",
                    crossDomain: true,
                    beforeSend: function (request)
                    {
                        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=utf-8");
                        $.mobile.loading('show');
                    },
                    complete: function () {
                        $.mobile.loading('hide')
                    },
                    data: {poi_id: poi_id, table: 'rating_place', action: 'save_rating_place', user_id: localStorage.id, poi_id:poi_id, rating_point: rating_point[1]},
                    dataType: 'json',
                    jsonp: 'jsoncallback',
                    // converters: {'text json':JSON.parse}, 
                    success: function (response) {
                        console.error(JSON.stringify(response));
                        if (response != null) {
                            $('#div_place_addr').html('Addr:' + response.addr);
                            $('#div_place_desc').html('Info:' + response.desc);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("error");
                        // alert('Error!'+textStatus);                  
                    }
                });

            }

            function GetURLParameter(sParam) {
                var sPageURL = window.location.search.substring(1);
                var sURLVariables = sPageURL.split('&');
                for (var i = 0; i < sURLVariables.length; i++) {
                    var sParameterName = sURLVariables[i].split('=');
                    if (sParameterName[0] == sParam) {
                        return sParameterName[1];
                    }
                }
            }


            $('#a_close').live('click', function () {
                window.close();
            });

            $('#a_save_place').live('click', function () {
                savePlace();
            });

            $('#a_save_coupon').live('click', function () {
                saveCoupon();
            });

            $('#a_rate_place').live('click', function () {
                saveRatingPlace();
            });

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
