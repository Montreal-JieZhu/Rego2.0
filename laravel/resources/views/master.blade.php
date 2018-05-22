<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>@yield('title')</title>
        @section('head')
        <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                    <!-- CSRF Token -->
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                        <meta property="og:type" content="website" >
                            <meta property="og:url" content="https://lipis.github.io/flag-icon-css/" ><!--This is for country flag icon-->
                                <link href="{{ asset('assets5/css/docs.css') }}" rel="stylesheet"><!--This is for country flag icon-->
                                    <link href="{{ asset('assets5/css/flag-icon.css') }}" rel="stylesheet"><!--This is for country flag icon-->
                                        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.min.css">
                                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
                                                        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
                                                            <link rel="stylesheet" href="{{ asset('assets5/css/styles.min.css') }} ">
                                                                <!-- Styles
                                                                <link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
                                                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                                                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                                                                <script src="{{ asset('assets5/js/jquery.twbsPagination.js') }} " type="text/javascript"></script><!--This is for paginatin controller-->
                                                                <script src="{{ asset('assets5/js/docs.js') }} " type="text/javascript"></script><!--This is for country flag icon-->

                                                                <style>
                                                                    #myInput {
                                                                        padding: 20px;
                                                                        margin-top: -6px;
                                                                        border: 0;
                                                                        border-radius: 0;
                                                                        background: #f1f1f1;
                                                                    }
                                                                    .flag-size {
                                                                        width: 20px;
                                                                        height: 17px;
                                                                    }
                                                                    /* Remove the navbar's default rounded borders and increase the bottom margin */
                                                                    .navbar {
                                                                        margin-bottom: 50px;
                                                                        border-radius: 0;
                                                                    }

                                                                    /* Remove the jumbotron's default bottom margin */
                                                                    .jumbotron {
                                                                        margin-bottom: 0;
                                                                    }

                                                                    /* Add a gray background color and some padding to the footer */
                                                                    footer {
                                                                        background-color: #f2f2f2;
                                                                        padding: 25px;
                                                                    }
                                                                    /* Note: Try to remove the following lines to see the effect of CSS positioning */
                                                                    .affix {
                                                                        top: 0;
                                                                        width: 100%;
                                                                        z-index: 9999 !important;
                                                                    }

                                                                    .affix + .container-fluid {
                                                                        padding-top: 110px;
                                                                    }
                                                                </style>
                                                                @show
                                                                </head>
                                                                <body>
                                                                    @section('header')
                                                                    <div class="jumbotron container-fluid" style="height:250px;">
                                                                        <div class="container text-center">
                                                                            <h1>Online Store</h1>
                                                                            <p>Mission, Vission & Values</p>
                                                                        </div>
                                                                    </div>

                                                                    <nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="247">
                                                                        <div class="container-fluid">
                                                                            <div class="navbar-header">
                                                                                <a class="navbar-brand" href="/">Rego</a>
                                                                            </div>
                                                                            <div class="collapse navbar-collapse" id="myNavbar">
                                                                                <ul class="nav navbar-nav">
                                                                                    <li><a href="#">Home</a></li>
                                                                                    <li><a href="#">Franchisee</a></li>
                                                                                    <li class="dropdown">
                                                                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Language <span class="caret"></span></a>
                                                                                        <ul class="dropdown-menu">
                                                                                            <li><a href="#"><div class="flag-size img-thumbnail flag flag-icon-background flag-icon-gb" title="gb" id="eg"></div> English</a></li>
                                                                                            <li><a href="#"><div class="flag-size img-thumbnail flag flag-icon-background flag-icon-fr" title="eg" id="fr"></div> French</a></li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ul>
                                                                                <form class="navbar-form navbar-left" action="/action_page.php">
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" placeholder="Search" name="search">
                                                                                            <div class="input-group-btn">
                                                                                                <button class="btn btn-default" type="submit">
                                                                                                    <i class="glyphicon glyphicon-search"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                    </div>
                                                                                </form>
                                                                                <ul class="nav navbar-nav navbar-right">
                                                                                    @guest
                                                                                    <li>
                                                                                        <a>
                                                                                            Hi,Customer
                                                                                        </a>
                                                                                    </li>
                                                                                    @else
                                                                                    <li class="dropdown">
                                                                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                                                            Hi,{{Auth::user()->name}}
                                                                                            <span class="caret"></span>
                                                                                        </a>
                                                                                        <ul class=" dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                                            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Your Account</a></li>
                                                                                            <li><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Your Reservation</a></li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    @endguest
                                                                                    <li>
                                                                                        <a id="registerBtn" href="{{ route('register') }}">
                                                                                            <span class="glyphicon glyphicon-registration-mark"></span> Register
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        @guest
                                                                                        <a id="loginoutBtn" href="{{ route('login') }}">
                                                                                            <span class="glyphicon glyphicon-log-in"></span> Login
                                                                                        </a>
                                                                                        @else
                                                                                        <a id="loginoutBtn" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                                                document.getElementById('logout-form').submit();">
                                                                                            Logout <span class="glyphicon glyphicon-log-out"></span>
                                                                                        </a>
                                                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                                            {{ csrf_field() }}
                                                                                        </form>
                                                                                        @endguest
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </nav>
                                                                    @show
                                                                    @yield('content')
                                                                    @section('footer')
                                                                    <footer class="container-fluid text-center">
                                                                        <p>Online Store Copyright</p>
                                                                        <form class="form-inline">
                                                                            Get deals:
                                                                            <input type="email" class="form-control" size="50" placeholder="Email Address">
                                                                                <button type="button" class="btn btn-danger">Sign Up</button>
                                                                        </form>
                                                                    </footer>
                                                                    @show
                                                                    <!--<script src="{{ asset('js/app.js') }}"></script>-->
                                                                </body>
                                                                </html>