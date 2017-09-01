<?php

include_once '../include/config.php';
include_once '../include/function.php';
include '../include/session.php';

//set post function

//set post function
if( isset( $_POST['user'] ) ) {
    //login user
    $results = login_user( $_POST );
    die( json_encode( $results ) ); 
} 
//redirect to account
elseif( $user && !isset( $_GET['logout'] ) ) {
    header('Location: home.php');
}

//set name
$name = isset( $_GET['activate'] ) ? $_GET['activate'] : '';

//set redirect
$redir = isset( $_GET['redir'] ) ? $_GET['redir'] : '';
//check if empty
if( empty( $redir ) ) {
   $redir = '/account/home.php?' . http_build_query($_GET);
}


//set dashboard
$page_name = 'login';
$page_title = 'Login';
$page_excerpt = 'Account login';
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $page_excerpt; ?>">
    <meta name="author" content="">

    <title>Mesa onTime - Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="../vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="../css/creative.css" rel="stylesheet">

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
            <?php importModule('navbar_header'); ?>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="page-scroll" href="#login">Login</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header id="login" class="login long">
        <div class="header-content">
            <div class="header-content-inner">
                <h1 id="homeHeading">Account Login</h1>
                <hr>
                <div class="col-md-6 middle">
                    <form id="login-form" class="text-left user_form" method="POST" action="login.php">
                        <?php if( !isset($_GET['logout'] ) ) { ?>
                        <input type="hidden" class="redirect" name="redirect" value="<?php echo $redir; ?>">
                        <?php } ?>
                        <div class="status_text alert alert-info alert-dismissible fade show hide" role="alert">
                          <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <div></div>
                        </div>
                        <div class="main-login-form">
                            <div class="login-group">
                                <!--
                                <div class="form-group form-group-lg col-md-12">
                                    <label for="lg_username" class="sr-only">Username</label>
                                    <input type="text" class="form-control" id="lg_username" name="user" placeholder="username" value="<?php echo $name; ?>">
                                </div>
                                <div class="form-group form-group-lg col-md-12">
                                    <label for="lg_password" class="sr-only">Password</label>
                                    <input type="password" class="form-control" id="lg_password" name="pswd" placeholder="password">
                                </div>
                                <div class="col-md-12 middle text-center">
                                    <button id="login" name="login" class="btn btn-primary btn-xl submit_btn" type="submit">Login To account</button>
                                </div>
                                -->
                                <div class="text-center middle center">
                                    <a href="https://clever.com/oauth/district-picker?response_type=code&redirect_uri=https%3A%2F%2Fwww.mesaontime.com%2Fapp%2Findex.php&client_id=41f56eff7fe5d53a8096">
                                        <img src="../img/clever-login.png" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>



    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/jquery/jquery-ui.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="../vendor/jquery/jquery.easing.min.js"></script>
    <script src="../vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="../vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="../js/creative.js"></script>
    <script src="../js/login.js"></script>

    <!-- Remove Cookie -->
    <script type="text/javascript">
        document.cookie = "<?php echo $crd_snid; ?>=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
    </script>

</body>

</html>
