@extends('clients.layout')

@section('content')
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
            alert("Password has been changed");
        }
        
    </script>
<h1>Change Password</h1>
<div class="uk-vertical-align uk-text-center uk-height-1-1">
            <div class="uk-vertical-align-middle" style="width: 400px;">
                <?php if(isset($error)) { echo "<br> <font color=red><strong>".$error."</strong></font>"; } ?>
                <?php if(isset($success)) { echo "<br> <font color=green><strong>".$success."</strong> </font>"; } ?>
                <form id="changepwd-form" method="post" action="/clients/changepwd" class="uk-panel uk-panel-box uk-form">
<!--                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="Current Password" name="current_password">
                    </div>-->
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="New Password" name="new_password" id="new_password">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="Confirm New Password" name="confirm_new_password" id="confirm_new_password">
                    </div>
                    <div class="uk-form-row">
                        <input type="submit" class="uk-width-1-1 uk-button uk-button-primary uk-button-large" value="Save"/>
<!--                        <a class="uk-float-right uk-width-2-5 uk-button uk-button-primary uk-button-large" href="#">Cancel</a>-->
                    </div>
                    
                </form>

            </div>
        </div>
@stop
