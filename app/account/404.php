<?php

include_once '../include/config.php';
include_once '../include/session.php';
include_once '../include/function.php';

// get game url, script or json
if( isset( $_GET['404'] ) ) {
    //check user
    if( $user ) {
        //check if admin
        if( $account['type'] == 'admin' ) {
            // check if page name exist
            if( !empty( $_GET['404'] ) ) {
                // check if type name exist
                if( !empty( $_GET['type'] ) ) {
                    //set game and type
                    $data = $_GET['404'];
                    $type = $_GET['type'];
                    //set json default
                    $lot_json = array();
                    //set script default
                    $lot_script = array();
                    //check for file
                    $lot_data = $lot_ddir.$lot_dpfx.$data.$lot_dext;
                    //set lot script
                    $lot_fetch = $lot_fdir.$lot_fpfx.$data.$lot_fext;
                    //check if refresh
                    if( is_file( $lot_data ) ) {
                        $lot_json = json_decode( file_get_contents( $lot_data ), TRUE );
                    }
                    //check if fetch
                    if( is_file( $lot_fetch ) ) {
                        $lot_script = file_get_contents( $lot_fetch );
                    }
                    //set ssl
                    $lot_ssl = 1;
                    //set encode
                    $lot_enc = 0; 
                    //set lot url
                    $lot_url = '';
                    //set script
                    $lot_scr = '';
                    //set method
                    $lot_mth = 'GET';
                    //set format
                    $lot_fmt = 'html';

                    //set type
                    if( $type == 'url' ) {
                        //stop here
                        die( json_encode( array(
                            'status' => 1,
                            'text' => '<strong>OKAY</strong> Found request URL for '.$data.' ...',
                            'data' => array()
                        ) ) ); 
                    } 
                    //check if json
                    elseif( strlen( $lot_script ) > 0 && $type == 'script' ) {
                        //stop here
                        die( json_encode( array(
                            'status' => 1,
                            'text' => '<strong>OKAY</strong> Found request script for '.$data.' ...',
                            'data' => $lot_script
                        ) ) );
                    } 
                    //check if json
                    elseif( count( $lot_json ) > 0 && $type == 'data' ) {
                        //stop here
                        die( json_encode( array(
                            'status' => 1,
                            'text' => '<strong>OKAY</strong> Found request data for '.$data.' ...',
                            'data' => $lot_json
                        ) ) );
                    } 
                    //not found
                    else {
                        die( json_encode( array(
                            'status' => 2,
                            'text' => '<strong>DANG</strong> Data not found for '.$data.' ...',
                            'data' => ''
                        ) ) );    
                    }
                } else {
                    die( json_encode( array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> Missing request type ...',
                        'data' => ''
                    ) ) ); 
                }
            } else {
                die( json_encode( array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> Missing game name ...',
                    'data' => ''
                ) ) );
            }
        } else {
            die( json_encode( array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> Invalid credentails ...',
                'data' => ''
            ) ) ); 
        }
    } else {
        die( json_encode( array(
            'status' => 0,
            'text' => '<strong>OOPS</strong> Request not permitted ...',
            'data' => ''
        ) ) );    
    }
} 
// save game past wins
elseif( isset( $_POST['404'] ) ) {
    //check user
    if( $user ) {
        //check if admin
        if( $account['type'] == 'admin' ) {
            // check if page name exist
            if( !empty( $_POST['404'] ) ) {
                // check if type name exist
                if( !empty( $_POST['list'] ) ) {
                    //set game and type
                    $data = $_POST['404'];
                    $list = $_POST['list'];
                    $pick = array(
                        'PowerBall' => array( 'pickSix' ),
                        'MegaMillions' => array( 'pickSix' ),
                        'LottoTexas' => array( 'pickSix' ),
                        'TexasTwoStep' => array( 'pickFive' ),
                        'TexasTripleChance' => array( 'pickTen' ),
                        'AllOrNothing' => array( 'pickTwelve' ),
                        'PickThree' => array( 'pickThreeMorning', 'pickThreeDay', 'pickThreeEvening', 'pickThreeNight' ),
                        'DailyFour' => array( 'pickFourMorning', 'pickFourDay', 'pickFourEvening', 'pickFourNight' )
                    );
                    //amount
                    $amnt = array(
                        'PowerBall' => 'Million',
                        'MegaMillions' => 'Million',
                        'LottoTexas' => 'Million',
                        'TexasTwoStep' => '',
                        'TexasTripleChance' => '',
                        'AllOrNothing' => '',
                        'PickThree' => '',
                        'DailyFour' => ''
                    );
                    //set output
                    $rows = '';
                    //set json default
                    $lot_json = json_decode( $list, true );
                    //check for file
                    $lot_data = $lot_ddir.$lot_dpfx.$data.$lot_dext;
                    //save file
                    file_put_contents( $lot_data, json_encode( $lot_json ) );
                    //check for pick
                    if( isset( $pick[ $data ] ) ) {
                        //set picks
                        $numbers = $pick[ $data ];
                        //loop in list
                        foreach( $lot_json as $item ) {
                            //set intraday
                            $intra = false;
                            //check for pick amount
                            if( !isset( $item['pickAmount'] ) ) {
                                $item['pickAmount'] = 500;
                                $intra = true;
                            }
                            //loop in numbers
                            foreach( $numbers as $numb ) {
                                //check for intra day
                                if( $intra ) {
                                    $timeof = implode('', explode( 'Four', $numb ) );
                                    $timeof = implode('', explode( 'Three', $timeof ) );
                                    $timeof = implode('', explode( 'pick', $timeof ) );
                                    $date = $item['datePretty'] . ' - ' . $timeof;
                                } else {
                                    $date = $item['datePretty'];
                                }
                                //check for number
                                if( isset( $item[ $numb ] ) ){
                                    //set wins
                                    $wins = $item[ $numb ];
                                    $size = count( $wins )-1;
                                    $nfmt = number_format( $item['pickAmount'], 0, ".", "," );
                                    //set rows
                                    $rows .= '<tr>';
                                    $rows .= '<td><strong>'.$date.'</strong></td>';
                                    $rows .= '<td><ul class="draw-result list-unstyled list-inline">';
                                    foreach( $wins as $count => $win ) {
                                        //check if last
                                        if( $count == $size ){
                                            $rows .= '<li class="bonus">' . $win . '</li>';
                                        } else {
                                            $rows .= '<li>' . $win . '</li>';
                                        }
                                    }
                                    $rows .= '</ul></td>';
                                    $rows .= '<td><strong> $' . $nfmt . ' ' . $amnt[ $data ] . ' </strong></td>';
                                    $rows .= '</tr>';
                                }
                            }
                        }
                    } else {
                        $rows = '<tr><td>No pick number found for '.$data.'</td></tr>';
                    }
                    //set type
                    if( is_file( $lot_data ) ) {
                        //stop here
                        die( json_encode( array(
                            'status' => 1,
                            'text' => '<strong>OKAY</strong> Saved data for '.$data.' ...',
                            'data' => $rows
                        ) ) ); 
                    } 
                    //not found
                    else {
                        die( json_encode( array(
                            'status' => 0,
                            'text' => '<strong>OOPS</strong> Could not save data for '.$data.' ...',
                            'data' => ''
                        ) ) );    
                    }
                } else {
                    die( json_encode( array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> Missing post data ...',
                        'data' => ''
                    ) ) ); 
                }
            } else {
                die( json_encode( array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> Missing game name ...',
                    'data' => ''
                ) ) );
            }
        } else {
            die( json_encode( array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> Invalid credentails ...',
                'data' => ''
            ) ) ); 
        }
    } else {
        die( json_encode( array(
            'status' => 0,
            'text' => '<strong>OOPS</strong> Request not permitted ...',
            'data' => ''
        ) ) );    
    }
} else {
    //check user
    if( !$user ) {
        header('Location: login.php');
    }
    //check user
    if( $account['type'] != 'admin' ) {
        header('Location: home.php');
    }
}


