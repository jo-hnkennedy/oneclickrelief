<?php

include_once 'include/config.php';
include_once 'include/session.php';
include_once 'include/function.php';

//get term
if( isset( $_GET['term'] ) ) {
    //set table
    $table = 'all_students';
    //set term
    $term = $_GET['term'];
    //set sql statement
    $find = (is_numeric($term)) ? 'number' : 'name';
    //get campus id
    $campusId = '12345';
    //set statement
    // $sql = 'SELECT student_name, student_number FROM all_students WHERE hs_number = "'.$campusId.'" AND student_'.$find.' LIKE "%'.$term.'%" LIMIT 10;';
    $sql = 'SELECT student_name, student_number FROM all_students WHERE student_'.$find.' LIKE "%'.$term.'%" LIMIT 10;';
    //die( $sql );
    $results = run_db_query( 'SearchStudent', $sql );
    //check query
    if ($results) {
        //set output
        $output = array();
        //set each
        foreach( $results as $data ) {
            $output[] = $data['student_number'] . ' - ' . $data['student_name'];
        }
        //save as status
        die( json_encode( $output ) );
    } else {
        //save as status
        die('[]');
    }
} else {
    die('[]');
}


?>