@extends('admin.layout')

@section('content')
<h1>Edit User Information</h1>

<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <hr class="uk-grid-divider">

    <div class="uk-grid" data-uk-grid-margin>

        <div class="uk-width-medium-2-3">
            <div class="uk-panel uk-panel-header">

                <h3 class="uk-panel-title">Edit Profile</h3>

                <form id="editaccount-form" method="post" action="/admin/users/<?php echo $user['_id']; ?>/edit" class="uk-form uk-form-stacked">

                    <div class="uk-form-row">
                        <label class="uk-form-label">First Name</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="first_name" value="<?php echo $user['first_name']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Your Name</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="" class="uk-width-1-1" name="last_name" value="<?php echo $user['last_name']; ?>">
                        </div>
                    </div>
                    
                    <div class="uk-form-row">
                        <label class="uk-form-label">Gender</label>
                        <div class="uk-form-controls">
                            <select class="uk-width-1-1" name="gender">
                                <option <?php if("male" == $user['gender']) {echo "selected";} ?> value="male">Male</option>
                                <option <?php if("female" == $user['gender']) {echo "selected";} ?> value="female">Female</option>
                                
                            </select>
                        </div>
                    </div>

                    <div class="uk-form-row">
                        <label class="uk-form-label">Your Email</label>
                        <div class="uk-form-controls">
                            <input type="email" placeholder="" class="uk-width-1-1" name="email" value="<?php echo $user['email']; ?>">
                        </div>
                    </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Language</label>
                        <div class="uk-form-controls">
                            <select class="uk-width-1-1" name="locale">
                                <option <?php if("en_US" == $user['locale']) {echo "selected";} ?> value="en_US">English</option>
                                <option <?php if("th_TH" == $user['locale']) {echo "selected";} ?> value="th_TH">Thai</option>
                            </select>
                        </div>
                    </div>
                    <div class="uk-form-row">
                            <label class="uk-form-label">Time Zone</label>
                            <div class="uk-form-controls">
                                <select class="uk-width-1-1" name="timezone">
                                    <option>Select Timezone</option>
                                    <?php foreach ($timezone as $key => $tz) { ?>
                                        <option value="<?php echo $key; ?>" <?php if ($key == $user['timezone']) {
                                        echo "selected";
                                    } ?>> <?php echo $tz; ?> </option>
<?php } ?>
                                </select>
                            </div>
                        </div>
                    <div class="uk-form-row">
                        <label class="uk-form-label">Name</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-width-1-1" cols="100" rows="5" name="name"><?php echo $user['name']; ?></textarea>
                        </div>
                    </div>

                    
                    <div class="uk-form-row">
                        <label class="uk-form-label">Website</label>
                        <div class="uk-form-controls">
                            <input type="text" placeholder="http://" class="uk-width-1-1" name="link" value="<?php echo $user['link']; ?>">
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
