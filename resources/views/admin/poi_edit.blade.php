@extends('admin.layout')

@section('content')

<script>
    function beforePost() {
        var index = 0;
            var res = [];

                var files_max = document.getElementById("files_max");
                var files_changed = document.getElementById('files_changed');

                files_changed.value = ""; // clear old value

                var len = parseInt(files_max.value);
                for (var i = 0; i <= len; i++) {

                    var str = "upload_file_" + i;
                    var obj = document.getElementById(str);
                    if (obj !== null) {
                        if (obj.value !== "") {
                            //files_changed.value += i +", ";
                            // New File
                            var filename = obj.value;
                            var lastIndex = filename.lastIndexOf("\\");
                            if (lastIndex >= 0) {
                                filename = filename.substring(lastIndex + 1);
                            }

                            var flag = "new";
                            res.push({
                                "flag": flag,
                                "value": filename
                            });
                        } else {
                            var flag = "none";
                            var item = obj.previousElementSibling.innerText;
                            if (item == "Browse file") {
                                flag = "none";
                            } else {
                                flag = "old";
                            }
                            res.push({
                                "flag": flag,
                                "value": item
                            });
                        }
                    }
                }

                //console.log("files_changed.value = " + files_changed.value);
                files_changed.value = JSON.stringify(res);
                console.log(res);


                return true;
        }
</script>
<script>
    var updated;
    <?php if(isset($updated)) { echo "updated = true;"; } ?>
        function alert(txt) {
            alertify.set({buttonFocus: "ok"}); // "none", "ok", "cancel"
            alertify.alert(txt, function (e) {
                if (e) {
                    
                } else {
                }
            });

        }
        // confirm dialog

        if( updated != undefined) {
            alert("Save successfully");
        }
        
    </script>
    
<script type="text/javascript">
    
    $(function() {
        
    });
    
    
                
    function postPreview() {
        //alert(document.getElementById('name'));
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
                    //coupon_layouts : [ document.getElementById('editshop-form').innerHTML ],

                    //name : 'TJ Cafe',
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
                //just in case of browsers that don't support the above 3 properties.
                //fortunately we don't come across such case so far.
                alert('Cannot inject dynamic contents into iframe.');
            }


            console.log(result);
        }).fail(function () {
            console.error('error');
        });

    }

</script>

