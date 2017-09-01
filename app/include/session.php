<?php

include_once 'config.php';
include_once 'function.php';
//set directory pairs
$dir_pairs = array(
    array( $css_ddir, $css_dpdr, 'css', 'd' ),
    array( $scr_ddir, $scr_dpdr, 'scr', 'd' ),
    array( $res_ddir, $res_dpdr, 'res', 'd' ),
    array( $crd_sdir, $crd_spdr, 'crd', 's' )
);
//look in each pairs directory
foreach( $dir_pairs as $pair ) {
    //check if no directory
    if( !is_dir( $pair[0] ) ) {
        ${ '$' . $pair[2] . '_' . $pair[3] . 'dir' } = $pair[1];
    } 
    if ( !is_dir( $pair[1] ) ) {
        ${ '$' . $pair[2] . '_' . $pair[3] . 'pdr' } = $pair[0];
    }
}



//check for credentail data
if( !empty($crd_snid) ) {
    //check for cookie in session id
    if( isset($_COOKIE[$crd_snid]) && !empty($_COOKIE[$crd_snid])  ) {
        //set credential name
        $cred_sess = $_COOKIE[$crd_snid];
        //set persist
        $crd_sact = 'check';
    }
    //check if resume or login
    if( $crd_sact == 'check' ) {

        //check if not empty
        if( !empty($cred_sess) ){
            //include credential file
            //set query
            $sql = 'SELECT * FROM sessions WHERE sessionId = "'.$cred_sess.'" LIMIT 1;';
            //die( $sql );
            $sess_data = run_db_query('sessions', $sql);
            //check for session data
            if( count( $sess_data ) > 0 && isset( $sess_data[0]['data']) ){
                //json decode session data
                $sdata = json_decode( $sess_data[0]['data'], true );
                //check for user id
                $user = $sdata['user'];
                //account
                $account = $sdata['account'];
                //set session
                $session = $sdata;
                //check if account is active
                if( !$account['active'] ) {
                    $user = false;
                } else {
                    //get campus id 
                    $campusId = get_campus_id( $user );
                }
            }
        }
    }
}

?>