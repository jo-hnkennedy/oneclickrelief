<?php

session_start();


error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

//set api version
$version = 2; //1 or 2 
//set cookie id
$cookieID = 'OAUTH2MSCVR';
//set session id
$sessionID = 'oauth2state';



//--------------------
//  Composer Autoload
//--------------------
//include autoload file
$dir = dirname(__FILE__).'/';
$diru = dirname(__FILE__, 2 ).'/';
$autoload = $dir.'vendor/autoload.php';
if( is_file( $autoload ) ) {
    include $autoload; 
}



//------------------
//  Check provider
//------------------
$provider = new Schoolrunner\OAuth2\Client\Provider\Clever([
    'clientId'     => '41f56eff7fe5d53a8096',
    'clientSecret' => '692914f2e8108d31bfe650414ea79c82f288a36b',
    'redirectUri'  => 'https://www.mesaontime.com/app/index.php'
]);

//set code 
$code = ( isset($_GET['code']) ) ? $_GET['code'] : false;
//force code
//$code = '45f5c0cb6a8187ce3454db3216e37e3f1535a3ca';

//------------------
//  Check for code
//------------------
if ( !$code ) {
    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    //get current date
    $curState = $provider->getState();
    //set cookie varialbe
    $cookie = array();
    //set session id
    $cookie[ $cookieID ] = $curState;
    //set cookie as session
    foreach($cookie as $key => $value){
        setcookie($key, $value, strtotime("+2 year"), '/');
    }
    //set session
    $_SESSION[ $sessionID ] = $curState;
    //redirect to auth page
    header('Location: '.$authUrl);
    exit;
}

/*
//------------------------------------------------------------------------------
// Check given state against previously stored one to mitigate CSRF attack
//------------------------------------------------------------------------------
elseif ( 
    ( !isset( $_GET['state'] ) || empty( $_GET['state'] ) ) || 
    ( isset( $_SESSION[ $sessionID ] ) && $_GET['state'] !== $_SESSION[ $sessionID ] ) || 
    ( isset( $_COOKIE[ $cookieID ] ) && $_GET['state'] !== $_COOKIE[ $cookieID ] )
) {
    //remove session if exists
    if( isset( $_SESSION[ $sessionID ] ) ) {
        unset( $_SESSION[ $sessionID ] );
    }

    //remove cookie if exists
    if( isset( $_COOKIE[ $cookieID ] ) ) {
        //remove cookie
        unset( $_COOKIE[ $cookieID ] );
        //set session id cookie
        setcookie($cookieID, "", time() - 3600);
    }
    $error = 'Invalid state';
} 
*/


