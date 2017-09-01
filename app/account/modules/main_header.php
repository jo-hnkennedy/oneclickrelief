<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}


GLOBAL $page_excerpt;
GLOBAL $page_title;
GLOBAL $page_name;
GLOBAL $css_dpdr;

//set css file
$css_file = $css_dpdr . $page_name . '.css';

GLOBAL $page_options;
$po = $page_options;

//check for print
$print = ( isset( $_GET['print'] ) ) ? true : false;

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="<?php echo $page_excerpt; ?>">
    <meta name="author" content="Mesa onTime">
    <title>Mesa onTime - <?php echo $page_title; ?></title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <!-- Font Awesome Animated -->
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome-animation.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../vendor/ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../css/AdminLTE.min.css">
    <!-- All Skins -->
    <link rel="stylesheet" href="../css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../vendor/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="../vendor/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="../vendor/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="../vendor/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../vendor/daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="../vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="../vendor/magnific-popup/magnific-popup.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="../vendor/datatables/pack/datatables.min.css">
   <!-- Animate CSS -->
    <link rel="stylesheet" href="../css/animate.min.css">
    <!-- UI Styles -->
    <link rel="stylesheet" href="../css/ui-style.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="../css/creative.css">
    <!-- Wysiwyg -->
    <link rel="stylesheet" href="../css/wysiwyg.css">
    <!-- Account -->
    <link rel="stylesheet" href="../css/account.css">
    <?php if(  is_file( $css_file ) ) { ?>
    <link href="<?php echo $css_file; ?>" rel="stylesheet">
    <?php } ?>
    <?php if( $print ) { ?>
    <link href="../css/print.css" rel="stylesheet">
    <?php } ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>