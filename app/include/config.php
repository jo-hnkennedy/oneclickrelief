<?php


GLOBAL $user;
GLOBAL $cache;
GLOBAL $debug;
GLOBAL $modules;
GLOBAL $account; 
GLOBAL $session;
GLOBAL $protocol;
GLOBAL $crd_spfx;
GLOBAL $crd_sext;
GLOBAL $crd_sdir;
GLOBAL $crd_snid;
GLOBAL $crd_sact;
GLOBAL $top_menu;
GLOBAL $res_ddir;
GLOBAL $res_dpdr;
GLOBAL $acc_ddir;
GLOBAL $mod_ddir;
GLOBAL $css_ddir;
GLOBAL $css_dpdr;
GLOBAL $scr_ddir;
GLOBAL $scr_dpdr;
GLOBAL $queries;
GLOBAL $db_path;
GLOBAL $db_cred;
GLOBAL $db_types;
GLOBAL $db_store;
GLOBAL $db_table;
GLOBAL $db_type;

//SET GOBAL ACCOUNT TYPE
GLOBAL $account_types;
GLOBAL $console_types;
GLOBAL $private_types;
GLOBAL $customer_types;
GLOBAL $restrict_types;
GLOBAL $page_options;
GLOBAL $page_excerpt;
GLOBAL $page_title;
GLOBAL $page_name;
GLOBAL $top_menu;
GLOBAL $left_menu;


GLOBAL $campusId;

//SET GLOBAL
define('modules', true);

$cache = true;
$user = false;
$debug = true;
$account = false;
$session = false;
$modules = array();

//maximum public comment
$auto_approve_comment = true;
$max_public_comment = 3;
$max_public_message = 3;
$max_public_checkme = 5;
$max_public_winners = 5;
$max_public_generate = 5;

//protocol
$protocol = 'http://';

//set accout directory
$acc_ddir = 'account/';
//set module directory
$mod_ddir = 'modules/';

//credentials
$crd_spfx = 'les-';
$crd_sext = '.json';
$crd_indx = '_index_';
$crd_sdir = '../session/';
$crd_spdr = 'session/';
$crd_snid = 'MesaonTime';
$crd_sact = '';

//set css directory
$css_ddir = 'css/';
$css_dpdr = '../css/';

//set script directory
$scr_ddir = 'js/';
$scr_dpdr = '../js/';

//set resource directory
$res_ddir = 'resource/';
$res_dpdr = '../resource/';

//set account types
$account_types = array(
    'admin' => array(),
    'student' => array(),
    'campus' => array(),
    'district' => array()
);

//set private types
$private_types = array(
    'admin', 'district'
);

//set restricted types
$restrict_types = array(
    'campus'
);

//set restricted types
$customer_types = array(
    'student', 'campus'
);

//set services
$account_services = array(
    'message' => 'Send A Message',
    'comment' => 'Send A Comment'
);

//set top menu
$top_menu = array(
    //Type => Name ( Path, Label, Extension, Role )
    'public' => array(
        'mission'       => array('#', 'Our Mission', '', 'both'),
        //'signup'        => array('#', 'Signup', '', 'public'),
        'login'         => array('#', 'Login', '', 'public'),
        'logout'        => array('/account/', 'Logout', '.php', 'private')
    ),
    'student' => array(
        'account'       => array('', 'Account', '.php', 'private'),
        'logout'        => array('', 'Logout', '.php', 'private')
    ),
    'parent' => array(
        'account'       => array('', 'Account', '.php', 'private'),
        'logout'        => array('', 'Logout', '.php', 'private')
    ),
    'campus' => array(
        'home'          => array('', 'Home', '.php', 'private'),
        //'upload'        => array('#', 'Upload', '', 'private'),
        'account'       => array('', 'Account', '.php', 'private'),
        'logout'        => array('', 'Logout', '.php', 'private')
    ),
    'district' => array(
        'home'          => array('', 'Home', '.php', 'private'),
        'upload'        => array('#', 'Upload', '', 'private'),
        'account'       => array('', 'Account', '.php', 'private'),
        'logout'        => array('', 'Logout', '.php', 'private')
    ),
    'account' => array(
        'home'          => array('', 'Home', '.php', 'private'),
        'account'       => array('', 'Account', '.php', 'private'),
        'logout'        => array('', 'Logout', '.php', 'private')
    )
);