//-----------------------
//  Get Token & Access
//-----------------------
else {

    include_once $diru.'include/config.php';
    include_once $diru.'include/session.php';
    include_once $diru.'include/function.php';

    //https://github.com/Clever/clever-php/tree/v0.5.0
    $base = 'https://api.clever.com/v1.2/';
    $path = array(
        //get districts path
        'districts'             => 'districts',
        'districtsById'         => 'districts/{id}',
        'districtsAdmins'       => 'districts/{id}/admins',
        'districtsSchools'      => 'districts/{id}/schools',
        'districtsSections'     => 'districts/{id}/sections',
        'districtsStatus'       => 'districts/{id}/status',
        'districtsStudents'     => 'districts/{id}/students',
        'districtsTeachers'     => 'districts/{id}/teachers',
        //set district admins path
        'districtAdmins'        => 'district_admins',
        'districtAdminsById'    => 'district_admins/{id}',
        //set school admins path
        'schoolAdmins'          => 'school_admins',
        'schoolAdminsById'      => 'school_admins/{id}',
        'schoolAdminsEvents'    => 'school_admins/{id}/events',
        'schoolAdminsSchools'   => 'school_admins/{id}/schools',
        //get schools path
        'schools'               => 'schools',
        'schoolsById'           => 'schools/{id}',
        'schoolsEvents'         => 'schools/{id}/events',
        'schoolsDistrict'       => 'schools/{id}/district',
        'schoolsSections'       => 'schools/{id}/sections',
        'schoolsStudents'       => 'schools/{id}/students',
        'schoolsTeachers'       => 'schools/{id}/teachers',
        //get sections path
        'sections'              => 'sections',
        'sectionsById'          => 'sections/{id}',
        'sectionsEvents'        => 'sections/{id}/events',
        'sectionsDistrict'      => 'sections/{id}/district',
        'sectionsSchool'        => 'sections/{id}/school',
        'sectionsStudents'      => 'sections/{id}/students',
        'sectionsTeacher'       => 'sections/{id}/teacher',
        'sectionsTeachers'      => 'sections/{id}/teachers',
        //get students path
        'students'              => 'students',
        'studentsById'          => 'students/{id}',
        'studentsEvents'        => 'students/{id}/events',
        'studentsContacts'      => 'students/{id}/contacts',
        'studentsDistrict'      => 'students/{id}/district',
        'studentsSchool'        => 'students/{id}/school',
        'studentsSections'      => 'students/{id}/sections',
        'studentsTeachers'      => 'students/{id}/teachers',
        //get teachers path
        'teachers'              => 'teachers',
        'teachersById'          => 'teachers/{id}',
        'teachersEvents'        => 'teachers/{id}/events',
        'teachersDistrict'      => 'teachers/{id}/district',
        'teachersGradeLevels'   => 'teachers/{id}/grade_levels',
        'teachersSchool'        => 'teachers/{id}/school',
        'teachersSections'      => 'teachers/{id}/sections',
        'teachersStudents'      => 'teachers/{id}/students',
        //get contacts path
        'contacts'              => 'contacts',
        'contactsById'          => 'contacts/{id}',
        'contactsDistrict'      => 'contacts/{id}/district',
        'contactsStudent'       => 'contacts/{id}/student',
        //get events path
        'events'                => 'events',
        'eventsById'            => 'events/{id}',
    );

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken( 'authorization_code', [ 'code' => $_GET['code'] ] );
        //get access token value
        $tokenValue = $accessToken->getToken();
        //get resource owner
        $resourceOwner = $provider->getResourceOwner( $accessToken );
        //get resource data
        $resourceData = $resourceOwner->toArray();
        //get user id
        $user_id = $resourceData['id'];
        //set user status
        $user_status = 'active';
        //get user type
        $user_type = $resourceData['type'];
        //get user district
        $user_district = $resourceData['district'];
        //get user data url
        $getUserDataURL = $base.implode( $user_id, explode( '{id}', $path[ $user_type . 'sById' ] ) );
        //get user data request
        $userDataRequest = $provider->getAuthenticatedRequest( 'GET', $getUserDataURL, $accessToken );
        //get user data 
        $userData = $provider->getParsedResponse( $userDataRequest );
        //get sis id
        $user_sid = $userData['data']['id'];
        //get user email
        $user_email = $userData['data']['email'];
        //get first name
        $first_name = $userData['data']['name']['first'];
        //get first name
        $middle_name = $userData['data']['name']['middle'];
        //get first name
        $last_name = $userData['data']['name']['last'];
        //get family name
        $family_name = trim( $middle_name.' '.$last_name );
        //get user name
        $user_name = clean( trim( $first_name.' '.$family_name ) );
        //set password
        $user_paswd = $user_name.$user_sid;
        //set post array
        $post_user = array(
            'role'          => $user_type,
            'email'         => $user_email,
            'status'        => $user_status,
            'userId'        => $user_id,
            'sourcedId'     => $user_sid,
            'username'      => $user_name,
            'identifier'    => $user_type,
            'givenName'     => $first_name,
            'familyName'    => $family_name,
            'orgSourcedIds' => $user_district,
            'sms'           => '',
            'phone'         => '',
            'agents'        => ''
        );
        //set user login
        $login_user = array(
            'user' => $user_email,
            'pswd' => $user_paswd
        );

        //create a user
        $create_user = create_user( $post_user );

        //login the user
        $login_user = login_user( $login_user );

        //check if user is logged in
        if( $login_user['status'] == 1 ) {
            $user = $login_user['data'];
        } else {
            $error = $login_user['text'];
        }

        /*

        //----------------------
        // Get other user data
        //----------------------
        //get user data url
        $getUserDataURL = $base.implode( $user_id, explode( '{id}', $path[ $user_type . 'sById' ] ) );
        //get user data request
        $userDataRequest = $provider->getAuthenticatedRequest( 'GET', $getUserDataURL, $accessToken );
        //get user data 
        $userData = $provider->getParsedResponse( $userDataRequest );

        //----------------------
        // Use Clever Library
        //----------------------
        $cleverDir = $dir . "clever-php/lib/Clever.php";
        //check for clever directory
        if( is_dir( $cleverDir ) ) {
            //set clever token
            Clever::setToken( $accessToken );
            $all_schools = CleverSchool::all(); // gets the schools you have access to via your API token.

            die( var_dump( $all_schools ) );

            // CleverSchools::retrieve($id); // gets school with ID $id
            $demo_district = CleverDistrict::retrieve("4fd43cc56d11340000000005");
            $demo_schools = $demo_district->schools();
            $demo_teachers = $demo_district->teachers();
            $demo_students = $demo_district->students();
            $demo_sections = $demo_district->sections();
            $teacher = CleverTeacher::retrieve("4fee004dca2e43cf270007d5");
            $teacher_sections = $teacher->sections();
            $teacher_school = $teacher->school();
            $teacher_students = $teacher->students();
            $events = CleverEvent::all(array('ending_before' => 'last', 'limit' => 1));
        }

        */


    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());
    }

}

?>