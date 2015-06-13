<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.core.css" />
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.default.css" />
        <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="../vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="../css/style.css">

        <script src="../vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="../vendor/uikit/js/uikit.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="vendor/alertify/lib/alertify.min.js"></script> 

        <script>

            $(function () {

                $("#editaccount-form").validate({
                    rules: {
                        client_name: "required",
                        client_email: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        client_name: "<font color=red>Please enter your name</font>",
                        client_email: "<font color=red>Please enter a valid email address</font>"
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

                $("#changepwd-form").validate({
                    rules: {
                        new_password: "required",
                        confirm_new_password: "required"
                    },
                    messages: {
                        new_password: {
                            required: "<font color=red>Please input a password</font>",
                            minlength: "<font color=red>Your password must be at least 5 characters long</font>"
                        },
                        confirm_new_password: {
                            required: "<font color=red>Please input a password confirmation</font>",
                            minlength: "<font color=red>Your password must be at least 5 characters long</font>"
                        }
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

            });

        </script>
    </head>
    <body>
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

            <nav class="uk-navbar uk-margin-large-bottom">
                <ul class="uk-navbar-nav uk-hidden-small">
                    <li>
                        <a href="/dashboard">Dashboard</a>
                    </li>
                    <li>
                        <a href="/shop">Shop</a>
                    </li>
                    <li>
                        <a href="/coupon">Coupon</a>
                    </li>

                </ul>
                <div class="uk-navbar-flip">

                    <ul class="uk-navbar-nav">
                        <li class="uk-parent uk-active" data-uk-dropdown>
                            <a href="#">Account</a>

                            <div class="uk-dropdown uk-dropdown-navbar">
                                <ul class="uk-nav uk-nav-navbar">

                                    <li class="uk-nav-header"></li>
                                    <li><a href="/clients/account">View Account</a></li>
                                    <li><a href="/clients/changepwd">Change Password</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="/clients/logout">Logout</a></li>
                                </ul>
                            </div>

                        </li>
                    </ul>

                </div>
            </nav>
            @yield('content')

        </div>
    </body>
</html>
