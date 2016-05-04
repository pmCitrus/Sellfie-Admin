<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        <!-- Bootstrap CSS -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
        <link href="{{ URL::asset('assets/auth_theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        
        <!-- MetisMenu CSS -->
        <link href="{{ URL::asset('assets/auth_theme/bower_components/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="{{ URL::asset('assets/auth_theme/dist/css/timeline.css') }}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{ URL::asset('assets/auth_theme/dist/css/sb-admin-2.css') }}" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="{{ URL::asset('assets/auth_theme/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
<!--        <style>
            .container-outer-udf { overflow: scroll; width: 950px; height: 100%; }
            .container-inner-udf { width: 100%; }
        </style>-->
    </head>
    
    <body>
        <div class="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <!-- /.navbar-header -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">SELLFIE ADMIN</a>
                </div>
            
                <!-- /.navbar-header -->
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
<!--                            <li>
                                <a href="{{ route('auth.logout') }}">
                                    <i class="fa fa-lock fa-fw"></i>
                                    Change Password
                                </a>
                            </li>-->
                            <li>
                                <a href="{{ route('auth.logout') }}">
                                    <i class="fa fa-sign-out fa-fw"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}" id='nav_dashboard'>
                                    <i class="fa fa-dashboard fa-fw"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="glyphicon glyphicon-user"></i>
                                    User Management
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('users') }}" id='nav_users'>
                                            <i class="fa fa-users fa-fw"></i>
                                            Admin Users
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-home fa-fw"></i>
                                    Store Preferences
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('sellers', ['seller_status' => 'sign_up']) }}" id='nav_sellers'>
                                            <i class="fa fa-user fa-fw"></i>
                                            Sellers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('products', ['product_status' => 'active']) }}" id='nav_products'>
                                            <i class="fa fa-camera fa-fw"></i>
                                            Products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('collect_links', ['product_status' => 'active']) }}" id='nav_collect_links'>
                                            <i class="fa fa-list-alt fa-fw"></i>
                                            Collect Links
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-stack-overflow fa-fw"></i>
                                    Payments
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('orders', ['status_code' => 'all']) }}" id='payments_orders'>
                                            <i class="fa fa-barcode fa-fw"></i>
                                            Orders
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('collect_payments', ['status_code' => 'all']) }}" id='payments_collects'>
                                            <i class="fa fa-credit-card fa-fw"></i>
                                            Collect Link Payments
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('transactions', ['status_code' => 'all']) }}" id='payments_transactions'>
                                            <i class="fa fa-signal fa-fw"></i>
                                            Transactions
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('commission_settings') }}" id='payments_commissions'>
                                            <i class="fa fa-umbrella fa-fw"></i>
                                            Commission Settings
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('rod_settings') }}" id='payments_rods'>
                                            <i class="glyphicon glyphicon-hourglass fa-fw"></i>
                                            ROD Settings
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('settlements_upload_view') }}" id='payments_settlements'>
                                            <i class="fa fa-bar-chart-o fa-fw"></i>
                                            Settlements
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" id='nav_disputes'>
                                    <i class="fa fa-bomb fa-fw"></i>
                                    Disputes
                                </a>
                            </li>
                            <li>
                                <a href="#" id='main_reports'>
                                    <i class="fa fa-download fa-fw"></i>
                                    Reports
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ route('reports', ['report_type' => 'sellers']) }}" id='reports_sellers'>
                                            <i class="fa fa-user fa-fw"></i>
                                            Sellers
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('kyc', ['kyc_status' => 'new']) }}" id='nav_kyc'>
                                    <i class="fa fa-envelope fa-fw"></i>
                                    KYC
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
            <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">
                @yield('content')
            </div>
            
        </div>

        <!-- jQuery -->
        <script src="//code.jquery.com/jquery.js"></script>
        <!-- DataTables -->
        <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        
        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{ URL::asset('assets/auth_theme/bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>
        
        <!-- Custom Theme JavaScript -->
        <script src="{{ URL::asset('assets/auth_theme/dist/js/sb-admin-2.js') }}"></script>

        <!-- App scripts -->
        @stack('scripts')
    </body>
    
</html>