@extends('shop.layout')

@section('content')

<script type="text/javascript"> 

    $(function () {

    });



    function postPreview() {

        console.log($("#keyword").val());
        var name = $("#name").val();
        var addr = $("#addr").val();
        var zip = $("#zip").val();
        var phone = $("#phone").val();
        var href = $("#href").val();
        var desc = $("#desc").val();
        var cat = $("#cat").val();
        var area = $("#area").val();
        var tzone = $("#tzone").val();
        var img = $("#img").val();
        var latbox = $("#latbox").val();
        var lngbox = $("#lngbox").val();
        var data =
                {
                    name: name,
                    addr: addr,
                    zip: zip,
                    phone: phone,
                    href: href,
                    desc: desc,
                    cat: cat,
                    area: area,
                    tzone: tzone,
                    img: img,
                    loc:
                            {
                                lon: lngbox,
                                lat: latbox
                            }
                };
        $.post("/api/shop/preview/undefined", data, function (result) {
            var iframe = document.getElementById('preview_container');
            var html_string = result;

            var iframedoc = iframe.document;
            if (iframe.contentDocument)
                iframedoc = iframe.contentDocument;
            else if (iframe.contentWindow)
                iframedoc = iframe.contentWindow.document;

            if (iframedoc) {
                iframedoc.open();
                iframedoc.writeln(html_string);
                iframedoc.close();
            } else {
                alert('Cannot inject dynamic contents into iframe.');
            }


            console.log(result);
        }).fail(function () {
            console.error('error');
        });

    }

</script>
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


<h1 class="uk-text-center">Add New Shop</h1>

