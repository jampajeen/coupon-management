<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rinxor Co., Ltd.</title>
        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->

        <!-- Optional theme -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">-->

        <link rel="stylesheet" href="vendor/uikit/css/uikit.gradient.min.css">
        <link rel="stylesheet" href="css/style.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="vendor/jquery/jquery-1.11.1.min.js"></script>
        <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->
        <script src="vendor/uikit/js/uikit.min.js"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
    </head>
    <body>
        <div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

            <nav class="uk-navbar uk-margin-large-bottom">
                <!--<a class="uk-navbar-brand uk-hidden-small" href="/dashboard">Dashboard</a>-->
                <ul class="uk-navbar-nav uk-hidden-small">
                    <ul class="uk-navbar-nav uk-hidden-small">
                    <li class="uk-active">
                        <a href="/dashboard">Dashboard</a>
                    </li>
                    <li>
                        <a href="/shop">Shop</a>
                    </li>
                    <li>
                        <a href="/coupon">Coupon</a>
                    </li>
                    
<!--                    <li>
                        <a href="/resources">Media Resources</a>
                    </li>-->
                </ul>
                </ul>
                <div class="uk-navbar-flip">

                    <ul class="uk-navbar-nav">
                        <li class="uk-parent" data-uk-dropdown>
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
