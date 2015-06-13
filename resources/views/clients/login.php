<!DOCTYPE html>
<html class="uk-height-1-1">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
        <link rel="stylesheet" href="../vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="../css/style.css">
        
        <script src="../vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="../vendor/uikit/js/uikit.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script>
  
  
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#login-form").validate({
    
        // Specify the validation rules
        rules: {
            client_name: "required",
            client_email: {
                required: true,
                email: true
            }
        },
        
        // Specify the validation error messages
        messages: {
            client_email: "<font color=red>Please enter a valid email address</font>",
            client_password: {
                required: "<font color=red>Please provide a password</font>"
            }
        },
        
        submitHandler: function(form) {
            form.submit();
            //alert('');
        }
    });

  });
  
  </script>
    </head>
    <body class="uk-height-1-1">
        <div class="uk-vertical-align uk-text-center uk-height-1-1 uk-margin-top">
            <div class="uk-vertical-align-middle" style="width: 300px;">

                <a href="/"><img class="uk-margin-bottom" width="140" height="120"src="http://rinxor.com/assets/images/logo.png" alt=""></a>
                <?php echo "<br> <font color=red>".$error."</font>"; ?>
                <form id="login-form" method="post" action="/clients/login" class="uk-panel uk-panel-box uk-form">
                    <div class="uk-form-row uk-text-small">
                        <a class="uk-float-right uk-link uk-link-muted" href="/clients/register">Sign up</a>
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="email" placeholder="Email" name="client_email" id="client_name">
                    </div>
                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="password" placeholder="Password" name="client_password" id="client_password">
                    </div>
                    <div class="uk-form-row">
                        <input type="submit" class="uk-width-1-1 uk-button uk-button-primary uk-button-large" value="Login">
                    </div>
                    <div class="uk-form-row uk-text-small">
                        <!--<label class="uk-float-left"><input type="checkbox"> Remember Me</label>-->
                        <a class="uk-float-right uk-link uk-link-muted" href="/clients/forgotpwd">Forgot Password?</a>
                        
                        
                    </div>
                </form>

            </div>
        </div>
    </body>
</html>
