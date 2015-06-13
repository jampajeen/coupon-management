<!DOCTYPE html>
<html class="uk-height-1-1">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
<!--        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>-->
  
        <link rel="stylesheet" href="../vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="../css/style.css">
        
        <script src="../vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="../vendor/uikit/js/uikit.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script>
  
  
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#register-form").validate({
    
        // Specify the validation rules
        rules: {
            client_name: "required",
            client_email: {
                required: true,
                email: true
            },
            client_password: {
                required: true,
                minlength: 5
            },
            client_confirm_password: {
                required: true,
                minlength: 5
            },
            agree: {required: true}
        },
        
        // Specify the validation error messages
        messages: {
            client_name: "<font color=red>Please enter your name</font>",
            client_password: {
                required: "<font color=red>Please enter a password</font>",
                minlength: "<font color=red>Your password must be at least 5 characters long</font>"
            },
            client_confirm_password: {
                required: "<font color=red>Please confirm a password</font>",
                minlength: "<font color=red>Your password must be at least 5 characters long</font>"
            },
            client_email: "<font color=red>Please enter a valid email address</font>",
            agree: "<font color=red>Please accept user agreement</font>"
        },
        
        submitHandler: function(form) {
            form.submit();
            //alert('');
        }
    });

  });
  
  </script>
    </head>
    <body>
        <div class="uk-vertical-align uk-text-center uk-height-1-1 uk-margin-top uk-margin-bottom">
            <div class="uk-vertical-align-middle" style="width: 450px;">

                <a href="/"><img class="uk-margin-bottom" width="140" height="120"src="http://rinxor.com/assets/images/logo.png" alt=""></a>
                <?php echo "<br> <font color=red>".$error."</font>"; ?>
                <form id="register-form" method="post" action="/clients/register"  class="uk-panel uk-panel-box uk-form">
                    
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Admin Name" name="client_name" id="client_name">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="email" placeholder="Email" name="client_email" id="client_email">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Phone" name="client_telephone" id="client_telephone">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="Password" name="client_password" id="client_password">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="Confirm Password" name="client_confirm_password" id="client_confirm_password">
                    </div>
                    
                    <div class="uk-form-row" style="display: none;">
                        <!--<input class="uk-width-1-1 uk-form-large" type="text" placeholder="Description">-->
                        <textarea class="uk-width-1-1" id="form-h-t" cols="100" rows="5" placeholder="Description" name="client_desc" id="client_desc"></textarea>
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <!--<input class="uk-width-1-1 uk-form-large" type="text" placeholder="Address Line1">-->
                        <textarea class="uk-width-1-1" id="form-h-t" cols="100" rows="5" placeholder="Address" name="client_addr" id="client_addr"></textarea>
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Contact Person Name" name="client_contact" id="client_contact">
                    </div>
                    
                    <div class="uk-form-row" style="display: none;">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="Shop Name" name="client_show_name" id="client_show_name" value="N/A">
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <select class="uk-width-1-1 uk-form-large" name="client_cat">
                            <option selected="" value="N/A">Shop Type</option>
                            <?php foreach($shop_cat as $scat) { ?>
                            <option><?php echo $scat; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="uk-form-row" style="display: none;">
                        <input class="uk-width-1-1 uk-form-large" type="text" name="client_website" id="client_website" placeholder="Web Site URL" type="url">
                    </div>
                    <div class="uk-form-row uk-text-small">
                        <a href="#my-id" data-uk-modal></a>
                        <!-- This is the modal -->
                        <div id="my-id" class="uk-modal">
                            <div class="uk-modal-dialog">
                                <p><h5>User Agreement</h5></p>
                                <div class="uk-overflow-container">
                                    <div style="text-align: left;">
                                        Place license agreement here.
                                    </div>
                                </div>
                                <p> </p>
                            </div>
                        </div>
                        <label class="uk-float-left"><input type="checkbox" name="agree" id="agree"> Accept Agreement <a data-uk-modal="{target:'#my-id'}"> learn more...</a></label>
                    </div>
                    <div class="uk-form-row">
                        <input type="submit" class="uk-width-1-2 uk-button uk-button-primary uk-button-large" value="Register" />
                    </div>
                    
                </form>

            </div>
        </div>
    </body>
</html>