<h1>Edit Shop</h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                <div class="uk-panel uk-panel-header">
                    <h3 class="uk-panel-title"> Edit Shop Information </h3>
                    <form id="editshop-form" method="post" action="/admin/poi/<?php echo $poi['_id']; ?>/edit" enctype="multipart/form-data" class="uk-form uk-form-stacked">

                        <div class="uk-form-row">
                            <label class="uk-form-label">Shop Name</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="name" id="name" value="<?php echo $poi['name']; ?>">
                            </div>
                        </div>
                        <script>
                            function onMainCatChange(c_main, c_sub) {
                                
                                var cat_main = document.getElementById(c_main);
                                var cat_sub = document.getElementById(c_sub);
                                
                                if (cat_main.selectedIndex>=0)
                                {
                                    cat_sub.style.display = "block";
                                    cat_sub.options.length = 0; // clear all options
                                        
<?php
foreach ($shop_cat as $key => $scat) {
    $main = $scat['main'];
    $sub = $scat['sub'];
    ?>
                                        if( cat_main.options[cat_main.selectedIndex].value == "<?php echo $main; ?>") {
                                                              var option = document.createElement("option");
                                                                option.val = "";
                                                                option.text = "Select Sub Category";
                                                                //cat_sub.appendChild(option);
                                                                cat_sub.add(option, cat_sub.options.length);
                                        }
    <?php foreach ($sub as $s) { ?>
                                                    //console.log('');
                                        if (cat_main.options[cat_main.selectedIndex].value == "<?php echo $main; ?>") {
                                                console.log("<?php echo $s; ?>");

                                                var option = document.createElement("option");
                                                option.val = "<?php echo $s; ?>";
                                                option.text = "<?php echo $s; ?>";
                                                //cat_sub.appendChild(option);
                                                cat_sub.add(option, cat_sub.options.length);
                                        }
    <?php } ?>

<?php } ?>

                                }
                            }
                        </script>
                        <div class="uk-form-row">
                            <label class="uk-form-label"> Categories </label>
                            <?php //if(isset($poi['cat']['main'])) { ?>
                            <div class="uk-form-controls">
                                <div id="cat_main_container" class="uk-form-row">
                                        
                                        <?php $count = 0; $cats = $poi['cat']; foreach($cats as $c) { ?> <!-- Begin foreach $scats -->
                                        <div id="cat_row_<?php echo $count; ?>" class="uk-grid uk-form-row" data-uk-grid-margin>
                                        <div class="uk-width-2-5">
                                            <select class="uk-width-1-1 cat_main" name="cat_main_<?php echo $count; ?>" id="cat_main_<?php echo $count; ?>" onchange="onMainCatChange('cat_main_<?php echo $count; ?>','cat_sub_<?php echo $count; ?>')">
                                                <option>Select Main Category</option>
                                                <?php foreach ($shop_cat as $key => $scat) { ?> <!-- Begin foreach $shop_cat -->
                                                    <option <?php if($scat['main'] == $c['main'] ) echo "selected"; ?> value="<?php echo $scat['main']; ?>"><?php echo $scat['main']; ?></option>
                                                <?php } ?> <!-- End foreach $shop_cat -->
                                            </select>
                                        </div>
                                        <div class="uk-width-2-5">
                                            <select class="uk-width-1-1" name="cat_sub_<?php echo $count; ?>" id="cat_sub_<?php echo $count; ?>">
                                                <option>Select Sub Category</option>
                                                <?php foreach ($shop_cat as $key => $scat) { $main = $scat['main']; $sub = $scat['sub']; ?> <!-- Begin foreach $shop_cat -->
                                                    <?php foreach($sub as $s) { if($scat['main'] == $c['main'] ) { ?> <!-- Begin foreach $sub & if -->
                                                    <option <?php if( $s == $c['sub'] ) echo "selected"; ?> value="<?php echo $s; ?>"><?php echo $s; ?></option>
                                                    <?php } } ?> <!-- End foreach $sub & if -->
                                                <?php } ?> <!-- End foreach $shop_cat -->
                                            </select>
                                        </div>
                                        
                                        <div class="uk-width-1-5" style="visibility: <?php if($count == 0)  echo "hidden;"; else echo "visible;"; ?>">
                                            <a href="" onclick="javascript:removeElement('cat_row_<?php echo $count; ?>'); return false;">Remove</a>
                                        </div>
                                        </div>
                                        <?php $count++; } ?> <!-- End foreach $scats -->
                                        
                                    
                                </div>
                                <input type="hidden" name="cats_max" id="cats_max" value="<?php echo ($count - 1); ?>">
                                <p><a onclick="addCatMain();">Add More Category... </a></p>
                                
                            </div>
                            <?php //} ?>
                        </div>
                        
                        <div class="uk-form-row">
                            <label class="uk-form-label">Description</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-width-1-1" cols="100" rows="5" name="desc" id="desc"><?php echo $poi['desc']; ?></textarea>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <?php if(isset($poi['keyword'])) { ?>
                            <label class="uk-form-label">Keyword</label>
                            <div class="uk-width-1-1">
                                <input type="text" class="uk-width-1-1"  id="keyword" name="keyword" value="<?php $keywords = $poi['keyword']; $str = ""; foreach($keywords as $k) { $str = $str."$k, ";} echo $str;  //echo $poi['keyword']; ?>" /> 
                                
                                <script>
                                    $(function() {
                                        $('#keyword').tokenfield({
                                        autocomplete: {
                                          source: ['free','cheap','discount'],
                                          delay: 100
                                        },
                                        showAutocompleteOnFocus: true
                                      });


                                    } );
                                </script>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Shop Address</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-width-1-1" cols="100" rows="5" name="addr" id="addr"><?php echo $poi['addr']; ?></textarea>
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Zip Code</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="zip" id="zip" value="<?php echo $poi['zip']; ?>">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Phone</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="phone" id="phone" value="<?php echo $poi['phone']; ?>">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Home page</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="http://" class="uk-width-1-1" name="href" id="href" value="<?php echo $poi['href']; ?>">
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Shop Category</label>
                            <div class="uk-form-controls">
                                <select class="uk-width-1-1" name="cat" id="cat">
                                    <option>Select Shop Category</option>
                                    <?php //foreach ($shop_cat as $scat) { ?>
                                        <option value="<?php //echo $scat; ?>"><?php //echo $scat; ?></option>
                                    <?php //} ?>
                                </select>
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Area</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="e.g., Bangkok, Chonburi" class="uk-width-1-1" name="area" id="area" value="<?php echo $poi['area']; ?>">
                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Time Zone</label>
                            <div class="uk-form-controls">
                                <select class="uk-width-1-1" name="tzone" id="tzone">
                                    <option>Select Timezone</option>
                                    <?php foreach ($timezone as $key => $tz) { ?>
                                        <option value="<?php echo $key; ?>" <?php if ($key == $poi['tzone']) {
                                        echo "selected";
                                    } ?>> <?php echo $tz; ?> </option>
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
                                        newElement.setAttribute('data-uk-grid-margin','');
                                        newElement.innerHTML = html;
                                        p.appendChild(newElement);
                                        
                                        updateFileChangedValue();
                                    }

                                    function removeElement(elementId) {
                                        // Removes an element from the document
                                        var element = document.getElementById(elementId);
                                        element.parentNode.removeChild(element);
                                        
                                        updateFileChangedValue();
                                    }
                                    
                                    var catMainId = parseInt(document.getElementById('cats_max').value);
                                    function addCatMain() {
                                        catMainId++;
                                        var html = 
//                                                '<div class="uk-grid" data-uk-grid-margin>' +
                                                    '<div class="uk-width-2-5">' +
                                                    '<select class="uk-width-1-1 cat_main" name="cat_main_'+catMainId+'" id="cat_main_'+catMainId+'" onchange="onMainCatChange(\'cat_main_'+catMainId+'\',\'cat_sub_'+catMainId+'\')">' +
                                                    '<option>Select Main Category</option>' +
                                                    <?php foreach ($shop_cat as $scat) { ?>
                                                        '<option value="<?php echo $scat['main']; ?>"><?php echo $scat['main']; ?></option>' +
                                                    <?php } ?>
                                                '</select>' +
                                                        '</div>' +

                                                        '<div class="uk-width-2-5">' +
                                                    '<select class="uk-width-1-1 cat_sub" name="cat_sub_'+catMainId+'" id="cat_sub_'+catMainId+'">' +
                                                    '<option>Select Sub Category</option>' +
                                                    <?php //foreach ($shop_cat as $scat) { ?>
                                                        '<option value="<?php //echo $scat; ?>"><?php //echo $scat; ?></option>' +
                                                    <?php //} ?>
                                                '</select>' +
                                                        '</div>' +
                                                        
                                                        '<div class="uk-width-1-5">' +
                                                        '<a href="" onclick="javascript:removeElement(\'cat_row_' + catMainId + '\'); return false;">Remove</a>';
                                                        '</div>' ;
                                                    
//                                                    '</div>' +
                                                    
                                            
                                            addElement('cat_main_container', 'div', 'cat_row_' + catMainId, html);
                                            increase("cats_max");
                                    }
                                    
                                    var actualfiles = <?php echo (count($poi['img']) - 1); ?>;
                                    var fileId = 0; // used by the addFile() function to keep track of IDs
                                    function delFile(elementId) {
                                        actualfiles--;
                                         removeElement(elementId);
                                    }
                                    
                                    function addFile() {
                                        if(actualfiles >= 2) return;
                                        
                                        actualfiles++;
                                        fileId++; // increment fileId to get a unique ID for the new element
                                            var html =
                                                    '<div class="uk-form-file uk-width-2-5">' +
                                            '<button type="button" class="uk-button">Browse file</button>' +
                                            '<input type="file" id="upload_file_'+ fileId +'" name="upload_file_'+ fileId +'" onchange="addFileChangeEvent(this);">' +
                                        '</div>' +
                                        '<div class="uk-width-1-5">' +
                                            '<a href="" onclick="javascript:delFile(\'files_row_'+ fileId +'\'); return false;">Remove</a>' +
                                        '</div>';
                                            addElement('files', 'div', 'files_row_' + fileId, html);
                                            increase("files_max");
                                        }
                                        
                                        // ไปทำให้เหมือนกันทุกไฟล์
                                    $(function() {
                                        fileId = parseInt( document.getElementById('files_max').value);
                                    });
                                    
                                </script>
                                <script>
                                    
                                function updateFileChangedValue() {
                                    var files_max = document.getElementById("files_max");
                                    var files_changed = document.getElementById('files_changed');
                                    
                                    files_changed.value = ""; // clear old value
                                    
                                    var len = parseInt(files_max.value);
                                    for(var i = 0; i <= len; i++) {
                                        
                                        var str = "upload_file_" + i;
                                        var obj = document.getElementById( str );
                                        if( obj !== null ) {
                                            if(obj.value !== "") {
                                                files_changed.value += i +", ";
                                            }
                                        }
                                    }
                                    
                                    console.log("files_changed.value = " + files_changed.value);
                                }
                                
                                function addFileChangeEvent(id) {
                                    id.previousElementSibling.innerText = "" + id.value;
                                    updateFileChangedValue();
                                }

                            </script>
                                <div class="uk-form-row">
                                    <label class="uk-form-label">Image</label>
                                    <?php //if(isset($img[0])) { ?>
                                    <div id="files" class="uk-form-controls">
                                        <?php $count = 0; $imgs = $poi['img']; foreach( $imgs as $img) { ?>
                                        
                                        <div id="files_row_<?php echo $count; ?>" class="uk-grid uk-form-row" data-uk-grid-margin>
                                            <!--<span class="uk-float-left"> &nbsp; <a target="_blank" href="/useruploads/image/<?php echo $img; ?>"><?php echo $img; ?></a></span>-->
                                            <div class="uk-form-file uk-width-2-5">
                                                <button type="button" class="uk-button"><?php echo $img; ?></button>
                                                <input type="file" id="upload_file_<?php echo $count; ?>" name="upload_file_<?php echo $count; ?>" onchange="addFileChangeEvent(this);">
                                            </div>
                                            <!--<div class="uk-form-row"> <a href="/useruploads/image/<?php echo $img; ?>" target="_blank"> View Image </a> </div>-->
                                            <div class="uk-width-1-5" style="visibility: <?php if($count == 0)  echo "hidden;"; else echo "visible;"; ?>">
                                            <a href="" onclick="javascript:delFile('files_row_<?php echo $count; ?>'); return false;">Remove</a>
                                        </div>
                                        </div>
                                        <?php $count++; } ?>
                                        
                                    </div>
                                    <input type="hidden" name="files_changed" id="files_changed" value="">
                                    <input type="hidden" name="files_max" id="files_max" value="<?php echo (count($poi['img']) - 1); ?>">
                                    <p><a onclick="addFile();">Add more file...</a></p>
                                    <?php //} ?>
                                </div>
