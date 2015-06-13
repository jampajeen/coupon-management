<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link href="vendor/jquery-ui/jquery-ui.css" type="text/css" rel="stylesheet">
        <link href="vendor/sliptree-bootstrap-tokenfield/dist/css/tokenfield-typeahead.css" type="text/css" rel="stylesheet">

        <link href="vendor/sliptree-bootstrap-tokenfield/dist/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">

        <link rel="stylesheet" href="vendor/alertify/themes/alertify.core.css" />
        <link rel="stylesheet" href="vendor/alertify/themes/alertify.default.css" />

        <link rel="stylesheet" href="vendor/rome/dist/rome.css">
        <link rel="stylesheet" href="vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="vendor/uikit/css/addons/uikit.gradient.addons.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/coupon.css">

        <script src="vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="vendor/jquery-ui/jquery-ui.min.js"></script>

        <script src="vendor/uikit/js/uikit.min.js"></script>
        <script src="vendor/uikit/js/addons/datepicker.js"></script>
        <script src="vendor/uikit/js/addons/form-select.js"></script>
        <script src="vendor/uikit/js/addons/autocomplete.js"></script>
        <script src="vendor/uikit/js/addons/timepicker.js"></script>

        <script src="vendor/interactjs/interact.js"></script>

        <script src="vendor/alertify/lib/alertify.min.js"></script> 

        <script src="vendor/tinymce/js/tinymce/tinymce.min.js"></script>
        <script src="js/script.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

        <script type="text/javascript" src="vendor/sliptree-bootstrap-tokenfield/dist/bootstrap-tokenfield.js" charset="UTF-8"></script>
        <script type="text/javascript" src="vendor/sliptree-bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js" charset="UTF-8"></script>

        <script>

            $(function () {

                $("#addcoupon-form").validate({
                    rules: {
                        coupon_name: "required",
                        coupon_desc: "required",
                        coupon_startdate: {
                            required: true,
                            date: true
                        },
                        coupon_enddate: {
                            required: true,
                            date: true
                        },
                        coupon_price: {
                            required: true,
                            number: true
                        },
                        coupon_amt: {
                            required: true,
                            number: true
                        },
                        template_id: {
                            required: function (elm) {
                                if (elm.options[elm.options.selectedIndex].value != "-1") {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    },
                    messages: {
                        coupon_name: "<font color=red>Please enter Coupon Name</font>",
                        coupon_desc: "<font color=red>Please enter description</font>",
                        coupon_startdate: "<font color=red>Please choose start date</font>",
                        coupon_enddate: "<font color=red>Please choose end date</font>",
                        coupon_price: "<font color=red>Please enter price in number format</font>",
                        coupon_amt: "<font color=red>Please enter amount in number format</font>",
                        template_id: "<font color=red>Please choose template and edit your coupon</font>",
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });

                $("#editcoupon-form").validate({
                    rules: {
                        coupon_name: "required",
                        coupon_desc: "required",
                        coupon_startdate: {
                            required: true,
                            date: true
                        },
                        coupon_enddate: {
                            required: true,
                            date: true
                        },
                        coupon_price: {
                            required: true,
                            number: true
                        },
                        coupon_amt: {
                            required: true,
                            number: true
                        }
                    },
                    messages: {
                        coupon_name: "<font color=red>Please enter Coupon Name</font>",
                        coupon_desc: "<font color=red>Please enter description</font>",
                        coupon_startdate: "<font color=red>Please choose start date</font>",
                        coupon_enddate: "<font color=red>Please choose end date</font>",
                        coupon_price: "<font color=red>Please enter price in number format</font>",
                        coupon_amt: "<font color=red>Please enter amount in number format</font>",
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
                    <li class="uk-active">
                        <a href="/coupon">Coupon</a>
                    </li>

                </ul>
                <div class="uk-navbar-flip">

                    <ul class="uk-navbar-nav">
                        <li class="uk-parent" data-uk-dropdown>
                            <a href="#">Account</a>

                            <div class="uk-dropdown uk-dropdown-navbar">
                                <ul class="uk-nav uk-nav-navbar">

                                    <li class="uk-nav-header"> </li>
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
