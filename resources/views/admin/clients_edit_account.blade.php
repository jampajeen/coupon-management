@extends('admin.layout')

@section('content')
<h1>Edit Account Information</h1>

<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">

    <div class="uk-grid" data-uk-grid-margin>

        <div class="uk-width-medium-2-3">
            <div class="uk-panel uk-panel-header">

                <h3 class="uk-panel-title">Edit Profile</h3>

                <form id="editaccount-form" method="post" action="/admin/clients/<?php echo $client['_id']; ?>/edit" class="uk-form uk-form-stacked">

                    <div class="uk-form-row">
                        <label class="uk-form-label">Your Name</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="client_name" value="<?php echo $client['client_name']; ?>">
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">Your Email</label>
                        <div class="uk-form-controls">
                            <input type="email" placeholder="" class="uk-width-1-1" name="client_email" value="<?php echo $client['client_email']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Description</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-width-1-1" cols="100" rows="5" name="client_desc"><?php echo $client['client_desc']; ?></textarea>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">Your Address</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-width-1-1" cols="100" rows="5" name="client_addr"><?php echo $client['client_addr']; ?></textarea>
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Contact</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="client_contact" value="<?php echo $client['client_contact']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Phone</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="client_telephone" value="<?php echo $client['client_telephone']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <label class="uk-form-label">Shop Name</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="client_show_name" value="<?php echo $client['client_show_name']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <label class="uk-form-label">Shop Category</label>
                        <div class="uk-form-controls">
                            <select class="uk-width-1-1" name="client_cat">
                                <option>Shop Type</option>
                                <?php foreach ($shop_cat as $scat) { ?>
                                    <option <?php if($scat == $client['client_cat']) {echo "selected";} ?>><?php echo $scat; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Website</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="http://" class="uk-width-1-1" name="client_website" value="<?php echo $client['client_website']; ?>">
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <div class="uk-form-controls">
                            <input type="submit" class="uk-button uk-button-primary" value="Save"/>
                        </div>
                    </div>

                </form>

            </div>
        </div>


    </div>

</div>

@stop