<!--                        <div class="uk-form-row">
                            
                            <label class="uk-form-label">Image</label>
                            <div class="uk-form-controls">
                                <div id="files">
                                    <div id="files_row_0" class="uk-grid uk-form-row" data-uk-grid-margin>
                                        <div id="file-label" class="custom-file-input-label uk-width-1-1"></div>
                                        <input type="file" id="upload_file_0" name="upload_file_0" class="custom-file-input" onchange="addFileChangeEvent(this);" />
                                    </div>
                                </div>
                                <input type="hidden" name="files_max" id="files_max" value="0">
                                <p><input type="button" class="uk-button" value="Add More File" onclick="addFile();" /></p>
                            </div>
                        </div>-->
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Image</label>
                            <div class="uk-form-controls">
                                <!-- This is a button toggling the modal -->
<!--                                <button class="uk-button" type="button" data-uk-modal="{target:'#my-id'}">Browse Images</button>-->
                                    <div id="file-label"></div>
                                    <input type="file" name="file" id="file" class="custom-file-input">
                                    
                            </div>
                            
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label"></label>
                            <div class="uk-form-controls">
                                <a href="#view_modal-1" data-uk-modal></a>
                                <div id="view_modal-1" class="uk-modal"> 

                                    <div class="uk-modal-dialog">
                                        <a class="uk-modal-close uk-close"></a>
                                        <div style=" width: 60%;"><img src="<?php // echo $poi['img']; ?>"/></div>
                                    </div>

                                </div>
                                <a data-uk-modal="{target:'#view_modal-1'}"><?php // echo $poi['img']; ?></a>
                                <input  style="display: none;" type="text" placeholder="http://" class="uk-width-1-1" id="img" name="img" value="<?php // echo $poi['img']; ?>">
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

                        </div>

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
                                    <!--<input type="submit" class="uk-button uk-button-success" value="Save"/>-->
                                    <button type="submit" class="uk-button uk-button-success" onclick="return beforePost();"> Save </button>

                                </div>


                            </div>
                        </div>

                    </form>
                    
                </div>
            </div>

        </div>

        <div class="uk-width-medium-1-2">
            <div class="uk-grid">
                <div class="uk-width-6-6">
                    <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                        <center>
                            <p> <strong>Mobile Preview</strong>  </p>
<!--                            <p>
                            <form class="uk-form uk-form-stacked">
                                <div class="uk-form-controls">
                                    <select class="uk-width-1-1">
                                        <option>Select Coupon to Show in Preview</option>
                                        <option value="">Coupon 1</option>
                                        <option value="">Coupon 2</option>
                                    </select>
                                </div>
                            </form>

                            </p>-->
                            <!--<iframe width="400px" height="860px" scrolling="no" src="place_map.html?id=53c94bb1f8535992788b4f2c&coupon_id=53e2ff02f8535935018b456b&lat=13.769273021934&lon=100.57361125946&place=Cyber%20World%20Tower"> </iframe>-->
                            <iframe width="400px" height="860px" scrolling="no" src="shop/overview/<?php echo $poi['_id'] ?>"> </iframe>
                        </center>
                    </div>

                </div>
            </div>
        </div>

    </div>


</div>

@stop
