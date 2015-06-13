<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link href="vendor/jquery-ui/jquery-ui.css" type="text/css" rel="stylesheet">
        <link href="vendor/sliptree-bootstrap-tokenfield/dist/css/tokenfield-typeahead.css" type="text/css" rel="stylesheet">

        <link href="vendor/sliptree-bootstrap-tokenfield/dist/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">

        <link rel="stylesheet" href="vendor/alertify/themes/alertify.core.css" />
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.default.css" />

        <link rel="stylesheet" href="vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="vendor/uikit/css/addons/uikit.gradient.addons.min.css">
        <link rel="stylesheet" href="css/style.css">

        <script src="vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="vendor/jquery-ui/jquery-ui.min.js"></script>

        <script src="vendor/uikit/js/uikit.min.js"></script>

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
        <script src="js/script.js"></script>

        <script src="vendor/alertify/lib/alertify.min.js"></script> 
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

        <script type="text/javascript" src="vendor/sliptree-bootstrap-tokenfield/dist/bootstrap-tokenfield.js" charset="UTF-8"></script>
        <script type="text/javascript" src="vendor/sliptree-bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js" charset="UTF-8"></script>

        <script>

            $(function () {


                $(".uk-overlay-toggle").on('click', function () {
                });

                $("#addshop-form").validate({
                    rules: {
                        name: "required",
                        desc: "required",
                        addr: "required",
                        zip: "required",
                        phone: "required",
                        href: "required",
                        area: "required",
                        img: "required"
                    },
                    messages: {
                        name: "<font color=red>Please enter shop name</font>",
                        desc: "<font color=red>Please enter shop description</font>",
                        addr: "<font color=red>Please enter shop address</font>",
                        zip: "<font color=red>Please enter zip code</font>",
                        phone: "<font color=red>Please enter shop phone</font>",
                        href: "<font color=red>Please enter url of shop</font>",
                        area: "<font color=red>Please enter shop area</font>",
                        img: "<font color=red>Please enter image of shop</font>"
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

                $("#editshop-form").validate({
                    rules: {
                        name: "required",
                        desc: "required",
                        addr: "required",
                        zip: "required",
                        phone: "required",
                        href: "required",
                        area: "required",
                        img: "required"
                    },
                    messages: {
                        name: "<font color=red>Please enter shop name</font>",
                        desc: "<font color=red>Please enter shop description</font>",
                        addr: "<font color=red>Please enter shop address</font>",
                        zip: "<font color=red>Please enter zip code</font>",
                        phone: "<font color=red>Please enter shop phone</font>",
                        href: "<font color=red>Please enter url of shop</font>",
                        area: "<font color=red>Please enter shop area</font>",
                        img: "<font color=red>Please enter image of shop</font>"
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

            });



        </script>

    </head>
    <body> 

        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

            <nav class="uk-navbar uk-margin-large-bottom">
                <ul class="uk-navbar-nav uk-hidden-small">
                    <li>
                        <a href="/dashboard">Dashboard</a>
                    </li>
                    <li class="uk-active">
                        <a href="/shop">Shop</a>
                    </li>
                    <li>
                        <a href="/coupon">Coupon</a>
                    </li>

                </ul>
                <div class="uk-navbar-flip">

                    <ul class="uk-navbar-nav">
                        <li class="uk-parent" data-uk-dropdown>
                            <a href="#">Account</a>

                            <div class="uk-dropdown uk-dropdown-navbar">
                                <ul class="uk-nav uk-nav-navbar">

                                    <li class="uk-nav-header"></li>
                                    <li><a href="/clients/account">View Account</a></li>
                                    <li><a href="/clients/changepwd">Change Password</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="/clients/logout">Logout</a></li>
                                </ul>
                            </div>

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
