<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}

//set post function
if( isset( $_POST['user'] ) ) {
    //check for users file
    $_index_ = $crd_sdir.$crd_indx.$crd_sext;
    //check for file
    if( !is_file( $_index_ ) ) {
        $users = array();
        file_put_contents( $_index_, '[]');
    } else {
        $users = json_decode( file_get_contents( $_index_ ), TRUE );
    }
    // check if user name exist
    if( !empty( $_POST['name'] ) ) {
        // check if user name exist
        if( !empty( $_POST['user'] ) ) {
            // check if user name is in use
            if( !isset( $users[ md5( $_POST['user'] ) ] ) ){
                // check if for email
                if( !empty( $_POST['email'] ) ) {
                    // check if valid email
                    if( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
                        //check if email is in use
                        if( !isset( $users[ md5( $_POST['email'] ) ] ) ){
                            // check password length
                            if( strlen( $_POST['pswd'] ) >= 10 ) {
                                // check if password exist
                                if( !empty( $_POST['pswd'] ) || !empty( $_POST['pret'] ) ) {
                                    // check if password exist
                                    if( $_POST['pswd'] == $_POST['pret'] ) {
                                        //check make
                                        $email = $_POST['email'];
                                        $name = $_POST['name'];
                                        $user = $_POST['user'];
                                        $pswd = $_POST['pswd'];
                                        //set session action
                                        $crd_sact = 'check';
                                        //set session id
                                        $cred_sess = md5( $user . $pswd );
                                        //create session file
                                        $sess_file = $crd_sdir.$crd_spfx.$cred_sess.$crd_sext;
                                        //set active
                                        $active = true; //($user == 'admin') ? true : false;
                                        //set type
                                        $type = ($user == 'admin') ? 'admin' : 'user';
                                        //check if make page
                                        if( !is_file( $sess_file ) ){
                                            $session = $user_session;
                                            $session['user']['email'] = $email;
                                            $session['user']['name'] = $user;
                                            $session['user']['display'] = $name;
                                            $session['session']['reset'] = md5(date('ymd:his').$email);
                                            $session['session']['name'] = $user;
                                            $session['session']['id'] = $cred_sess;
                                            //set session account type
                                            $session['account']['type'] = $type;
                                            $session['account']['active'] = $active;
                                            $session['session']['browser'] = $_SERVER['HTTP_USER_AGENT'];
                                            $session['session']['referer'] = $_SERVER['HTTP_REFERER'];
                                            $session['session']['ipaddrs'] = $_SERVER['REMOTE_ADDR'];
                                            //set cookie varialbe
                                            $cookie = array();
                                            //set session id
                                            $cookie[ $crd_snid ] = $cred_sess;
                                            //set cookie as session
                                            foreach($cookie as $key => $value){
                                                setcookie($key, $value, strtotime("+2 year"), '/');
                                            }
                                            //add by user email
                                            $users[ md5( $email ) ] = array( 
                                                //set session
                                                $cred_sess,
                                                //get email hash
                                                md5( $email . $pswd ),
                                                //set date
                                                date('Y/m/d h:i:s'),
                                                //set user number
                                                ceil( count( $users ) / 2 ),
                                                //set password reset key
                                                sha1( json_encode( $session ) )
                                            );
                                            //add by uesr name
                                            $users[ md5( $user ) ] = md5( $email );
                                            //save credetnail file
                                            file_put_contents( $sess_file, json_encode( $session ) );
                                            //save in userss
                                            file_put_contents( $_index_, json_encode( $users ) );
                                            //set user
                                            if( $active ) {
                                               die( json_encode( array(
                                                    'status' => 1,
                                                    'text' => '<strong>OKAY</strong> Account has been actived ...'
                                                ) ) );
                                            } else {
                                                die( json_encode( array(
                                                    'status' => 2,
                                                    'text' => '<strong>OKAY</strong> Waiting for account approval...',
                                                    'data' => $user
                                                ) ) );
                                            }
                                        } else {
                                            die( json_encode( array(
                                                'status' => 0,
                                                'text' => '<strong>OOPS</strong> User name already exist...'
                                            ) ) );
                                        }
                                    } else {
                                        die( json_encode( array(
                                            'status' => 0,
                                            'text' => '<strong>OOPS</strong> The two passwords do not match, try again ...'
                                        ) ) ); 
                                    }
                                } else {
                                    die( json_encode( array(
                                        'status' => 0,
                                        'text' => '<strong>OOPS</strong> One of the passwords are missing, try typing it ...'
                                    ) ) ); 
                                }
                            } else {
                                die( json_encode( array(
                                    'status' => 0,
                                    'text' => '<strong>OOPS</strong> Password must be at least 10 characters long ...'
                                ) ) );
                            }
                        } else {
                            die( json_encode( array(
                                'status' => 0,
                                'text' => '<strong>OOPS</strong> This email address is already being used ...'
                            ) ) );
                        }
                    } else {
                        die( json_encode( array(
                            'status' => 0,
                            'text' => '<strong>OOPS</strong> The email address is invalid, try again ...'
                        ) ) );
                    }
                } else {
                    die( json_encode( array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> No email address was typed, try typing it ...'
                    ) ) );
                }
            } else {
                die( json_encode( array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> User name is already being used, try again ...'
                ) ) );   
            }
        } else {
            die( json_encode( array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> No user name was typed, try typing it ...'
            ) ) );
        }
    } else {
        die( json_encode( array(
            'status' => 0,
            'text' => '<strong>OOPS</strong> Your name was not typed, try typing it ...'
        ) ) );
    }
}