//set top menu
$left_menu = array(
    'student' => array(
        'gradplan'      => array('Grad Plan', '.php', 'list-ol'),
        'schedule'      => array('Schedule', '.php', 'calendar'),
        'grades'        => array('Grades', '.php', 'font')
    ),
    'parent' => array(
        'gradplan'      => array('Grad Plan', '.php', 'list-ol'),
        'endorsements'  => array('Endorsements', '.php', 'check-circle'),
        'schedule'      => array('Schedule', '.php', 'calendar'),
        'grades'        => array('Grades', '.php', 'font')
    ),
    'campus' => array(
        'indicators'    => array('Indicators', '.php', 'pie-chart', 'subnav' => array(
                                array( 'groups.php?type=indicators&filter=distinguished','Distinguished Progress', ''),
                                array( 'groups.php?type=indicators&filter=endorsements','Endorsements Progress', ''),
                                array( 'cte.php','CTE Progress', ''),
                                array( 'groups.php?type=indicators&filter=post_secondary','Post Secondary Progress', ''),
                                array( 'groups.php?type=indicators&filter=industry_cert','Industry Cert Progress', '')
                        ) ),
        'groups'        => array('Groups', '.php', 'users', 'subnav' => array(
                                array( 'groups.php?filter=esl', 'ESL', ''),
                                array( 'groups.php?filter=retester', 'ReTester', ''),
                                array( 'groups.php?filter=sped', 'SPED', ''),
                                array( 'groups.php?filter=freshman', 'Freshman', ''),
                                array( 'groups.php?filter=sophomore', 'Sophomore', ''),
                                array( 'groups.php?filter=junior', 'Junior', ''),
                                array( 'groups.php?filter=senior', 'Senior', '')
                        ) ),
        'gradplan'      => array('Grad Plan', '.php', 'list-ol'),
    ),
    'district' => array(
        'indicators'    => array('Classes', '.php', 'pie-chart', 'subnav' => array(
                                array( 'groups.php?type=indicators&filter=distinguished', 'Distinguished Progress', ''),
                                array( 'groups.php?type=indicators&filter=endorsements', 'Endorsements Progress', ''),
                                array( 'groups.php?type=indicators&filter=cte_progress', 'CTE Progress', ''),
                                array( 'groups.php?type=indicators&filter=post_secondary', 'Post Secondary Progress', ''),
                                array( 'groups.php?type=indicators&filter=industry_cert', 'Industry Cert Progress', '')
                        ) ),
        'groups'        => array('Groups', '.php', 'users', 'subnav' => array(
                                array( 'groups.php?filter=ib', 'IB', ''),
                                array( 'groups.php?filter=stem', 'STEM', ''),
                                array( 'groups.php?filter=ccr', 'CCR', ''),
                                array( 'groups.php?filter=early_college', 'Early College', ''),
                                array( 'groups.php?filter=district_1', 'District 1', ''),
                                array( 'groups.php?filter=district_2', 'District 2', ''),
                                array( 'groups.php?filter=district_3', 'District 3', ''),
                                array( 'groups.php?filter=district_4', 'District 4', ''),
                                array( 'groups.php?filter=district_5', 'District 5', ''),
                                array( 'groups.php?filter=district_6', 'District 6', ''),
                                array( 'groups.php?filter=district_7', 'District 7', ''),
                                array( 'groups.php?filter=district_8', 'District 8', ''),
                                array( 'groups.php?filter=district_9', 'District 9', '')
                        ) ),
        'schools'       => array('Schools', '.php', 'university')
    ),
    'admin' => array(
        'indicators'    => array('Classes', '.php', 'pie-chart', 'subnav' => array(
                                array( 'groups.php?type=indicators&filter=distinguished', 'Distinguished Progress', ''),
                                array( 'groups.php?type=indicators&filter=endorsements', 'Endorsements Progress', ''),
                                array( 'groups.php?type=indicators&filter=cte_progress', 'CTE Progress', ''),
                                array( 'groups.php?type=indicators&filter=post_secondary', 'Post Secondary Progress', ''),
                                array( 'groups.php?type=indicators&filter=industry_cert', 'Industry Cert Progress', '')
                        ) ),
        'groups'        => array('Groups', '.php', 'users', 'subnav' => array(
                                array( 'groups.php?filter=ib', 'IB', ''),
                                array( 'groups.php?filter=stem', 'STEM', ''),
                                array( 'groups.php?filter=ccr', 'CCR', ''),
                                array( 'groups.php?filter=early_college', 'Early College', ''),
                                array( 'groups.php?filter=district_1', 'District 1', ''),
                                array( 'groups.php?filter=district_2', 'District 2', ''),
                                array( 'groups.php?filter=district_3', 'District 3', ''),
                                array( 'groups.php?filter=district_4', 'District 4', ''),
                                array( 'groups.php?filter=district_5', 'District 5', ''),
                                array( 'groups.php?filter=district_6', 'District 6', ''),
                                array( 'groups.php?filter=district_7', 'District 7', ''),
                                array( 'groups.php?filter=district_8', 'District 8', ''),
                                array( 'groups.php?filter=district_9', 'District 9', '')
                        ) ),
        'schools'       => array('Schools', '.php', 'university'),
        'students'      => array('Students', '.php', 'user-circle-o')
    )
);


