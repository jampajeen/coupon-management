@extends('shop.layout')

@section('content')
<h1><?php echo $poi['name'] . ""; ?></h1>
<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <div class="uk-grid" data-uk-grid-margin>
        <div class="uk-width-medium-1-2">
            <div class="uk-grid">
                <div class="uk-width-6-6">
                    <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                        </p>-->
                        <p>
                            <strong>Description</strong>
                            <br> <?php echo $poi['desc']; ?>
                        </p>
                        <p>
                            <strong>Address</strong>
                            <br> <?php echo $poi['addr']; ?>
                        </p>
                        <p>
                            <strong>Zip</strong>
                            <br> <?php echo $poi['zip']; ?>
                        </p>
                        <p>
                            <strong>Phone</strong>
                            <br><?php echo $poi['phone']; ?>
                        </p>
                        <p>
                            <strong>Homepage</strong>
                            <br><?php echo $poi['href']; ?>
                        </p>
                        <p>
                            <strong>Category</strong>
                            <br> <?php echo $poi['cat']; ?>
                        </p>
                        <p>
                            <strong>Area</strong>
                            <br> <?php echo $poi['area']; ?>
                        </p>
                        <p>
                            <strong>Time Zone</strong>
                            <br> <?php echo $poi['tzone']; ?>
                        </p>
                        <p>
                            <strong>Shop Image</strong>
                            <br> 
                            <a href="#view_modal-1" data-uk-modal></a>
                        <div id="view_modal-1" class="uk-modal"> 

                            <div class="uk-modal-dialog">
                                <a class="uk-modal-close uk-close"></a>
                                <div style=" width: 60%;"><img src="<?php echo $poi['img']; ?>"/></div>
                            </div>

                        </div>
                        <a data-uk-modal="{target:'#view_modal-1'}"><?php echo $poi['img']; ?></a>

                        </p>

                    </div>

                </div>
            </div>
        </div>

        <div class="uk-width-medium-1-2">
            <div class="uk-grid">
                <div class="uk-width-6-6">
                    <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                        <center>
                            <p> <strong>Mobile Preview</strong>  </p>
                            <iframe width="400px" height="860px" scrolling="no" src="shop/overview/<?php echo $poi['_id'] ?>"> </iframe>

                        </center>
                    </div>

                </div>
            </div>
        </div>

    </div>


</div>

@stop
