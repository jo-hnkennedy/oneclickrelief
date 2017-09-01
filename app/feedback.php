<?php

require_once 'include/config.php';
require_once 'include/function.php';
require_once 'include/session.php';

// post comment  
if( isset($_POST['send'] ) ) {
    //check if send comment
    if( $_POST['send'] == 'feedback' ) {
        //check if not empty
        if( isset($_POST['message'] ) && !empty( $_POST['message'] ) ) {
            //check for name
            if( isset($_POST['name'] ) && !empty($_POST['name'] ) ) {
                //check for name
                if( isset($_POST['email'] ) && !empty($_POST['email'] ) ) {
                    //check for valid email
                    if( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
                        //set comment 
                        $message = array(
                            'name'    => $_POST['name'],
                            'email'   => $_POST['email'],
                            'message' => $_POST['message']
                        );
                        //set table
                        $table = 'feedback';
                        //save to database 
                        $keys = array_keys( $message );
                        $vals = array_values( $message );
                        //set sql statement
                        $sql = "INSERT INTO feedback (".implode( ",", $keys ).") VALUES ('".implode( "','", $vals )."');";
                        //die( $sql );
                        $insertRows = run_db_query($table, $sql);
                        //check query
                        if ($insertRows) {
                            //save as status
                            die( json_encode( array(
                                'status' => 1,
                                'text' => '<strong>OKAY</strong> Delivered your message!',
                                'data' => ''
                            ) ) );
                        } else {
                            //save as status
                            die( json_encode( array(
                                'status' => 1,
                                'text' => '<strong>Oops</strong> Could not delivered your message!',
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
}