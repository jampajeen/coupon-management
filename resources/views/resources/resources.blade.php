@extends('resources.layout')

@section('content')

<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">


    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-1-1">
            <h1 class="uk-heading-large">Media Resources </h1>
            <p class="uk-text-large"> </p>
        </div>
    </div>

    <hr class="uk-grid-divider">

    <div>
        <form id="upload-form" method="post" action="/resources/upload" enctype="multipart/form-data" class="uk-form uk-form-stacked">
            <div class="uk-form-row">
                <label class="uk-form-label">Upload as</label>
                <div class="uk-form-controls">
                    <select class="uk-width-1-4" name="file_type">
                        <option value="image">Image</option>
                        <option value="logo">Logo</option>
                    </select>
                </div>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label"> </label>
                <div class="uk-form-controls">
                    <div id="file-label"></div>
                    <input type="file" name="file" id="file" class="custom-file-input">
                </div>
            </div>
            <div class="uk-form-row" id="submit_ready" style="display: none;">
                <label class="uk-form-label"></label>
                <div class="uk-form-controls">
                    <button type="submit" class="uk-button"> Upload Now </button>
                </div>
            </div>

            <script>
                function confirm_delete(id) {
                    alertify.set({labels: {
                            ok: "Yes",
                            cancel: "No"
                        }});
                    alertify.set({buttonFocus: "cancel"}); // "none", "ok", "cancel"
                    alertify.confirm("Do you really want to delete this image?", function (e) {
                        if (e) {
                            document.location.href = "/resources/" + id + "/delete";
                        } else {
                        }
                    });

                }

                function urlPrompt(url) {
                    alertify.prompt("You can copy URL below", function (e, str) {

                        if (e) {

                        } else {

                        }
                    }, url);
                }

                function updated(event) {
                    var count = 0;
                    for (i = 0; i < event.path.length; i++) {
                        var tmpObj = event.path[i];

                        if (tmpObj.value !== undefined) {
                            //            alert(tmpObj.value);
                            document.getElementById('file-label').innerText = "" + tmpObj.value;
                            document.getElementById("submit_ready").style.display = "block";

                            count++;
                        }
                    }
                    //
                    //alert(count + " file" + (count==1?"":"s") + " parsed.");
                }

                var el = document.getElementsByClassName('custom-file-input')[0];
                el.onchange = updated;
            </script>

        </form>
    </div>
    <br>
    <hr class="uk-grid-divider">

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-1-1">

            <ul class="uk-subnav uk-subnav-pill" data-uk-switcher="{connect:'#switcher-content'}">
                <li class="uk-active"><a href="#">All</a></li>
                <li><a href="#">Image</a></li>
                <li><a href="#">Logo</a></li>
            </ul>

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

                        <?php if ($index % 3 == 0) { ?>
                            <div class="uk-grid" data-uk-grid-margin>
                            <?php } ?>

                            <div class="uk-width-medium-1-3">
                                <div class="uk-thumbnail" >
                                    <a class="uk-overlay-toggle" data-uk-modal="{target:'#modal-<?php echo "$modal_id"; ?>'}">
                                        <div class="uk-overlay">
                                            <img style="width: 350px; height: 235px;" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                            <div class="uk-overlay-area"></div>

                                        </div>
                                    </a>
                                    <div class="uk-float-left uk-text-small" style="padding-left: 10px;"> <a onclick="urlPrompt('<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>')">Image URL</a> </div>
                                    <div class="uk-float-right uk-text-small" style="padding-right: 10px;"> <a onclick="confirm_delete('<?php echo $resource['_id'] ?>')">Delete</a> </div>
                                </div>

                                <div id="modal-<?php echo "$modal_id"; ?>" class="uk-modal">
                                    <div class="uk-modal-dialog uk-modal-dialog-frameless">
                                        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
                                        <img width="600" height="400" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                    </div>
                                </div>
                            </div>

                            <?php if (($index + 1) % 3 == 0) { ?>
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

                <li>
                    <?php
                    /*
                     * Begin foreach()
                     */
                    $index = 0; // reset index value for grid redering
                    foreach ($resources as $resource) {
                        ?>

                        <?php if ($index % 3 == 0) { ?>
                            <div class="uk-grid" data-uk-grid-margin>
                            <?php } ?>

                            <?php if ($resource['resource_type'] == "image") { ?>

                                <div class="uk-width-medium-1-3">
                                <div class="uk-thumbnail" >
                                    <a class="uk-overlay-toggle" data-uk-modal="{target:'#modal-<?php echo "$modal_id"; ?>'}">
                                        <div class="uk-overlay">
                                            <img style="width: 350px; height: 235px;" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                            <div class="uk-overlay-area"></div>

                                        </div>
                                    </a>
                                    <div class="uk-float-left uk-text-small" style="padding-left: 10px;"> <a onclick="urlPrompt('<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>')">Image URL</a> </div>
                                    <div class="uk-float-right uk-text-small" style="padding-right: 10px;"> <a onclick="confirm_delete('<?php echo $resource['_id'] ?>')">Delete</a> </div>
                                </div>

                                <div id="modal-<?php echo "$modal_id"; ?>" class="uk-modal">
                                    <div class="uk-modal-dialog uk-modal-dialog-frameless">
                                        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
                                        <img width="600" height="400" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if (($index + 1) % 3 == 0) { ?>
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

                <li>
                    <?php
                    /*
                     * Begin foreach()
                     */
                    $index = 0; // reset index value for grid redering
                    foreach ($resources as $resource) {
                        ?>

                        <?php if ($index % 3 == 0) { ?>
                            <div class="uk-grid" data-uk-grid-margin>
                            <?php } ?>

                            <?php if ($resource['resource_type'] == "logo") { ?>

                                <div class="uk-width-medium-1-3">
                                <div class="uk-thumbnail" >
                                    <a class="uk-overlay-toggle" data-uk-modal="{target:'#modal-<?php echo "$modal_id"; ?>'}">
                                        <div class="uk-overlay">
                                            <img style="width: 350px; height: 235px;" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                            <div class="uk-overlay-area"></div>

                                        </div>
                                    </a>
                                    <div class="uk-float-left uk-text-small" style="padding-left: 10px;"> <a onclick="urlPrompt('<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>')">Image URL</a> </div>
                                    <div class="uk-float-right uk-text-small" style="padding-right: 10px;"> <a onclick="confirm_delete('<?php echo $resource['_id'] ?>')">Delete</a> </div>
                                </div>

                                <div id="modal-<?php echo "$modal_id"; ?>" class="uk-modal">
                                    <div class="uk-modal-dialog uk-modal-dialog-frameless">
                                        <a href="" class="uk-modal-close uk-close uk-close-alt"></a>
                                        <img width="600" height="400" src="<?php echo "http://" . $resource['resource_server'] . "/" . $resource['resource_path'] . "/" . $resource['resource_new_name']; ?>" alt="">
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <?php if (($index + 1) % 3 == 0) { ?>
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

@stop
