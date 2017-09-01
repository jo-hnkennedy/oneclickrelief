<?php

include_once '../include/config.php';

//remove cookie
unset( $_COOKIE[ $crd_snid ] );
//set session id cookie
$res = setcookie($crd_snid, "", time() - 3600);

//set defaults
$user = false;
$account = false;

//check for redirect
if( isset( $_GET['redir'] ) ) {
    //set redirect
    $redir = $_GET['redir'];
    //check for file
    if( strpos($redir, '://') === false ) {
        //check for string
        $name = 'index';
        //check file name
        if( is_file( $redir . '.php' ) ){
            //set name
            $name = $redir;
        }
        //set header
        header('Location: login.php?logout=1&redir=' . $name . '.php');
    } else {
        header('Location: ' . $redir);
    }
} else {
    //includ login page
    header('Location: login.php?logout=1');
}

?>