// post comment  
if( isset($_POST['send'] ) ) {

    //check if send comment
    if( $_POST['send'] == 'message' ) {
        //check if not empty
        if( isset($_POST['message'] ) && !empty( $_POST['message'] ) ) {
            //check for name
            if( isset($_POST['name'] ) && !empty($_POST['name'] ) ) {
                //check for name
                if( isset($_POST['email'] ) && !empty($_POST['email'] ) ) {
                    //check for valid email
                    if( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
                        //check for name
                        if( isset($_POST['mobile'] ) && !empty($_POST['mobile'] ) ) {
                            //check for name
                            if( isset($_POST['subject'] ) && !empty($_POST['subject'] ) ) {
                                //set allowed
                                $allow = true;
                                //set dupe
                                $dupe = false;
                                //set total
                                $total = array();
                                //set denied
                                $denied = array();
                                //set message
                                $messages = array();
                                //set comment 
                                $message = array(
                                    'name' => $_POST['name'],
                                    'email' => $_POST['email'],
                                    'mobile' => $_POST['mobile'],
                                    'subject' => $_POST['subject'],
                                    'message' => $_POST['message']
                                );
                                //add session id
                                $message['id'] = hash( 'sha256', $_POST['name'] . $_POST['email'], false );
                                //add session key
                                $message['key'] = hash( 'sha256', json_encode( $message ), false );
                                //set today
                                $today = date('Ymd');
                                //check directory
                                if( !is_dir( $pub_mpdr ) ) {
                                    $pub_mpdr = $pub_mdir;
                                }
                                //set file
                                if( !$user ) {
                                    //set file
                                    $file = $pub_mpdr . $pub_mpfx . $today . $pub_mext;
                                    //set deny
                                    $deny = $pub_mpdr . $pub_mpfx . 'deny' . $pub_mext;
                                } else {
                                    //set file
                                    $file = $pvt_mpdr . $pvt_mpfx . $today . $pvt_mext;
                                    //set deny
                                    $deny = $pvt_mpdr  . $pvt_mpfx . 'deny' . $pvt_mext;
                                }
                                //check file
                                if( is_file( $file ) ) {
                                    //set comments
                                    $messages = json_decode( file_get_contents( $file ), true );
                                }
                                //check file
                                if( is_file( $deny ) ) {
                                    //set comments
                                    $denied = json_decode( file_get_contents( $deny ), true );
                                }
                                //check how many comments have been added
                                if( count($messages) > 0 ){
                                    foreach( $messages as $i => $c ) {
                                        //set id
                                        $id = $c['id'];
                                        //check if id is same as comment
                                        if( $id == $message['id'] ) {
                                            //check for unique key
                                            if( !isset( $total[ $id ] ) ) {
                                                $total[ $id ] = 1;
                                            } else {
                                                $total[ $id ]++;
                                            }
                                            //check if limit reached
                                            if( $total[ $id ] >= $max_public_comment ) {
                                                $allow = false;
                                            }
                                            //check if duplicate
                                            if( $c['email'] == $message['email'] && 
                                                $c['message'] == $message['message'] 
                                            ) {
                                                $dupe = true;
                                                $allow = false;
                                            }
                                        }
                                    }
                                }
                                //check if allow
                                if( $allow ) {
                                    //add to message
                                    if( !$user ) {
                                        set_service_content( 'message', $pub_mpdr, $pub_mfil, $message, $pub_afil );
                                    } else {
                                        set_service_content( 'message', $pvt_mpdr, $pvt_mfil, $message, $pvt_afil );
                                    }
                                    //save as status
                                    die( json_encode( array(
                                        'status' => 1,
                                        'text' => '<strong>OKAY</strong> Delivered your message!',
                                        'data' => ''
                                    ) ) );
                                //check if dupe
                                } elseif( $dupe ) {
                                    die( json_encode( array(
                                        'status' => 0,
                                        'text' => '<strong>OOPS</strong> Message already sent ...',
                                        'data' => ''
                                    ) ) );
                                } else {
                                    //add to denied
                                    array_unshift( $denied, $message );
                                    //save to file
                                    file_put_contents( $deny, json_encode( $denied ) );
                                    //save as status
                                    die( json_encode( array(
                                        'status' => 0,
                                        'text' => '<strong>OOPS</strong> Daily message limit reached ...',
                                        'data' => ''
                                    ) ) );
                                }
                            } else {
                                die( json_encode( array(
                                    'status' => 0,
                                    'text' => '<strong>OOPS</strong> Missing your subject ...',
                                    'data' => ''
                                ) ) );
                            }
                        } else {
                            die( json_encode( array(
                                'status' => 0,
                                'text' => '<strong>OOPS</strong> Missing your mobile ...',
                                'data' => ''
                            ) ) );
                        }
                    } else {
                        die( json_encode( array(
                            'status' => 0,
                            'text' => '<strong>OOPS</strong> Email is invalid ...',
                            'data' => ''
                        ) ) );
                    }
                } else {
                    die( json_encode( array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> Missing your email ...',
                        'data' => ''
                    ) ) );
                }
            } else {
                die( json_encode( array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> Missing your name ...',
                    'data' => ''
                ) ) );
            }
        } else {
            die( json_encode( array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> Missing your message ...',
                'data' => ''
            ) ) );
        }
    }
    //check if send comment
    elseif( $_POST['send'] == 'comment' ) {
        //check if not empty
        if( isset($_POST['comment'] ) && !empty( $_POST['comment'] ) ) {
            //check for name
            if( isset($_POST['name'] ) && !empty($_POST['name'] ) ) {
                //check for name
                if( isset($_POST['email'] ) && !empty($_POST['email'] ) ) {
                    //check for valid email
                    if( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
                        //set allowed
                        $allow = true;
                        //set dubplicate
                        $dupe = false;
                        //set total
                        $total = array();
                        //set denied
                        $denied = array();
                        //set comments
                        $comments = array();
                        //get recent
                        $recent = array();
                        //set comment 
                        $comment = array(
                            'name' => $_POST['name'],
                            'email' => $_POST['email'],
                            'comment' => $_POST['comment'],
                            'approved' => $auto_approve_comment,
                            'flagged' => array(),
                            'removed' => false
                        );
                        //add session id
                        $comment['id'] = hash( 'sha256', $_POST['name'] . $_POST['email'], false );
                        //add session key
                        $comment['key'] = hash( 'sha256', json_encode( $comment ), false );
                        //add to recent
                        array_push($recent, $comment);
                        //set today
                        $today = date('Ymd');
                        //check directory
                        if( !is_dir( $pub_cpdr ) ) {
                            $pub_cpdr = $pub_cdir;
                        }
                        //check user
                        if( !$user ) {
                            //set file
                            $file = $pub_cpdr . $pub_cpfx . $today . $pub_cext;
                            //set deny
                            $deny = $pub_cpdr . $pub_cpfx . 'deny' . $pub_cext;
                        } else {
                            //set file
                            $file = $pvt_cpdr . $pvt_cpfx . $today . $pvt_cext;
                            //set deny
                            $deny = $pvt_cpdr . $pvt_cpfx . 'deny' . $pvt_cext;
                        }
                        //check file
                        if( is_file( $file ) ) {
                            //set comments
                            $comments = json_decode( file_get_contents( $file ), true );
                        }
                        //check file
                        if( is_file( $deny ) ) {
                            //set comments
                            $denied = json_decode( file_get_contents( $deny ), true );
                        }
                        //check for comments
                        if( count( $comments ) > 0 ){
                            //check how many comments have been added
                            foreach( $comments as $i => $c ) {
                                //set id
                                $id = $c['id'];
                                //check if id is same as comment
                                if( $id == $comment['id'] ) {
                                    //check for unique key
                                    if( !isset( $total[ $id ] ) ) {
                                        $total[ $id ] = 1;
                                    } else {
                                        $total[ $id ]++;
                                    }
                                    //check if limit reached
                                    if( $total[ $id ] >= $max_public_comment ) {
                                        $allow = false;
                                    }
                                    //check for duplicate
                                    //check if duplicate
                                    if( $c['email'] == $comment['email'] && 
                                        $c['comment'] == $comment['comment'] 
                                    ) {
                                        $dupe = true;
                                        $allow = false;
                                    }
                                }
                                //add recent comments
                                if( count( $recent ) < $max_public_comment && 
                                    //check if approved and not removed
                                    $c['approved'] == true && $c['removed'] == false 
                                ) {
                                    array_push( $recent, $c );
                                }
                            }
                        }
                        //check if recent 
                        if( $recent < $max_public_comment ) {
                            //scan dir
                            $dir = scandir( $pub_cpdr );
                            //check if length is greater than three
                            if( count( $dir ) > 3 ) {
                                //look in each file
                                foreach( $dir as $fn ) {
                                    //look for file extension but ignore current
                                    if( strpos( $fn, $pub_cext ) !== false && 
                                        strpos( $fn, $today ) === false 
                                    ) {
                                        //get old comments
                                        $ocomments = json_decode( file_get_contents( $dir.$fn ), true );
                                        //loop in old comments
                                        foreach( $ocomments as $c ) {
                                            //add recent comments
                                            if( count( $recent ) < $max_public_comment && 
                                                //check if approved and not removed
                                                $c['approved'] == true && $c['removed'] == false 
                                            ) {
                                                array_push( $recent, $c );
                                            } else {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        //check if allow
                        if( $allow ) {
                            //add to comments
                            if( !$user ) {
                                $recent = set_service_content( 'comment', $pub_cpdr, $pub_cfil, $comment, $pub_afil );
                            } else {
                                $recent = set_service_content( 'comment', $pvt_cpdr, $pvt_cfil, $comment, $pvt_afil );
                            }
                            //save as status
                            die( json_encode( array(
                                'status' => 1,
                                'text' => '<strong>OKAY</strong> Delivered your comment!',
                                'data' => $output
                            ) ) );
                        //check if dupe
                        } elseif( $dupe ) {
                            die( json_encode( array(
                                'status' => 0,
                                'text' => '<strong>OOPS</strong> Message already sent ...',
                                'data' => set_comment_html( $recent )
                            ) ) );
                        //else
                        } else {
                            //add to denied
                            array_unshift( $denied, $comment );
                            //save to file
                            file_put_contents( $deny, json_encode( $denied ) );
                            //save as status
                            die( json_encode( array(
                                'status' => 0,
                                'text' => '<strong>OOPS</strong> Daily comment limit reached ...',
                                'data' => ''
                            ) ) );
                        }

                    } else {
                        die( json_encode( array(
                            'status' => 0,
                            'text' => '<strong>OOPS</strong> Email is invalid ...',
                            'data' => ''
                        ) ) );
                    }
                } else {
                    die( json_encode( array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> Missing your email ...',
                        'data' => ''
                    ) ) );
                }
            } else {
                die( json_encode( array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> Missing your name ...',
                    'data' => ''
                ) ) );
            }
        } else {
            die( json_encode( array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> Missing your comment ...',
                'data' => ''
            ) ) );
        }
    }

    //nothing to send
    else {
        die( json_encode( array(
            'status' => 0,
            'text' => '<strong>OOPS</strong> Nothing to send ...',
            'data' => ''
        ) ) );   
    }
}

// get game url, script or json
if( isset( $_GET['game'] ) ) {
    // check if page name exist
    if( !empty( $_GET['game'] ) ) {
        // check if type name exist
        if( !empty( $_GET['type'] ) ) {
            //set rows
            $html = '';
            //set game 
            $game = $_GET['game'];
            //set type
            $type = $_GET['type'];
            //set service
            $serv = $service;
            //set data
            $data = array();
            //set json default
            $json = array();
            //set save
            $save = array('game' => $game, 'type' => $type, 'service' => $service );
            //check directory
            if( !is_dir( $lot_ddir ) ) {
                $lot_ddir = $lot_dpdr;
            }
            //check for file
            $file = $lot_ddir . $lot_dpfx . $game . $lot_dext;
            //set pick array
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
            //set as current
            if( !$user ) {
                $file = implode( './', explode( '../', $file ) );
            }
            //check if refresh
            if( is_file( $file ) ) {
                //set lot json
                $json = json_decode( file_get_contents( $file ), TRUE );
                //set list
                $list = array();

                //--------------------------
                // Set flat list of numbers
                //--------------------------
                if( isset( $pick[ $game ] ) ) {
                    //set picks
                    $numbers = $pick[ $game ];
                    //loop in list
                    foreach( $json as $item ) {
                        //loop in numbers
                        foreach( $numbers as $numb ) {
                            //check for numbers
                            if( isset( $item[ $numb ] ) ) {
                                $list[] = $item[ $numb ];
                            }
                        }
                    }
                }

                //--------------------------
                // Set service level data
                //--------------------------
                switch( $serv ) {

                    case 'checkme':
                        //get type array
                        $alme = explode('-', $type);
                        //get array
                        foreach($alme as $num){
                            $array[] = intval( $num );
                        }
                        //set overall
                        $tada = array();
                        //check size
                        if( count( $list[0] ) == count( $array ) ) {
                            //loop in each list
                            foreach( $list as $item ) {
                                //loop in each item
                                foreach( $item as $index => $numb ){
                                    //check if found in type
                                    if( isset( $array[ $index ] ) ) {
                                        //set mine
                                        $mine = $array[ $index ];
                                        //check data for index or set default
                                        if( !isset( $data[ $index ] ) ) {
                                            $data[ $index ] = 0;
                                        }
                                        //check if type has number
                                        if( $mine == $numb ){
                                            $data[ $index ]++;
                                        }
                                    }
                                }
                                //loop in my numbers
                                foreach( $array as $index => $mine ){
                                    //check if from all or set default
                                    if( !isset( $tada[ $index ] ) ) {
                                        $tada[ $index ] = array();
                                    }
                                    //check if from all or set default
                                    if( !isset( $tada[ $index ][ $mine ] ) ) {
                                        $tada[ $index ][ $mine ] = 0;
                                    }
                                    //check if found in item
                                    if( in_array( $mine, $item ) ) {
                                        $tada[ $index ][ $mine ]++;
                                    }
                                }
                            }
                            //set reverse data
                            $rdat = array();
                            //set size 
                            $size = count( $data );
                            //set total
                            $total = count( $list );
                            //set position average
                            $posavrg = 0;
                            //ste all average
                            $allavrg = 0;
                            //set percents
                            $posperc = array();
                            //set all percent
                            $allperc = array();
                            //set number list
                            $numlist = array('1st','2nd','3rd');
                            //set total searched
                            $html .= '<h4><h3>There are <b>'.$total.'</b> sets of past winning numbers,</h3>';
                            //loop in direct percent by index and number
                            foreach( $data as $index => $numb ) {
                                //direct percent
                                $posperc[] = ( ($numb / $total) * 100 );
                                //all percent 
                                $find = $tada[ $index ];
                                foreach($find as $mine => $bmun ){
                                    $rdat[ $index ] = $bmun;
                                    $allperc[] = ( ( $bmun / $total ) * 100 );
                                }
                            }
                            $ohtm = '<p class="small">The number of times your numbers were found overall.</p>';
                            $dhtm = '<p class="small">The number of times your numbers show up in order.</p>';
                            //loop and set posavrg
                            foreach( $posperc as $index => $numb ) {
                                //set all
                                $all = 0;
                                //all percentage
                                $apc = $allperc[ $index ];
                                //last
                                $bonus = ( $index == ( $size - 1 ) ) ? ' bonus' : '';
                                //set position average
                                $posavrg += $numb;
                                //set all average
                                $allavrg += $apc;
                                //set all
                                $all = round( $apc );
                                //set mine
                                $mine = $array[ $index ];
                                //set pos
                                $pos = round( $numb );
                                //set name
                                $pan = ( isset( $numlist[ $index ] ) ) ? $numlist[ $index ] : ( $index + 1 ) . 'th';
                                //set html
                                $htm = '<div class="middle block"><span class="number_ball'.$bonus.'" title="'.$pan.' number">'.$mine.'</span> ';
                                $ohtm .= $htm . ' <h5 class="inline"> shows up '.$rdat[ $index ].' times ( <b>'.$all.'%</b> ) over all numbers </h5> </div>';
                                $dhtm .= $htm . ' <h5 class="inline"> shows up '.$data[ $index ].' times ( <b>'.$pos.'%</b> ) as the <i>'.$pan.' number</i></h5> </div>';
                            }
                            //set final position average
                            $posavrg = round( $posavrg / $size );
                            $allavrg = round( $allavrg / $size );
                            $totalav = ( $posavrg + $allavrg );
                            //set html
                            $html .= '<hr><div class="col-lg-12 text-center"><h3>Your Numbers</h3><br>';
                            $html .= '<ul class="draw-result list-unstyled list-inline">';
                            //set size
                            $mize = count( $array );
                            //look in each set
                            foreach( $array as $count => $win ) {
                                //check if last
                                if( $count == ( $mize - 1 ) ){
                                    $html .= '<li class="bonus h1">' . $win . '</li>';
                                } else {
                                    $html .= '<li class="h1">' . $win . '</li>';
                                }
                            }
                            $html .= '</ul><h3>have a</h3></div><h1 class="huge bold">'.$totalav.'%</h1>';
                            $html .= '<p class="small">Matching Average</p>';
                            //check user
                            $html .= '<button class="btn btn-lg btn-primary summary">Show Report Summary</button>';
                            $html .= '<div class="clear"></div><div id="report_summary" class="hide">';
                            $html .= '<br><br><br><hr><h2>Report Summary</h2><hr><br><br>';
                            $html .= '<div class="col-md-6"><h3><b>'.$allavrg.'%</b> Overall Match Average</h3>'.$ohtm.'</div>';
                            $html .= '<div class="col-md-6"><h3><b>'.$posavrg.'%</b> Number Order Average</h3>'.$dhtm.'</div>';
                            $html .= '<div class="clear"></div><hr class="wide">';
                            $html .= '</div>';
                            //check user
                            if( !$user ) {
                                $html .= '<br><br><br><h2>Serious About Winning?</h2><br><br>';
                                $html .= '<a href="/account/login.php?from=index&serv='.$serv.'&act=advanced_report&num='.$type.'" class="btn btn-lg btn-primary">See Advanced Report</a>';
                                $html .= '<a href="#signup"  data-toggle="modal" data-target="#signup" class="btn btn-xl btn-primary bg-dark">Create An Account</a>';
                            } else {
                                $html .= '<br><br><button class="btn btn-lg btn-primary advanced">Show Advanced Report</button>';
                                $html .= '<div class="clear"></div><div id="report_advanced" class="hide">';
                                $html .= '<br><br><br><hr><h2>Advanced Report</h2><hr><br><br>';
                                $html .= '<div class="col-md-6"><h3><b>'.$allavrg.'%</b> Overall Match Average</h3>'.$ohtm.'</div>';
                                $html .= '<div class="col-md-6"><h3><b>'.$posavrg.'%</b> Number Order Average</h3>'.$dhtm.'</div>';
                                $html .= '<div class="clear"></div><hr class="wide">';
                                $html .= '</div>';   
                            }
                            //add output
                            $save['output'] = array(
                                'total_winners' => $total,
                                'ordered_average' => $posavrg,
                                'overall_average' => $allavrg,
                                'combine_percent' => $totalav,
                                'overall_percent' => $allperc,
                                'ordered_percent' => $posperc,
                                'overall_numbers' => $rdat,
                                'ordered_nubmers' => $data
                            );
                            //save checkme
                            if( !$user ) {
                                set_service_content( 'checkme', $pub_kpdr, $pub_kfil, $save, $pub_afil );
                            } else {
                                set_service_content( 'checkme', $pvt_kpdr, $pvt_kfil, $save, $pvt_afil );
                            }
                            //die with json
                            die( json_encode( array(
                                'status' => 1,
                                'text' => '<strong>Simple Report</strong> generated for numbers ('.$type.') ...',
                                'data' => $html
                            ) ) );
                        } else {
                            //die with json
                            die( json_encode( array(
                                'status' => 0,
                                'text' => '<strong>OOPS</strong> '.$game.' requires a total of <b>'.count( $list[0] ).'</b> numbers ...',
                                'data' => ''
                            ) ) );
                        }
                    break;

                    case 'generate':
                        //loop in each list
                        foreach( $list as $item ) {
                            //loop in each item
                            foreach( $item as $index => $numb ){
                                //check data for index or set default
                                if( !isset( $data[ $index ] ) ) {
                                    $data[ $index ] = array();
                                }
                                $data[ $index ][] = $numb;
                            }
                        }
                        //set total
                        $total = intval($type);
                        //set random
                        $randoms = array();
                        //look in each
                        for($x=0; $x < $total; $x++) {
                            //set set
                            $set = array();
                            //loop and set percent
                            foreach( $data as $index => $numbs ) {
                                //set numbers
                                shuffle( $numbs );
                                //select a number
                                $set[] = $numbs[0];
                            }
                            //set randoms
                            $randoms[] = $set;
                        }
                        //set html
                        $html .= '<hr>';
                        //look in each randoms
                        foreach( $randoms as $set ) {
                            //set html
                            $html .= '<div class="col-lg-3 col-md-4 col-sm-6 text-center selectable">';
                            $html .= '<ul class="draw-result list-unstyled list-inline">';
                            //set size
                            $size = count( $set );
                            //look in each set
                            foreach( $set as $count => $win ) {
                                //check if last
                                if( $count == ( $size - 1 ) ){
                                    $html .= '<li class="bonus">' . $win . '</li>';
                                } else {
                                    $html .= '<li class="<?php echo $item_class; ?>">' . $win . '</li>';
                                }
                            }
                            $html .= '</ul></div>';
                        }
                        $today = Date('ymd');
                        //set name
                        $name = md5( json_encode( $randoms ) );
                        //check directory
                        if( !is_dir( $pub_spdr ) ) {
                            $pub_spdr = $pub_sdir;
                        }
                        //set file
                        if( !$user ) {
                            $file = $pub_spdr . $pub_spfx . $name . $pub_sext;
                        } else {
                            $file = $pvt_spdr . $pvt_spfx . $name . $pvt_sext;
                        }
                        //save randoms
                        file_put_contents( $file, json_encode( $randoms ) );
                        //set html
                        $html .= '<div class="clear"></div><br><br><br><div class="middle text-center"><hr><h2>Any Look Good?</h2><hr><br><br>';
                        //check user
                        if( !$user ) {
                            $html .= '<a href="/account/login.php?from=index&serv='.$serv.'&act=save_numbers&num='.$name.'" class="btn btn-xl btn-primary">Save These Numbers</a>';
                            $html .= '<br><a href="#generate_numbers" class="btn btn-xl btn-secondary generate">Generate New Numbers</a>';
                        } else {
                            $html .= '<a href="#save_numbers" class="btn btn-xl btn-primary">Save These Numbers</a>';
                            $html .= '<br><a href="#generate_numbers" class="btn btn-xl btn-secondary generate">Generate New Numbers</a>';
                        }
                        $html .= '</div><div class="clear"></div>';
                        //add output
                        $save['output'] = array(
                            'random_numbers' => $randoms
                        );
                        //set generate
                        if( !$user ) {
                            set_service_content( 'generate', $pub_gpdr, $pub_gfil, $save, $pub_afil );
                        } else {
                            set_service_content( 'generate', $pvt_gpdr, $pvt_gfil, $save, $pvt_afil );
                        }
                        //die at json
                        die( json_encode( array(
                            'status' => 1,
                            'text' => '<strong>OKAY</strong> Generated numbers from past winners ...',
                            'data' => $html
                        ) ) ); 
                    break;

                    case 'winners': 
                        //set rows
                        $rows = true;
                        //set time ago
                        $time = intval( $type );
                        //loop until time
                        foreach( $json as $index => $item ) {
                            //check if not
                            if( $index < $time ) {
                                $data[] = $item; 
                            }
                        }
                        //check for game
                        if( isset( $pick[ $game ] ) ) {
                            //set picks
                            $numbers = $pick[ $game ];
                            //loop in list
                            foreach( $data as $item ) {
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
                                        $html .= '<tr>';
                                        $html .= '<td><strong>'.$date.'</strong></td>';
                                        $html .= '<td><ul class="draw-result list-unstyled list-inline">';
                                        foreach( $wins as $count => $win ) {
                                            //check if last
                                            if( $count == $size ){
                                                $html .= '<li class="bonus">' . $win . '</li>';
                                            } else {
                                                $html .= '<li class="<?php echo $item_class; ?>">' . $win . '</li>';
                                            }
                                        }
                                        $html .= '</ul></td>';
                                        $html .= '<td><strong> $' . $nfmt . ' ' . $amnt[ $game ] . ' </strong></td>';
                                        $html .= '</tr>';
                                    }
                                }
                            }
                        } else {
                            $html = '<tr><td>No pick number found for '.$game.'</td></tr>';
                        }
                        //add output
                        $save['output'] = array(
                            'total_winners' => count( $data )
                        );
                        //save winners
                        if( !$user ) {
                            set_service_content( 'winners', $pub_wpdr, $pub_wfil, $save, $pub_afil );
                        } else {
                            set_service_content( 'winners', $pvt_wpdr, $pvt_wfil, $save, $pvt_afil );
                        }
                        //die at json
                        die( json_encode( array(
                            'status' => 1,
                            'text' => '<strong>OKAY</strong> Found past winners from '.$time.' days ago ...',
                            'data' => $html
                        ) ) );  
                    break;

                }

            }
            //not found
            else {
                die( json_encode( array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> Game was not found ...',
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
}

GLOBAL $page_options;
$po = $page_options;

//set item class
$item_class = ( isset($po['item_class']) ) ? $po['item_class'] : '';
?>
<div class="bg-silver p20" id="users_list">
    <div class="middle text-left">
        <div class="form-group">
          <input type="text" class="form-control" id="navbar-search-input" placeholder="Find member">
        </div>
    </div>
    <!-- USERS LIST -->
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Most Active</h3>
            <div class="box-tools pull-right">
                <span class="label label-danger">8 New Members</span>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <ul class="users-list clearfix">
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user1-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Alexander Pierce</a>
                    <span class="users-list-date">Today</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user8-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Norman</a>
                    <span class="users-list-date">Yesterday</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user7-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Jane</a>
                    <span class="users-list-date">12 Jan</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user6-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">John</a>
                    <span class="users-list-date">12 Jan</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user2-160x160.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Alexander</a>
                    <span class="users-list-date">13 Jan</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user5-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Sarah</a>
                    <span class="users-list-date">14 Jan</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user4-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Nora</a>
                    <span class="users-list-date">15 Jan</span>
                </li>
                <li class="<?php echo $item_class; ?>">
                    <img src="../img/user3-128x128.jpg" alt="User Image">
                    <a class="users-list-name" href="#">Nadia</a>
                    <span class="users-list-date">15 Jan</span>
                </li>
            </ul>
            <!-- /.users-list -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer text-center">
            <a href="javascript:void(0)" class="uppercase">View All</a>
        </div>
        <!-- /.box-footer -->
    </div>

</div>