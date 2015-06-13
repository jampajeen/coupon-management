<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.core.css" />
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.default.css" />

        <link rel="stylesheet" href="vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="vendor/uikit/css/addons/uikit.gradient.addons.min.css">
        <link rel="stylesheet" href="css/style.css">

        <script src="vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="vendor/uikit/js/uikit.min.js"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
        <script src="vendor/alertify/lib/alertify.min.js"></script> 
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    </head>
    <body>
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

            <nav class="uk-navbar uk-margin-large-bottom">
                <ul class="uk-navbar-nav uk-hidden-small">
                    <ul class="uk-navbar-nav uk-hidden-small">
                        <li <?php if ($active_route == "/admin/dashboard") {
    echo 'class="uk-active"';
} ?>>
                            <a href="/admin/dashboard">Dashboard</a>
                        </li>
                        <li <?php if ($active_route == "/admin/clients") {
    echo 'class="uk-active"';
} ?>>
                            <a href="/admin/clients">Clients</a>
                        </li>
                        <li <?php if ($active_route == "/admin/users") {
    echo 'class="uk-active"';
} ?>>
                            <a href="/admin/users">Users</a>
                        </li>
                        <li <?php if ($active_route == "/admin/poi") {
    echo 'class="uk-active"';
} ?>>
                            <a href="/admin/poi">POI</a>
                        </li>
                        <li <?php if ($active_route == "/admin/categories") {
    echo 'class="uk-active"';
} ?>>
                            <a href="/admin/categories">Categories</a>
                        </li>

                    </ul>
                </ul>
                <div class="uk-navbar-flip">

                    <ul class="uk-navbar-nav">
                        <li class="uk-parent" data-uk-dropdown>
                            <a href="/clients/logout">Logout</a>



                        </li>
                    </ul>

                </div>
            </nav>
            @yield('content')

        </div>

        <script type="text/javascript">
            var map = null;
            var marker = null;

            var infowindow = new google.maps.InfoWindow({
                size: new google.maps.Size(150, 50)
            });


            function initialize() {

                var myLatlng = new google.maps.LatLng(<?php echo (!empty($poi) ? $poi['loc']['lat'] : "13.68652997269749"); ?>, <?php echo (!empty($poi) ? $poi['loc']['lon'] : "100.5328094959259"); ?>);

                var myOptions = {
                    zoom: 19,
                    center: myLatlng,
                    mapTypeControl: true,
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                    navigationControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

                marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    //icon: image,
                    title: "Property Location",
                    draggable: true
                });

                formlat = document.getElementById("latbox").value = myLatlng.lat();
                formlng = document.getElementById("lngbox").value = myLatlng.lng();

                google.maps.event.addListener(marker, 'dragend', function (event) {
                    document.getElementById("latbox").value = this.getPosition().lat();
                    document.getElementById("lngbox").value = this.getPosition().lng();
                });

                google.maps.event.addListener(marker, 'click', function (event) {
                    document.getElementById("latbox").value = event.latLng.lat();
                    document.getElementById("lngbox").value = event.latLng.lng();
                });

                google.maps.event.addListener(map, 'click', function () {
                    infowindow.close();
                });

                google.maps.event.addListener(map, 'click', function (event) {
                    if (marker) {
                        marker.setMap(null);
                        marker = null;
                    }

                    var myLatLng = event.latLng;
                    marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                        //icon: image,
                        title: "Property Location",
                        draggable: true
                    });

                    formlat = document.getElementById("latbox").value = event.latLng.lat();
                    formlng = document.getElementById("lngbox").value = event.latLng.lng();

                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        document.getElementById("latbox").value = event.latLng.lat();
                        document.getElementById("lngbox").value = event.latLng.lng();
                    });

                });

                var input = (document.getElementById('pac-input'));
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                var searchBox = new google.maps.places.SearchBox((input));

                google.maps.event.addListener(searchBox, 'places_changed', function (event) {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0, place; place = places[i]; i++) {

                        marker.setMap(null);
                        marker = null;

                        marker = new google.maps.Marker({
                            map: map,
                            title: "Property Location",
                            draggable: true,
                            position: place.geometry.location
                        });

                        bounds.extend(place.geometry.location);

                        var lat = marker.getPosition().lat();
                        var lng = marker.getPosition().lng();
                        formlat = document.getElementById("latbox").value = lat;
                        formlng = document.getElementById("lngbox").value = lng;
                    }

                    map.fitBounds(bounds);
                });

                google.maps.event.addListener(map, 'bounds_changed', function () {
                    var bounds = map.getBounds();
                    searchBox.setBounds(bounds);
                });

            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script> 
    </body>
</html>