?><!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>404</title>

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
        <link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/font-awesome.min.css" rel="stylesheet">
<link href="../css/ui-style.css" rel="stylesheet">

    </head>

    <body id="page-top" class="theme-{COLOR}">

        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="../"> <i clss="fa fa-chevron-left"></i> Mesa onTime</a> 
                    <a class="navbar-brand page-scroll text-black" href="#page-top">| 404</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="page-scroll" href="">Not Found</a></li>
                        <!--<li><a class="page-scroll" href=""></a></li>-->
                        <!--<li><a class="page-scroll" href=""></a></li>-->
                        <!--<li><a class="page-scroll" href=""></a></li>-->
                        <!--<li><a class="page-scroll" href=""></a></li>-->
                        <!--<li><a class="page-scroll" href=""></a></li>-->
                        <!--<li><a class="page-scroll" href=""></a></li>-->
                        <ul class="nav navbar-nav navbar-right">
                            <?php echo setMenu( 'account',  $top_menu, '404', $account, '404' ); ?>
                        </ul>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>

        <header class="long bg-silver">
            <div class="header-content">
                <div class="header-content-inner">
                    <h1 id="homeHeading">404</h1>
                    <hr>
                    <a class="btn btn-default btn-xl page-scroll" href="">Not Found</a>
                    <!--<a class="btn btn-default btn-xl page-scroll" href=""></a>-->
                    <!--<a class="btn btn-default btn-xl page-scroll" href=""></a>-->
                    <!--<a class="btn btn-default btn-xl page-scroll" href=""></a>-->
                    <!--<a class="btn btn-default btn-xl page-scroll" href=""></a>-->
                    <!--<a class="btn btn-default btn-xl page-scroll" href=""></a>-->
                    <!--<a class="btn btn-default btn-xl page-scroll" href=""></a>-->
                </div>
            </div>
        </header>

        
        <section class="bg-primary" id="section_1">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Not Found</h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-left middle small">
                        <div class="jumbotron"> <h1>Hello, world!</h1> <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p> <p><a href="#" class="btn btn-primary btn-lg" role="button">Learn more</a></p> </div><br><br><div class="ui-2"><div class="container"><div class="row component-main" id="parent-item"><div class="col-md-4 hover" id="item-0" style="position: relative;"><div class="ui-item"><div class="ui-head"><h3>Basic Plan</h3><a href="#" class="btn btn-red">$<strong>19.00</strong></a></div><div class="clearfix"></div><div class="ui-para br-red"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere amet, consec erat a ante Lorem sit amet.</p></div><div class="ui-list"><ul class="list-unstyled"><li><i class="fa fa-check green"></i><strong> &nbsp; &nbsp;Storage - 10GB</strong></li><li><i class="fa fa-check green"></i> &nbsp; &nbsp;Bandwidth - 1GB</li><li><i class="fa fa-close red"></i> &nbsp; &nbsp;Email-Id - 5</li><li><i class="fa fa-check green"></i> &nbsp; &nbsp;Sql-Database - 5</li><li><i class="fa fa-close red"></i> &nbsp; &nbsp;No Support</li></ul></div></div></div>
                    </div>
                </div>
            </div>
        </section>
        

        <!--
        <section class="bg-white" id="section_2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading"></h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-left middle small">
                        
                    </div>
                </div>
            </div>
        </section>
        -->


        <!--
        <section class="bg-silver" id="section_3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading"></h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container text-center">
                <div class="call-to-action">
                    <div class="col-lg-12 text-left middle small">
                        
                    </div>
                </div>
            </div>
        </section>
        -->

        <!--
        <section class="bg-white" id="section_4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading"></h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-left middle small">
                        
                    </div>
                </div>
            </div>
        </section>
        -->


        <!--
        <section class="bg-blue" id="section_5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading"></h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-left middle small">
                        
                    </div>
                </div>
            </div>
        </section>
        -->


        <!--
        <section class="bg-white" id="section_6">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading"></h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container text-center">
                <div class="call-to-action">
                    <div class="col-lg-12 text-left middle small">
                        
                    </div>
                </div>
            </div>
        </section>
        -->

        <!--
        <section class="bg-silver" id="section_7">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading"></h2>
                        <hr class="primary">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-left middle small">
                        
                    </div>
                </div>
            </div>
        </section>
        -->


        <!-- jQuery -->
        <script src="../vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="../vendor/jquery/jquery.easing.min.js"></script>
        <script src="../vendor/scrollreveal/scrollreveal.min.js"></script>
        <script src="../vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

        <!-- Theme JavaScript -->
        <script src="../js/creative.min.js"></script>
        <script src="../js/index.js"></script>

        <script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/placeholder.js"></script>
<script src="../js/respond.min.js"></script>
<script src="../js/html5shiv.js"></script>

    </body>

</html>
