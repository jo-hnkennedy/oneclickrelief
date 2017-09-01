<?php

include_once '../include/config.php';
include_once '../include/session.php';
include_once '../include/function.php';


//check user
if( !$user ) {
    header('Location: login.php');
}
//set menu type
$menu_type = $account['type'];

//check for other types
if( !in_array( $menu_type, $restrict_types ) ) {
    $menu_type = 'account';
}

//set dashboard
$page_name = 'dashboard';
$page_title = 'Dashboard';
$page_excerpt = 'An overview of your account';


//current student id
$studentId = '1562074';
//set campus id
$campusId = '12345';
//set schedule data
$schedule_data = array( array(), array() );
//set sql statement
$sql = 'SELECT schedule_json FROM schedules_temp WHERE hs_number = "'.$campusId.'" AND student_id LIKE "%'.$studentId.'%" LIMIT 1;';
//die( $sql );
$results = run_db_query( 'ScheduleJson', $sql );
//check query
if ($results) {
    //set output
    $schedule_data = json_decode( $results[0]['schedule_json'], true );
} else {
    // default to sample data
    $schedule_data = json_decode( '[{"semester": "S2 ", "schedule": [{"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "THEATRE ARTS 1B-1", "semester": "S2 ", "room": "H108 (027)", "period": 1, "teacher": "Cramer, Shenoa J"}, {"course": "SPANISH 1B-7", "semester": "S2 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1B-7", "semester": "S2 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}]}, {"semester": "S1 ", "schedule": [{"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}, {"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}, {"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}, {"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}, {"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}, {"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}, {"course": "SPANISH 1A-7", "semester": "S1 ", "room": "", "period": 2, "teacher": "Flores, Erica"}, {"course": "AJROTC-1A-7", "semester": "S1 ", "room": "K105 (027)", "period": 3, "teacher": "Creal, Jesse T"}, {"course": "CHEM A PREIB-14", "semester": "S1 ", "room": "D205 (027)", "period": 4, "teacher": "Harrison, Silvester N"}]}]', true);
}

// die(var_dump( $schedule_data) );

//data attributes
$table_data_attr = array(
    "paging"        => false,
    "lengthChange"  => false,
    "responsive"    => true,
    "searching"     => true,
    "ordering"      => true,
    "processing"    => true,
    "scrollX"       => true,
    "scrollY"       => true,
    "stateSave"     => true,
    "info"          => true,
    "autoWidth"     => true,
    "deferRender"   => true,
    "fixedHeader"   => false,
    "buttons"       => true
);
//set page options
$page_options = array(
    'calendar_one' => array(
        'title' => 'View your schedule',
        'add_text' => 'Add a class',
        'view_text' => 'View schedule',
        'clear_text' => 'Change schedule',
        //set options
        'show_expand' => false,
        'show_remove' => false,
        //set bar size ( empty = large)
        'progress_style' => 'animated sm',
        //set progress
        'progress_left' => array(
            array('Math Classes Taken', 60, 'green'),
            array('English Classes Taken', 40, 'yellow'),
            array('Science Classes Taken', 50, 'blue'),
            array('Social Studies Classes Taken', 20, 'red')
        ),
        'progress_right' => array(
            array('Elective Classes Taken', 45, 'aqua'),
            array('Physical Education Taken', 40, 'yellow'),
            array('S.T.E.M Completion ', 50, 'blue'),
            array('Total Credits Received', 90, 'green')
        )
    ),
    'table_multiple' => array(
        'schedule' => array(
            'title' => 'Your Schedule',
            'description' => '<div>Your schedule for the first semester.</div>',
            'main_class' => 'col-md-10 text-left bg-white middle p20',
            'table_class' => 'text-left table table-striped table-responsive table-condensed data_table w100',
            'thead_class' => array('text-left','text-left','text-left','text-left','text-left'),
            'tdata_class' => array('text-left','text-left','text-left','text-left','text-left'),
            'table_head' => array('Course','Semester','Room','Period','Teacher'),
            'table_data' => array(),
            'data_attr' => $table_data_attr
        )
    )
);
$unique = array();
foreach( $schedule_data as $semester => $tdata ){
    $sem = ( $semester + 1 );
    foreach( $tdata['schedule'] as $course ) {
        $code = array_values( $course );
        $hash = hash( 'sha256', json_encode( $code ) );
        if( !isset( $unique[ $hash ] ) ) {
            $page_options['table_multiple']['schedule']['table_data'][] = $code;
            $unique[ $hash ] = 1;
        }
    }
}
//set unique
?>
    <!DOCTYPE html>
    <html lang="en">
    
    <?php importModule('main_header'); ?> 

    <body id="page-top" class="loggedin hold-transition skin-<?php echo $account['layout']['skin_color']; ?> sidebar-mini">
        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <?php importModule('navbar_header'); ?>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php echo setMenu( $menu_type,  $top_menu, $page_name, $account ); ?>
                    </ul>
                </div>
            </div>
        </nav>
        <header class="main-header">
            <a href="#" class="logo"><?php echo ucwords($page_name); ?></a>
            <?php importModule('section_topnav'); ?>
        </header>
        <div class="wrapper">

            <?php importModule('sidebar_left'); ?> 
            <div class="content-wrapper">
                <?php importModule('content_header'); ?> 
                <section class="content black">
                    <?php importModule('table_multiple'); ?> 
                </section>
            </div>
            <?php importModule('section_footer'); ?> 
            <?php importModule('sidebar_right'); ?> 
        </div>

        <?php importModule('main_footer'); ?> 

    </body>

    </html>
