@extends('coupon.layout')

@section('content')
<h1>Add Coupon</h1>

<script>

    var selected_poi;

    function postPreview() {

        if (!beforePost()) {
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
            if (selected_poi == "<?php echo $poi['_id']; ?>") {

            }
<?php } ?>



        var data =
                {
                    //coupon_layouts : [ document.getElementById('addshop-form').innerHTML ],
                    coupon_layouts: [document.getElementById('coupon_layout').value],
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
        $.post("/api/shop/preview/" + selected_poi, data, function (result) {
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

        document.getElementById('coupon-template-edit-1').style.display = "none";
        document.getElementById('coupon-template-view-1').style.display = "none";

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
        if (document.getElementById('poi_id').value == "N/A") {
            alert('You must create at least one shop before create a coupon');
            return false;
        }

        if (selected_poi == undefined) {
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
                    <h3 class="uk-panel-title"> </h3>
                    <link rel="stylesheet" href="css/coupon.css">
                    <form id="addcoupon-form" method="post" action="/coupon/addcoupon" enctype="multipart/form-data" class="uk-form uk-form-stacked">
                        <div class="uk-form-row">
                            <label class="uk-form-label">Coupon Templates</label>
                            <div class="uk-form-controls">
                                <!--<label class="uk-form-label" style="color: #000000">You can change the template from menu <span style="color: #d32c46; text-decoration: underline;">"Insert -> Insert Template"</span>, and you can pick the image URL from button <span style="color: #d32c46; text-decoration: underline;">"Browse Images"</span></label>-->
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
                                <div id="coupon-edit-text2" style="position: absolute; top: 130px; left: 0px;width: 380px; height: 50px;" onclick="textPrompt('coupon-edit-text2-preview-inner')">
                                    <div>Text2</div>
                                    <p>  </p>

                                    <div class="bt"></div>
                                </div>
                            </div>




                        </div>

                        <div class="uk-form-row">
                            <label class="uk-form-label">Coupon Name</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="coupon_name" value="">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Description</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-width-1-1" cols="100" rows="5" name="coupon_desc"></textarea>
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Term</label>
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;Begin</span> <input data-uk-datepicker="{weekstart:0, format:'YYYY-MM-DD'}" class='uk-width-5-6 input uk-float-right' name="coupon_begin_date" value='<?php echo substr($now, 0, 10); ?>'>
                            </div>

<!--                        <span> - </span>-->
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;End</span> <input data-uk-datepicker="{weekstart:0, format:'YYYY-MM-DD'}" class='uk-width-5-6 input uk-float-right' name="coupon_finish_date" value='<?php echo substr($now, 0, 10); ?>'>
                            </div>


                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Target Time </label>
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;Days</span>
                                <div class="uk-width-5-6 uk-float-right">
                                    <label><input name="check_days[]" type="checkbox" value="s" checked> S</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="m" checked> M</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="tu" checked> Tu</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="w" checked> W</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="th" checked> Th</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="f" checked> F</label> &nbsp;
                                    <label><input name="check_days[]" type="checkbox" value="sa" checked> Sa</label>
                                    
                                </div>
                            </div>

                        </div>
                        <div class="uk-form-row">
                            <!--<label class="uk-form-label">Times</label>-->
                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> &nbsp;Times</span>
                                <div id="times_container" class="uk-width-5-6 uk-float-right">
                                    <div id="time_row_0" class="uk-form-row">
                                        <input data-uk-timepicker class='input' name="coupon_begin_time_0" value='<?php echo "00:00"; ?>'>
                                        <span> - </span>
                                        <input data-uk-timepicker class='input' name="coupon_finish_time_0" value='<?php echo "00:00"; ?>'>
                                        <a style="visibility: hidden;" href="" onclick="javascript:removeElement('time_row_0'); return false;">Remove</a>
                                    </div>
                                </div>
                            </div>

                            <div class="uk-form-row">
                                <span class="uk-width-1-6"> </span>
                                <div class="uk-width-5-6 uk-float-right">
                                    <a onclick="addTime();" >Add More Time...</a>
                                </div>
                                <input type="hidden" id="times_max" name="times_max" value="1">
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
                                    
                                    var timeId = 0;
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
                                <input type="text" class="uk-width-1-1" placeholder="eg.,Bangkok, Chonburi, Jatujak" name="area"  id="area" value="" /> 
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
                                <input type="text" placeholder="" class="uk-width-1-1" name="coupon_price" value="">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Amount</label>
                            <div class="uk-form-controls">
                                <input type="text" placeholder="" class="uk-width-1-1" name="coupon_amt" value="">
                            </div>
                        </div>
                        <div class="uk-form-row">
                            <label class="uk-form-label">Coupon for Shop</label>
                            <div class="uk-form-controls">
                                <select class="uk-width-1-1" name="poi_id" id="poi_id" onchange="poi_changed('poi_id');">
                                    <option value="N/A">Select Shop</option>
                                    <?php foreach ($pois as $poi) { ?>
                                        <option value="<?php echo $poi['_id'] ?>"> <?php echo $poi['name']; ?> </option>
<?php } ?>
                                </select>

                            </div>
                        </div>
                        <div class="uk-form-row" style="display: none;">
                            <label class="uk-form-label">Generated Coupon Link</label>
                            <div class="uk-form-controls">
                                <input disabled type="text" placeholder="" class="uk-width-1-1" name="coupon_href" value="">
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
                        <input type="hidden" name="htmleditor_content">

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
                            <div style="width: 400px; height: 200px;">
                                <div id="coupon-template-view-1" style="margin-left: 5px; padding: 0px; width: 390px; height: 190px; background: #fafafa; color: #444444; border: 1px solid #dddddd; border-radius: 4px; background-color: #ffffff; color: #444444; display: block; position:relative; float: left;">
                                    <div id="coupon-edit-logo-preview" style="position: absolute; top: 0px; left: 0px;">
                                        <img style="width: 100px; height: 100px;" id="coupon-edit-logo-preview-inner" src=""/>
                                    </div>

                                    <div id="coupon-edit-text-preview" style="position: absolute; top: 5px; right: 20px; margin-left: 20px; margin-top: 0px; width: 180px; height: 120px;" >
                                        <div></div>
                                        <p id="coupon-edit-text-preview-inner">  </p>

                                        <div class="bt"></div>
                                    </div>
                                    <div id="coupon-edit-text2-preview" style="position: absolute; top: 100px; left: 0px;width: 380px; height: 50px;">
                                        <div></div>
                                        <p id="coupon-edit-text2-preview-inner">   </p>

                                        <div class="bt"></div>
                                    </div>

                                </div>
                            </div>
                        </center>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!--<hr class="uk-grid-divider">-->

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-3-3">

        </div>


    </div>

</div>

@stop
