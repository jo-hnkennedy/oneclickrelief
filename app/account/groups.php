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
$page_excerpt = 'An overview of your progress';

//set flex module
$chart_module = 'flex_charts';
//set has data
$has_data = true;
//set chart filter
$chart_filter = array();
//get filter
$filter = ( isset( $_GET['filter'] ) ) ? $_GET['filter'] : array();
//check for filter
if( !empty( $filter ) ) {
//check for filter
    $filter = ( strpos( $filter, ',') != -1 ) ? explode( ',', $filter ) : array( $filter );
}

//get all on track data for export table
$tableHead = array('Student Name', 'Student ID', 'Grade Level', 'Status');
$allOnTrack = get_chart_table_data( 'Students On Track To Graduate', $filter, $tableHead );
$allOnGLVCP = get_chart_table_data( 'Grade Level Comparison', $filter, $tableHead );
$allOnGLCPP = get_chart_table_data( 'Grade Level Comparison by Percent', $filter, $tableHead );
$allFalRate = get_chart_table_data( 'Fail Rate by Subject', $filter, $tableHead );



//check if ajax data
if( isset( $_GET['ajax'] ) ) {
    //set ajax data
    $data = array();
    $ajax = $_GET['ajax'];
    //check ajax type
    if( $ajax == 'students_ontrack_to_graduate' ) { $data = $allOnTrack; }
    if( $ajax == 'grade_level_comparison' ) { $data = $allOnGLVCP; }
    if( $ajax == 'grade_level_comparison_by_percent' ) { $data = $allOnGLCPP; }
    if( $ajax == 'fail_rate_by_subject' ) { $data = $allFalRate; }
    //return data
    die( json_encode( array( 'data' => $data ) ) );
}