<div class="uk-container-center uk-width-6-10 uk-margin-top uk-margin-large-bottom">

    <div class="uk-grid" data-uk-grid-margin>

        <div class="uk-width-medium-1-1">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary ">
                <div class="uk-panel uk-panel-header">
                    <h3 class="uk-panel-title"> Shop Information </h3>

                    <form id="addshop-form" method="post" action="/shop/addshop" enctype="multipart/form-data" class="uk-form uk-form-stacked">

                        <div class="uk-form-row">
                            <label class="uk-form-label">Shop Name</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="name" id="name" value="">
                            </div>
                        </div>
                        <script>
                            function onMainCatChange(c_main, c_sub) {

                                var cat_main = document.getElementById(c_main);
                                var cat_sub = document.getElementById(c_sub);

                                if (cat_main.selectedIndex >= 0)
                                {
                                    cat_sub.style.display = "block";
                                    cat_sub.options.length = 0; // clear all options

<?php
foreach ($shop_cat as $key => $scat) {
    $main = $scat['main'];
    $sub = $scat['sub'];
    ?>
                                        if (cat_main.options[cat_main.selectedIndex].value == "<?php echo $main; ?>") {
                                            var option = document.createElement("option");
                                            option.val = "";
                                            option.text = "Select Sub Category";

                                            cat_sub.add(option, cat_sub.options.length);
                                        }
    <?php foreach ($sub as $s) { ?>

                                            if (cat_main.options[cat_main.selectedIndex].value == "<?php echo $main; ?>") {
                                                console.log("<?php echo $s; ?>");

                                                var option = document.createElement("option");
                                                option.val = "<?php echo $s; ?>";
                                                option.text = "<?php echo $s; ?>";

                                                cat_sub.add(option, cat_sub.options.length);
                                            }
    <?php } ?>

<?php } ?>

                                }
                            }
                        </script>
                        <div class="uk-form-row">
                            <label class="uk-form-label"> Categories </label>
                            <div class="uk-form-controls">
                                <div id="cat_main_container" class="uk-form-row">
                                    <div id="cat_row_0" class="uk-grid uk-form-row" data-uk-grid-margin>
                                        <div class="uk-width-2-5">
                                            <select class="uk-width-1-1 cat_main" name="cat_main_0" id="cat_main_0" onchange="onMainCatChange('cat_main_0', 'cat_sub_0')">
                                                <option>Select Main Category</option>
                                                <?php foreach ($shop_cat as $scat) { ?>
                                                    <option value="<?php echo $scat['main']; ?>"><?php echo $scat['main']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="uk-width-2-5">
                                            <select class="uk-width-1-1" name="cat_sub_0" id="cat_sub_0" style="display: none;">
                                                <option>Select Sub Category</option>

                                            </select>
                                        </div>

                                        <div class="uk-width-1-5" style="visibility: hidden;">
                                            <a href="" onclick="javascript:removeElement('cat_row_0');
                                                    return false;">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="cats_max" id="cats_max" value="0">
                                <p><a onclick="addCatMain();">Add More Category... </a></p>

                            </div>

                        </div>

                        <div class="uk-form-row">
                            <label class="uk-form-label">Description</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-width-1-1" cols="100" rows="5" name="desc" id="desc"></textarea>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Keyword</label>
                            <div class="uk-width-1-1">
                                <input type="text" class="uk-width-1-1"  id="keyword" name="keyword" value="" /> 
                                <!--<textarea class="uk-width-1-1" cols="100" rows="5" name="keywords" id="keywords"></textarea>-->

                                <script>
                                    $(function () {
                                        $('#keyword').tokenfield({
                                            autocomplete: {
                                                source: ['free', 'cheap', 'discount'],
                                                delay: 100
                                            },
                                            showAutocompleteOnFocus: true
                                        });


                                    });
                                </script>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Shop Address</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-width-1-1" cols="100" rows="5" name="addr" id="addr"></textarea>
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Zip Code</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="zip" id="zip" value="">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Phone</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="phone" id="phone" value="">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Home page</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="http://" class="uk-width-1-1" name="href" id="href" value="">
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Shop Category</label>
                            <div class="uk-form-controls">
                                <select class="uk-width-1-1" name="cat" id="cat">
                                    <option>Select Shop Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Area</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="e.g., Bangkok, Chonburi, Ratchadapisek" class="uk-width-1-1" name="area" id="area" value="">
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Time Zone</label>
                            <div class="uk-form-controls">
                                <select class="uk-width-1-1" name="tzone" id="tzone">
                                    <option>Select Timezone</option>
                                    <?php foreach ($timezone as $key => $tz) { ?>
                                        <option <?php if ("$key" == "Asia/Bangkok") {
                                        echo "selected";
                                    } ?> value="<?php echo $key; ?>" > <?php echo $tz; ?> </option>
<?php } ?>

                                </select>
                            </div>
                        </div>

                        <script>
                            function urlPrompt(url) {
                                alertify.prompt("You can copy URL below", function (e, str) {

                                    if (e) {

                                    } else {

                                    }
                                }, url);
                            }
                        </script>
                        <div class="uk-form-row">
                            <a href="#my-id" data-uk-modal></a>

                            <div id="my-id" class="uk-modal">
                                <div class="uk-modal-dialog">
                                    <p>Click Image URL and copy it. </p>
                                    <div class="uk-overflow-container"> 
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-1-1">

                                                <ul id="switcher-content" class="uk-switcher">

                                                    <li class="uk-active">
                                                        <?php
                                                        /*
                                                         * Begin foreach()
                                                         */
                                                        $index = 0; // for grid control
                                                        $modal_id = 0; // effect with javascript modal popup, do not re-initial this value
                                                        foreach ($resources as $resource) {
                                                            ?>

                                                                <?php if ($index % 2 == 0) { ?>
                                                                <div class="uk-grid" data-uk-grid-margin>
    <?php } ?>

                                                                <div class="uk-width-medium-2-4">
                                                                    <div class="uk-thumbnail" >
                                                                        <a class="uk-overlay-toggle" data-uk-modal="{target:'#modal-<?php echo "$modal_id"; ?>'}">
                                                                            <div class="uk-overlay">
                                                                                <img style="width: 350px; height: 235px;" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                                                                <div class="uk-overlay-area"></div>

                                                                            </div>
                                                                        </a>
                                                                        <div class="uk-float-left uk-text-small" style="padding-left: 10px;"> <a onclick="urlPrompt('<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>')">Image URL</a> </div>

                                                                    </div>

                                                                    <div id="modal-<?php echo "$modal_id"; ?>" class="uk-modal">
                                                                        <div class="uk-modal-dialog uk-modal-dialog-frameless">
                                                                            <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
                                                                            <img width="600" height="400" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php if (($index + 1) % 2 == 0) { ?>
                                                                </div>
                                                            <?php } ?>

                                                            <?php
                                                            $modal_id++;
                                                            $index++;
                                                        }
                                                        /*
                                                         * End foreach()
                                                         */
                                                        ?>

                                                    </li>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <p> </p>
                                </div>
                            </div>
                        </div>
                        <script>
                            function increase(id) {
                                var val = parseInt(document.getElementById(id).value);
                                document.getElementById(id).value = val + 1;
                                console.log(document.getElementById(id).value);
                            }

                            function addElement(parentId, elementTag, elementId, html) {
                                // Adds an element to the document
                                var p = document.getElementById(parentId);
                                var newElement = document.createElement(elementTag);
                                newElement.setAttribute('id', elementId);
                                newElement.setAttribute('class', 'uk-grid uk-form-row');
                                newElement.setAttribute('data-uk-grid-margin', '');
                                newElement.innerHTML = html;
                                p.appendChild(newElement);
                            }

                            function removeElement(elementId) {
                                // Removes an element from the document
                                var element = document.getElementById(elementId);
                                element.parentNode.removeChild(element);
                            }

                            var catMainId = 0;
                            function addCatMain() {
                                catMainId++;
                                var html =
//                                                '<div class="uk-grid" data-uk-grid-margin>' +
                                        '<div class="uk-width-2-5">' +
                                        '<select class="uk-width-1-1 cat_main" name="cat_main_' + catMainId + '" id="cat_main_' + catMainId + '" onchange="onMainCatChange(\'cat_main_' + catMainId + '\',\'cat_sub_' + catMainId + '\')">' +
                                        '<option>Select Main Category</option>' +
<?php foreach ($shop_cat as $scat) { ?>
                                    '<option value="<?php echo $scat['main']; ?>"><?php echo $scat['main']; ?></option>' +
<?php } ?>
                                '</select>' +
                                        '</div>' +
                                        '<div class="uk-width-2-5">' +
                                        '<select class="uk-width-1-1 cat_sub" name="cat_sub_' + catMainId + '" id="cat_sub_' + catMainId + '">' +
                                        '<option>Select Sub Category</option>' +
<?php //foreach ($shop_cat as $scat) {  ?>
                                '<option value="<?php //echo $scat;  ?>"><?php //echo $scat;  ?></option>' +
<?php //}  ?>
                                '</select>' +
                                        '</div>' +
                                        '<div class="uk-width-1-5">' +
                                        '<a href="" onclick="javascript:removeElement(\'cat_row_' + catMainId + '\'); return false;">Remove</a>';
                                '</div>';

//                                                    '</div>' +


                                addElement('cat_main_container', 'div', 'cat_row_' + catMainId, html);
                                increase("cats_max");
                            }
                            var actualfiles = 0;
                            var fileId = 0; // used by the addFile() function to keep track of IDs
                            function delFile(elementId) {
                                actualfiles--;
                                removeElement(elementId);
                            }

                            function addFile() {
                                if (actualfiles >= 2)
                                    return;

                                actualfiles++;
                                fileId++; // increment fileId to get a unique ID for the new element
                                var html =
                                        '<div class="uk-form-file uk-width-2-5">' +
                                        '<button type="button" class="uk-button">Browse file</button>' +
                                        '<input type="file" id="upload_file_' + fileId + '" name="upload_file_' + fileId + '" onchange="addFileChangeEvent(this);">' +
                                        '</div>' +
                                        '<div class="uk-width-1-5">' +
                                        '<a href="" onclick="javascript:delFile(\'files_row_' + fileId + '\'); return false;">Remove</a>' +
                                        '</div>';
                                addElement('files', 'div', 'files_row_' + fileId, html);
                                increase("files_max");
                            }


                        </script>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Image</label>
                            <div id="files" class="uk-form-controls">

                                <div id="files_row_0" class="uk-grid uk-form-row" data-uk-grid-margin>

                                    <div class="uk-form-file uk-width-2-5">
                                        <button type="button" class="uk-button">Browse file</button>
                                        <input type="file" id="upload_file_0" name="upload_file_0" onchange="addFileChangeEvent(this);">
                                    </div>
                                    <div class="uk-width-1-5">

                                    </div>
                                </div>

                            </div>
                            <input type="hidden" name="files_max" id="files_max" value="0">
                            <p><a onclick="addFile();">Add more file...</a></p>

                        </div>

                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Image</label>
                            <div class="uk-form-controls">
                                <div id="file-label"></div>
                                <input type="file" name="file" id="file" class="custom-file-input">
                            </div>
                            <script>

                                function addFileChangeEvent(id) {
                                    id.previousElementSibling.innerText = "" + id.value;
                                }

                            </script>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label"></label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="http://" class="uk-width-1-1" name="img" id="img" value="">
                            </div>
                        </div>

                        <div class="uk-form-row">
                            <label class="uk-form-label">Shop Location</label>
                            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                            <div id="map_canvas" style="width: 100%; height:350px"></div>

                            <div id="latlong" class="reset-this">
                                <p class="tj-hidden">Latitude: <input size="20" type="text" id="latbox" name="lat" ></p>
                                <p class="tj-hidden">Longitude: <input size="20" type="text" id="lngbox" name="lon" ></p>
                            </div>
                        </div>

                        <hr>

                        <div class="uk-form-row">
                            <div class="uk-form-controls">
                                <div class="uk-float-left">
                                    <a href="#view_modal-preview" data-uk-modal></a>
                                    <div id="view_modal-preview" class="uk-modal"> 
                                        <a class="uk-modal-close uk-close"></a>
                                        <div class="uk-modal-dialog uk-text-center">
                                            <iframe id="preview_container" width="400px" height="860px" style="width: 400px; height: 860px;" scrolling="no" src=""> </iframe>
                                        </div>

                                    </div>
                                    <button data-uk-modal="{target:'#view_modal-preview'}" class="uk-button uk-button-primary" type="button" onclick="postPreview();">Preview</button>

                                </div>
                                <div class="uk-float-right">
                                    <a href="/shop"><button class="uk-button uk-button-danger" type="button">Cancel</button></a>

                                    <button type="submit" class="uk-button uk-button-success"> Save </button>

                                </div>


                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>

</div>

@stop
