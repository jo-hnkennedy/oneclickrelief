<?php

require_once 'include/config.php';
require_once 'include/function.php';
require_once 'include/session.php';

//set error
GLOBAL $error;
$error = false;

//get login code from clever if any
if( isset( $_GET['code'] ) && !empty( $_GET['code'] ) ) {
    require_once 'oauth2/clever.php';
}

//check user
if( $user ) {
    header('Location: account/home.php');
}


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Mesa onTime</title>
        <!-- Bootstrap gi Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheget" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
        <!-- Plugin CSS -->
        <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">
        <!-- Animate CSS -->
        <link href="css/animate.min.css" rel="stylesheet">
        <!-- UI Styles -->
        <link href="css/ui-style.css" rel="stylesheet">
        <!-- Theme CSS -->
        <link href="css/creative.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body id="page-top">
        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand page-scroll" href="home.php"><img src="/img/mesa_logo.png" alt="Mesa"> onTime</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="top-menu">
                    <ul class="nav navbar-nav navbar-right">
                        <?php echo setMenu( 'public',  $top_menu, '', $account ); ?>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <header class="long">
            <div class="header-content">
                <div class="header-content-inner">
                    <h1 id="homeHeading">Mesa onTime</h1>
                    <hr>
                    <p>Mission Control For Districts, Campuses &amp; Students.</p>
                    <a href="#mission" class="btn btn-default btn-xl page-scroll">The Mission</a>
                    <a href="#login" class="btn btn-primary btn-xl page-scroll" data-toggle="modal" data-target="#login" data-dismiss="modal">Start onTime</a>
                </div>
            </div>
        </header>

        <section id="mission">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">The Mission</h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="service-box">
                            <i class="fa fa-4x fa-diamond text-primary sr-icons"></i>
                            <h3>Sturdy Applications</h3>
                            <p class="text-muted">Get applications running smoothly and entirely bug free.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="service-box">
                            <i class="fa fa-4x fa-paper-plane text-primary sr-icons"></i>
                            <h3>Be Ready to Ship</h3>
                            <p class="text-muted">Release new versions of applications more often.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="service-box">
                            <i class="fa fa-4x fa-newspaper-o text-primary sr-icons"></i>
                            <h3>Keeping Up to Date</h3>
                            <p class="text-muted">Keep a close eye on the teams progress during development.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="service-box">
                            <i class="fa fa-4x fa-heart text-primary sr-icons"></i>
                            <h3>Made with Love</h3>
                            <p class="text-muted">You have to make your applications with much love!</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div class="login-modal modal fade<?php echo ( $error ) ? ' in' : '' ?> " id="login" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Login to Mesa onTime</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="well">
                                    <form id="login-form" class="form-horizontal text-left user_form" method="POST" action="account/login.php" novalidate="novalidate">
                                        <div class="status_text alert alert-info alert-dismissible fade <?php echo ( $error ) ? 'in show' : 'hide'; ?>" role="alert">
                                            <div><?php echo ( $error ) ? $error : ''; ?></div>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <label for="username" class="control-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="user" value="" required="" title="Please enter you username" placeholder="example@gmail.com">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="control-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="pswd" value="" required="" title="Please enter your password">
                                            <span class="help-block"></span>
                                        </div>
                                        <div id="loginErrorMsg" class="alert alert-error hide">Wrong username or password</div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" id="remember"> Remember login
                                            </label>
                                            <sup class="help-block m10">(if this is a private computer)</sup>
                                        </div>
                                        <button type="submit" class="btn btn-warning btn-block submit_btn">Login</button>
                                        <br>
                                        <p class="m10 middle text-center">
                                            <a href="/login.php?forgot=1" data-dismiss="modal">Forgot password</a>
                                        </p>
                                        <br>
                                        <input type="hidden" class="redirect" name="redirect" value="account/dashboard.php?<?php echo http_build_query($_GET); ?>">
                                        -->
                                            <div class="text-center middle center">
                                            <a href="https://clever.com/oauth/district-picker?response_type=code&redirect_uri=https%3A%2F%2Fwww.mesaontime.com%2Fapp%2Findex.php&client_id=41f56eff7fe5d53a8096">
                                                <img src="img/clever-login.png" />
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/jquery/jquery-ui.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <!-- Plugin JavaScript -->
        <script src="vendor/jquery/jquery.easing.min.js"></script>
        <script src="vendor/scrollreveal/scrollreveal.min.js"></script>
        <script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
        <!-- Theme JavaScript -->
        <script src="js/creative.js"></script>
        <script src="js/index.js"></script>
        <script src="js/login.js"></script>
    </body>

    </html>