//set by account type
if( $account['type'] == 'student' ) {

    //only show print if student
    $page_options['content_header']['actions'] = array(
        'print' => array('Print', '?print=1', '_blank', 'print')
    );

    //set student table
    $page_options['table_simple'] = array(
        'title' => '',
        'description' => '',
        'main_class' => '',
        'table_class' => 'text-left table table-responsive',
        'thead_class' => array('bold text-left h4 p20'),
        'tdata_class' => array('text-left h4'),
        'table_head' => array('Your Status'),
        'table_data' => array(
            array( 
                '<div class="info-box bg-aqua">'.
                '<span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span><div class="info-box-content">' . 
                '<span class="info-box-text">Total Credits</span><span class="info-box-number">22.5</span>' . 
                '<div class="progress"><div class="progress-bar" style="width: 80%"></div> </div>' . 
                '<span class="progress-description">30% Increase from prior year</span></div></div>'
            ),
            array( 
                '<div class="info-box bg-green">'.
                '<span class="info-box-icon"><i class="fa fa-thumbs-o-up"></i></span><div class="info-box-content">' . 
                '<span class="info-box-text">Total Classes Passed </span><span class="info-box-number">24</span>' . 
                '<div class="progress"><div class="progress-bar" style="width: 70%"></div> </div>' . 
                '<span class="progress-description">70% Increase in 9 Months</span></div></div>'
            ),
            array( 
                '<div class="info-box bg-red">'.
                '<span class="info-box-icon"><i class="fa fa-thumbs-o-down"></i></span><div class="info-box-content">' . 
                '<span class="info-box-text">Total Absenses To Date</span><span class="info-box-number">3</span>' . 
                '<div class="progress"><div class="progress-bar" style="width: 30%"></div> </div>' . 
                '<span class="progress-description">30% Increase in 30 Days</span></div></div>'
            )
        )
    );
    //get request data
    $chart_data = array(
        'line_chart_two' => array(
            'cols' => '6',
            'type' => 'line',
            'title' => 'Graduation Progress',
            'label' => 'Chart label if any',
            'excerpt' => '',
            'tabbed' => '',
            'labels' => array('Freshman', 'Sophomore', 'Junior', 'Senior'),
            'data' => array(
                'Math' => array(82,86,73),
                'English' => array(87,76,79),
                'Science' => array(86,73,74),
                'Social Studies' => array(89,89,70),
                'Electives' => array(90,99,89)
            )
        ),
        'bar_chart' => array(
            'cols' => '6',
            'type' => 'bar',
            'title' => 'Core Class Grades',
            'label' => 'Chart label if any',
            'excerpt' => '',
            'labels' => array('Math', 'English', 'Science', 'Social Studies'),
            'data' => array(
                'Freshman' => array(82,87,86,89),
                'Sophomore' => array(82,87,86,89),
                'Junior' => array(73,79,74,70),
            )
        )
    );

} else {
    //get request data

    #prepping bar_chaart lables
    $bar_chart_two_out = json_decode(fail_rate_subject($group_filter=$filter), true);
    #print var_dump($bar_chart_two_out[0]["labels"]);
    $has_data = ( ontrack($status='On track', $group_filter=$filter)[0] > 0 ) ? true : false;
    //set chart data
    $chart_data = array(
        'students_ontrack_to_graduate' => array(
            'cols' => '6',
            'type' => 'doughnut',
            'title' => 'Students On Track To Graduate',
            'label' => 'On Track Students',
            'excerpt' => 'The students who are On Track to graduate, Off Track or ' . 
                         '<a href="#" data-toggle="tooltip" class="text-orange" title="At Risk: Students who are at risk of not graduating">At Risk</a> of not graduating.',
            'colors' => array(
                'on_track' => '#00a65a',
                'off_track' => '#b92b2c',
                'at_risk' => '#ff8a40'
            ),
            'options' => array(
                'title' => array(
                    'text' => 'At Risk: Students who are at risk of not graduating',
                    'display' => true
                )
            ),
            'data' => array(
                'on_track' => ontrack($status='On track', $group_filter=$filter),
                'off_track' => ontrack($status='Off track', $group_filter=$filter),
                'at_risk' => ontrack($status='At risk', $group_filter=$filter)
            ),
            'table' => $allOnTrack
        ),
        'grade_level_comparison' => array(
            'cols' => '6',
            'type' => 'bar', 'title' => 'Grade Level Comparison',
            'label' => 'Chart label if any',
            'excerpt' => 'The number of students who are on track to graduate in each grade year',
            'labels' => array('Freshman', 'Sophomore', 'Junior', 'Senior'),
            'colors' => array(
                'on_track' => '#00a65a',
                'off_track' => '#b92b2c',
                'at_risk' => '#ff8a40'
            ),
            'data' => array(
                'on_track' => ontrack_by_grade( $status='On track', $group_filter=$filter ),
                'off_track' => ontrack_by_grade( $status='Off track', $group_filter=$filter ),
                'at_risk' => ontrack_by_grade( $status='At risk', $group_filter=$filter )
            ),
            'table' => $allOnGLVCP
        ),
        'grade_level_comparison_by_percent' => array(
            'cols' => '6',
            'type' => 'line',
            'title' => 'Grade Level Comparison by Percent',
            'label' => 'Chart label if any',
            'excerpt' => 'Grade Level Statuses as Percentages',
            'labels' => array('Freshman', 'Sophomore', 'Junior', 'Senior'),
            'colors' => array(
                'on_track' => '#00a65a',
                'off_track' => '#b92b2c',
                'at_risk' => '#ff8a40'
            ),
            'data' => array(
                'on_track' => percent_ontrack_by_grade( $status='On track', $group_filter=$filter ),
                'off_track' => percent_ontrack_by_grade( $status='Off track', $group_filter=$filter ),
                'at_risk' => percent_ontrack_by_grade( $status='At risk', $group_filter=$filter )
            ),
            'table' => $allOnGLCPP
        ),
        'fail_rate_by_subject' => array(
            'cols' => '6',
            'type' => 'bar',
            'title' => 'Fail Rate by Subject',
            'label' => 'Chart label if any',
            'excerpt' => 'Percentage of Courses Failed in Each Subject',
            'labels' => $bar_chart_two_out[0]["labels"],
            'data' => array( 'Subject' => array_values($bar_chart_two_out[1]["data"] ) ),
            'table' => $allFalRate
        )
    );

}

//die(json_encode($bar_chart_two_out));

//set page options
$page_options['content_header']['title'] = $page_title;
$page_options['content_header']['excerpt'] = $page_excerpt;
//set flex chart module options by building and passing in the flex chart data array 
$page_options['flex_charts'] = set_flex_charts( $chart_data, 'home', $chart_filter );

//set flex module to display
if( !$has_data ) { 
    $chart_module = 'section_text';
    $page_options['section_text'] = array(
        'title' => 'No data found',
        'detail' => 'There is no data to display charts at this time',
        'main_class' => 'col-md-12 text-left bg-white'
    );
}

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
                <section class="content">   
                
                <?php importModule( $chart_module ); ?> 

                    <?php if( $account['type'] == 'student' ) { ?>
                    <div class="col-md-6 bg-white"><?php importModule('todo_list'); ?></div>
                    <div class="col-md-6 bg-white"><?php importModule('table_simple'); ?> </div>
                    <div class="clear"></div>
                    <?php } ?>
                </section>
            </div>
            <?php importModule('section_footer'); ?> 
            <?php importModule('sidebar_right'); ?> 
        </div>

        <?php importModule('main_footer'); ?> 

    </body>

    </html>
