@extends('coupon.layout')

@section('content')
<h1>Edit Coupon</h1>

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
    
<script>

    var selected_poi;
    
    /*
     * 
     * Init poi value
     */
    <?php foreach ($pois as $key => $poi) {
        
        if ($key == $poi['_id']) {  ?>
            selected_poi = "<?php echo $poi['_id']; ?>";
        <?php }
        } ?>
    
    function postPreview() {
        
        if(!beforePost()) {
            return false;
        } 
        
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
        
        <?php foreach ($pois as $poi) { ?>
                if(selected_poi == "<?php echo $poi['_id']; ?>") {
                    
                }
        <?php } ?>
            
           
        
        var data =
                {
                    //coupon_layouts : [ document.getElementById('addshop-form').innerHTML ],
                    coupon_layouts :  [ document.getElementById('coupon_layout').value ],
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
        $.post("/api/shop/preview/"+selected_poi, data, function (result) {
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


            console.log(html_string);
        }).fail(function () {
            console.error('error');
        });

    }
    
    $(function () {

        $("#upload_ok_btn").on("dblclick", function (e) {
            showMyImage('upload_image');
        });
        
        document.getElementById('coupon-template-edit-1').style.display = "block";
        document.getElementById('coupon-template-view-1').style.display = "block";
    });

    function showMyImage(fileInput) {
        var files = document.getElementById(fileInput).files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var imageType = /image.*/;
            if (!file.type.match(imageType)) {
                continue;
            }
            var img = document.getElementById("coupon-edit-logo-preview-inner");
            img.file = file;
            var reader = new FileReader();
            reader.onload = (function (aImg) {
                return function (e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    }

    interact('#coupon-edit-logo')
            .draggable(false)
            .inertia(true)
            .restrict({
                drag: "parent",
                endOnly: true
            })
            .resizeable(false);

    interact('#coupon-edit-text')
            .draggable(false)
            .inertia(true)
            .restrict({
                drag: "parent",
                endOnly: true
            })
            .resizeable(false);


    function textPrompt(id) {

        alertify.prompt("Edit Content", function (e, str) {

            if (e) {

                document.getElementById(id).innerText = str;
            } else {

            }
        }, "Default Value");
    }

    function template_changed(id) {
        var myselect = document.getElementById(id);
        if (myselect.options[myselect.selectedIndex].value === "3") {
            document.getElementById('coupon-template-edit-1').style.display = "none";
            document.getElementById('coupon-template-view-1').style.display = "none";
        } else {

            document.getElementById('coupon-template-edit-1').style.display = "block";
            document.getElementById('coupon-template-view-1').style.display = "block";
        }
    }
    
    function poi_changed(id) {
        var myselect = document.getElementById(id);
        selected_poi = myselect.value;
    }
    
    function beforePost() {
        
        if( selected_poi == undefined) {
            alert('Please select a shop');
            return false;
        }
        
        // coupon_layout
        // coupon-template-view-1
        
        var target = document.getElementById('coupon-template-view-1');
        var wrap = document.createElement('div');
        wrap.appendChild(target.cloneNode(true));
        //alert(wrap.innerHTML);
        document.getElementById('coupon_layout').value = wrap.innerHTML;
        
        return true;
    }

</script>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                <div class="uk-panel uk-panel-header">
                <!--<h3 class="uk-panel-title"> </h3>-->

                <form id="editcoupon-form" method="post" action="/coupon/<?php echo $coupon['_id'] ?>/edit" enctype="multipart/form-data" class="uk-form uk-form-stacked">
                    <div class="uk-form-row">
                        <label class="uk-form-label">Coupon Templates</label>
                        <div class="uk-form-controls">
                            
                            <select id="template_id" class="uk-width-1-1" name="template_id" onchange="template_changed('template_id');">
                                <option value="">Select Template</option>
                                <option value="1">Template 1</option>
                            </select>
                        </div> 
                    </div>
                    
                    <div class="uk-form-row">
                        <a href="#upload_img_modal" data-uk-modal></a>
                        <!--<button class="uk-button" type="button" data-uk-modal="{target:'#upload_img_modal'}">Upload</button>-->
                        <div id="upload_img_modal" class="uk-modal"> 

                            <div class="uk-modal-dialog">
                                <a class="uk-modal-close uk-close"></a>
                                <p><input type=file id=upload_image name=file accept="image/*"> </p>
                                <p><input id="upload_ok_btn" class="uk-button uk-button-success uk-modal-close" type="button" value="OK" onclick="showMyImage('upload_image');"></p>

                            </div>

                        </div>
                    </div>
                    <input id="coupon_layout" type="hidden" name="coupon_layout">
                    <div class="uk-form-row">

                        <div id="coupon-template-edit-1" style="margin-left: 0px; padding: 0px; width: 390px; height: 190px; background: #fafafa; color: #444444; border: 1px solid #dddddd; border-radius: 4px; background-color: #ffffff; color: #444444; display: block; position:relative; float: left;">
                            <div class="coupon-template-logo-1" id="coupon-edit-logo" style="position: absolute; top: 0px; left: 0px; width: 120px; height: 120px" data-uk-modal="{target:'#upload_img_modal'}">
                                <div>Logo<p style="font-size: 12px;">100 x 100</p></div>
                            </div>
                            <div id="coupon-edit-text" style="position: absolute; top: 5px; right: 20px; margin-left: 20px; margin-top: 0px; width: 180px; height: 120px;" onclick="textPrompt('coupon-edit-text-preview-inner')">
                                <div>Text1</div>
                                <p>  </p>

                                <div class="bt"></div>
                            </div>
                            <div id="coupon-edit-text2" style="position: absolute; bottom: 5px; left: 0px;width: 380px; height: 50px;" onclick="textPrompt('coupon-edit-text2-preview-inner')">
                                <div>Text2</div>
                                <p>  </p>

                                <div class="bt"></div>
                            </div>
                        </div>
                        
                        
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">Coupon Name</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="coupon_name" value="<?php echo $coupon['coupon_name']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Description</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-width-1-1" cols="100" rows="5" name="coupon_desc"><?php echo $coupon['coupon_desc']; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="uk-form-row">
                            <label class="uk-form-label">Term</label>
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;Begin</span> <input data-uk-datepicker="{weekstart:0, format:'YYYY-MM-DD'}" class='uk-width-5-6 input uk-float-right' name="coupon_begin_date" value='<?php echo substr($coupon['coupon_startdate'], 0, 10); ?>'>
                            </div>

<!--                        <span> - </span>-->
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;End</span> <input data-uk-datepicker="{weekstart:0, format:'YYYY-MM-DD'}" class='uk-width-5-6 input uk-float-right' name="coupon_finish_date" value='<?php echo substr($coupon['coupon_enddate'], 0, 10); ?>'>
                            </div>


                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Target Time </label>
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;Days</span>
                                <div class="uk-width-5-6 uk-float-right"> 
                                    <label><input name="check_days[]" type="checkbox" value="s" <?php if(in_array("s", $coupon['coupon_target_days'])) { echo "checked"; } ?>> S</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="m" <?php if(in_array("m", $coupon['coupon_target_days'])) { echo "checked"; } ?>> M</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="tu" <?php if(in_array("tu", $coupon['coupon_target_days'])) { echo "checked"; } ?>> Tu</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="w" <?php if(in_array("w", $coupon['coupon_target_days'])) { echo "checked"; } ?>> W</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="th" <?php if(in_array("th", $coupon['coupon_target_days'])) { echo "checked"; } ?>> Th</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="f" <?php if(in_array("f", $coupon['coupon_target_days'])) { echo "checked"; } ?>> F</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="sa" <?php if(in_array("sa", $coupon['coupon_target_days'])) { echo "checked"; } ?>> Sa</label>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="uk-form-row">
                            <!--<label class="uk-form-label">Times</label>-->
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;Times</span>
                                <div id="times_container" class="uk-width-5-6 uk-float-right">
                                    <?php $count = 0; $coupon_target_times = $coupon['coupon_target_times']; foreach($coupon_target_times as $target_time) { ?>
                                    <div id="time_row_<?php echo $count; ?>" class="uk-form-row">
                                        <input data-uk-timepicker class='input' name="coupon_begin_time_<?php echo $count; ?>" value='<?php echo $target_time['begin']; ?>'>
                                        <span> - </span>
                                        <input data-uk-timepicker class='input' name="coupon_finish_time_<?php echo $count; ?>" value='<?php echo $target_time['finish']; ?>'>
                                        <a style="visibility: <?php if($count == 0)  echo "hidden;"; else echo "visible;"; ?>" href="" onclick="javascript:removeElement('time_row_<?php echo $count; ?>'); return false;">Remove</a>
                                    </div>
                                    <?php $count++; } ?>
                                </div>
                            </div>

                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> </span>
                                <div class="uk-width-5-6 uk-float-right">
                                    <a onclick="addTime();" >Add More Time...</a>
                                </div>
                                <input type="hidden" id="times_max" name="times_max" value="<?php echo $count; ?>">
                            </div>
                            <script>
                                    function increase() {
                                        var val = parseInt(document.getElementById('times_max').value);
                                        document.getElementById('times_max').value = val + 1;
                                        console.log(document.getElementById('times_max').value);
                                    }
                                    
                                    function addElement(parentId, elementTag, elementId, html) {
                                        // Adds an element to the document
                                        var p = document.getElementById(parentId);
                                        var newElement = document.createElement(elementTag);
                                        newElement.setAttribute('id', elementId);
                                        newElement.setAttribute('class', 'uk-form-row');
                                        newElement.innerHTML = html;
                                        p.appendChild(newElement);
                                        
                                    }

                                    function removeElement(elementId) {
                                        var element = document.getElementById(elementId);
                                        element.parentNode.removeChild(element);
                                    }
                                    
                                    var timeId = parseInt(document.getElementById('times_max').value);
                                    function addTime() {
                                        timeId++;
                                        var html = 
                                                '<input data-uk-timepicker class="input" name="coupon_begin_time_'+ timeId +'" id="coupon_begin_time_'+ timeId +'" value="<?php echo "00:00"; ?>">' +
                                        '<span> - </span>' +
                                        '<input data-uk-timepicker class="input" name="coupon_finish_time_'+ timeId +'" id="coupon_finish_time_'+ timeId +'" value="<?php echo "00:00"; ?>">' +
                                        '<a href="" onclick="javascript:removeElement(\'time_row_' + timeId + '\'); return false;">Remove</a>';
                                        
                                        addElement('times_container', 'div', 'time_row_' + timeId, html);
                                        
                                        increase();
                                    }

                                </script>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Area</label>
                            <div class="uk-width-1-1">
                                <input type="text" class="uk-width-1-1" placeholder="eg.,Bangkok, Chonburi, Jatujak" name="area"  id="area" value="<?php $coupon_areas = $coupon['coupon_areas']; $str = ""; foreach($coupon_areas as $k) { $str = $str."$k, ";} echo $str;  //echo $poi['keyword']; ?>" /> 
                                <!--<textarea class="uk-width-1-1" cols="100" rows="5" name="keywords" id="keywords"></textarea>-->
                                
                                <script>
                                    $(function() {
                                        $('#area').tokenfield({
                                        autocomplete: {
                                          source: ['Bangkok','Chonburi','Surin'],
                                          delay: 100
                                        },
                                        showAutocompleteOnFocus: true
                                      });


                                    } );
                                </script>
                            </div>
                        </div>
                        <!--                    <div class="uk-form-row">
                                                <label class="uk-form-label">Area Available </label>
                                                <div class="uk-form-controls">
                                                    <select class="uk-width-1-3" name="area_id" id="area_id">
                                                        <option value="N/A">Select Province</option>
                        <?php $areas = array(array("name" => "Bangkok"));
                        foreach ($areas as $area) { ?>
                                                                <option value="<?php echo $area['name'] ?>"> <?php echo $area['name']; ?> </option>
<?php } ?>
                                                    </select>
                                                    <span> - </span>
                                                    <select class="uk-width-1-3" name="area_id" id="area_id">
                                                        <option value="N/A">Select Area</option>
                        <?php $areas = array(array("name" => "Bangkok"));
                        foreach ($areas as $area) { ?>
                                                                <option value="<?php echo $area['name'] ?>"> <?php echo $area['name']; ?> </option>
<?php } ?>
                                                    </select>
                                                    
                                                </div>
                                            </div>-->
                        <!--                    <div class="ui-form-row">
                                                <label class="uk-form-label">Begin Date</label>
                                                <input id='beginDate' class='uk-width-1-1 input' name="coupon_startdate" value='<?php //echo "$now";  ?>' />
                                                <script> rome(beginDate);</script>
                                            </div>
                                            <div class="ui-form-row">
                                                <label class="uk-form-label">End Date</label>
                                                <input id='endDate' class='uk-width-1-1 input' name="coupon_enddate" value='<?php //echo "$now";  ?>' />
                                                <script> rome(endDate);</script>
                                            </div>-->

                        <div class="uk-form-row">
                        <label class="uk-form-label">Price</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="coupon_price" value="<?php echo $coupon['coupon_price']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Amount</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="coupon_amt" value="<?php echo $coupon['coupon_amt']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Coupon for Shop</label>
                        <div class="uk-form-controls">
                            <select class="uk-width-1-1" name="poi_id" id="poi_id" onchange="poi_changed('poi_id');">
                                <option>Select Shop</option>
                                <?php foreach ($pois as $key => $poi) { ?>
                                    <option value="<?php echo $key; ?>" <?php if ($key == $poi['_id']) {
                                    echo "selected";
                                } ?> > <?php echo $poi['name']; ?> </option>
<?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <label class="uk-form-label">Generated Coupon Link</label>
                        <div class="uk-form-controls">

                        </div>
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
                                    <button data-uk-modal="{target:'#view_modal-preview'}" class="uk-button uk-button-primary" type="button" onclick="postPreview();">Preview on Device</button>
                                    <!--<button class="uk-button uk-button-primary" type="button" onclick="postPreview();">Preview</button>-->
                                </div>
                                <div class="uk-float-right">
                                    <a href="/coupon"><button class="uk-button uk-button-danger" type="button">Cancel</button></a>
                                    <!--<input type="submit" class="uk-button uk-button-success" value="Save"/>-->
                                    <button type="submit" class="uk-button uk-button-success" onclick="return beforePost();"> Save Coupon </button>

                                </div>
                            
                            <!--<input type="submit" class="uk-button uk-button-primary" value="Save Coupon" onclick="return beforePost();"/>-->
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
                            <p> <strong>Coupon Preview</strong>  </p>
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
                            <!--<iframe width="400px" height="860px" scrolling="no" src="shop/overview/<?php echo $poi['_id'] ?>"> </iframe>-->
                            <div style="width: 400px; height: 200px;">  <?php echo $current_template_content; ?> </div>
                            <p>
                                <a href="#view_modal-<?php echo $coupon['_id']; ?>" data-uk-modal></a>
                                <div id="view_modal-<?php echo $coupon['_id']; ?>" class="uk-modal"> 

                                    <div class="uk-modal-dialog">
                                        <a class="uk-modal-close uk-close"></a>
                                        <iframe width="400px" height="860px" scrolling="no" src="shop/overview/<?php echo $poi['_id'] ?>"> </iframe>
                                    </div>

                                </div>
                                
<!--                            <p>
                                Please save a coupon before preview 
                            </p>
                            <p>
                                <a data-uk-modal="{target:'#view_modal-<?php echo $coupon['_id']; ?>'}"> Click to view </a>
                            </p>-->
                        </center>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <hr class="uk-grid-divider">

    

</div>

@stop