//set user session options
$user_session = array(
    'user' => array(
        'email' => 'admin@mesaedu.com',
        'name' => 'admin',
        'display' => 'Administrator',
        'status' => 'online'
    ),
    'session' => array(
        'loggedin' => date('Y/m/d h:i:s A'),
        'browser'  => '',
        'referer'  => '',
        'ipaddrs'  => '',
        'session'  => md5(date('ymdhis')),
        'reset'    => md5(date('ymd:his').'admin'),
        'name'     => 'admin',
        'id'       => 'admin'
    ),
    'account' => array(
        'type' => 'user',
        'active' => false,
        'created' => date('Y/m/d h:i:s'),
        'details' => array(),
        'services' => array(),
        'settings' => array(),
        'numbers' => array(),
        'activity' => array(),
        'profile' => array(
            'photo' => '',
            'tagline' => '',
        ),
        'layout' => array(
            'fixed_layout' => false,
            'boxed_layout' => false,
            'toggle_sidebar' => false,
            'sidebar_expand' => false,
            'sidebar_slide' => false,
            'sidebar_skin' => false,
            'skin_color' => 'orange'
        ),
        'settings' => array(
            'report_usage' => true,
            'mail_direct' => true,
            'author_name' => true,
            'chat_online' => true,
            'off_notify' => false
        ),
        'chats' => array(),
        'messages' => array(),
        'analytics' => array(),
        'activity' => array()
    )
);


//set page options
$page_options = array(
    'content_header' => array(
        'title' => '',
        'excerpt' => '',
        'actions' => array(
            'print' => array('Print', '?print=1', '_blank', 'print'),
            'email' => array('Email', 'mailto: ', '_blank', 'envelope'),
            'export' => array('Export', 'list', '_blank', 'download', 'data' => array(
                    array( 'PDF', 'http://seamarsh.org/export-20170717T202316Z-001.zip', '_blank', 'file-pdf-o' )
                    //array( 'TSV', '?export=TSV', '_blank', 'file-text-o' )
                )
            )
        )
    )
);

//database store types
$db_types = array('sql','file');
//database store type
$db_store = 'sql'; //sql, file
//databsae table name
$db_table = 'no_name';
//database bassword
$db_pswd = 'Some_Password_123!@#';

//database credentials ( MYSQL )
$db_type = 'MYSQL'; //MYSQL, SQLITE, PGSQL
$db_cred = array( 
    'port'     => '3306',
    'database' => 'ontime',
    'hostname' => 'ontime1.crbvit8ifwfb.us-east-1.rds.amazonaws.com', 
    'username' => 'mesa', 
    'password' => 'sharksgotofinland'
);

//databsae path ( SQLITE )
$db_path = '../process/db';

//queries 
$queries = array(
    'campus_ticker'                     => 'ADD SQL STATEMENT HERE',
    'campus_comparision'                => array( "SELECT COUNT(*) FROM ontrack WHERE", array( "grade" => "Senior" ) ),
    'grade_level_comparision'           => 'ADD SQL STATEMENT HERE',
    'comparative_growth_chart'          => 'ADD SQL STATEMENT HERE',
    'students_ontrack_to_graduate'      => array( "SELECT COUNT(*) FROM ontrack WHERE", array( "status" => "On track" ) ),
    'students_offtrack_to_graduate'     => array( "SELECT COUNT(*) FROM ontrack WHERE", array( "status" => "Off track" ) ),
    'students_atrisk_to_not_graduate'   => array( "SELECT COUNT(*) FROM ontrack WHERE", array( "status" => "At risk" ) ),
    'senior_ontrack_to_graduate'        => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "On track", "grade" => 12 ) ),
    'junior_ontrack_to_graduate'        => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "On track", "grade" => 11 ) ),
    'sophomore_ontrack_to_graduate'     => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "On track", "grade" => 10 ) ),
    'freshman_ontrack_to_graduate'      => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "On track", "grade" => 9 ) ),
    'senior_offtrack_to_graduate'       => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "Off track", "grade" => 12 ) ),
    'junior_offtrack_to_graduate'       => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "Off track", "grade" => 11 ) ),
    'sophomore_offtrack_to_graduate'    => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "Off track", "grade" => 10 ) ),
    'freshman_offtrack_to_graduate'     => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "Off track", "grade" => 9 ) ),
    'senior_atrisk_to_no_graduate'      => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "At risk", "grade" => 12 ) ),
    'junior_atrisk_to_no_graduate'      => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "At risk", "grade" => 11 ) ),
    'sophomore_atrisk_to_no_graduate'   => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "At risk", "grade" => 10 ) ),
    'freshman_atrisk_to_no_graduate'    => array( "SELECT student_name FROM ontrack WHERE", array( "status" => "At risk", "grade" => 9 ) ),
    'percent_ontrack_to_graduate'       => 'ADD SQL STATEMENT HERE'
); 


?>