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

$chart_filter = array();
//get filter
$filter = ( isset( $_GET['filter'] ) ) ? $_GET['filter'] : array();
//check for filter
if( !empty( $filter ) ) {
//check for filter
    $filter = ( strpos( $filter, ',') != -1 ) ? explode( ',', $filter ) : array( $filter );
}

//set by account type
if( $account['type'] == 'student' ) {

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
            'excerpt' => 'The final scores that contribute to your graduation progress at each grade level',
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
            'excerpt' => 'Your grades from all the core classes you passed year over yera.',
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

    $chart_data = array(
        'students_ontrack_to_graduate' => array(
            'cols' => '6',
            'type' => 'doughnut',
            'title' => 'Students On Track To CTE Completion',
            'label' => 'On Track Students',
            'excerpt' => '',
            'data' => array(
                'on_track' => cte_ontrack($status='On track', $group_filter=$filter),
                'off_track' => cte_ontrack($status='Off track', $group_filter=$filter),
                'at_risk' => cte_ontrack($status='At risk', $group_filter=$filter)
            )
        ),
        'grade_level_comparison' => array(
            'cols' => '6',
            'type' => 'bar', 'title' => 'Grade Level Comparison',
            'label' => 'Chart label if any',
            'excerpt' => '',
            'labels' => array('Freshman', 'Sophomore', 'Junior', 'Senior'),
            'data' => array(
                'on_track' => cte_ontrack_by_grade( $status='On track', $group_filter=$filter ),
                'off_track' => cte_ontrack_by_grade( $status='Off track', $group_filter=$filter ),
                'at_risk' => cte_ontrack_by_grade( $status='At risk', $group_filter=$filter )
            )
        ),
        'grade_level_comparison_by_percent' => array(
            'cols' => '6',
            'type' => 'line',
            'title' => 'Grade Level Comparison by Percent',
            'label' => 'Chart label if any',
            'excerpt' => '',
            'labels' => array('Freshman', 'Sophomore', 'Junior', 'Senior'),
            'data' => array(
                'on_track' => cte_percent_ontrack_by_grade( $status='On track', $group_filter=$filter ),
                'off_track' => cte_percent_ontrack_by_grade( $status='Off track', $group_filter=$filter ),
                'at_risk' => cte_percent_ontrack_by_grade( $status='At risk', $group_filter=$filter )
            )
        ),
        'fail_rate_by_subject' => array(
            'cols' => '6',
            'type' => 'bar',
            'title' => 'Fail Rate by Subject',
            'label' => 'Chart label if any',
            'excerpt' => '',
            'labels' => $bar_chart_two_out[0]["labels"],
            'data' => array( 'Subject' => array_values($bar_chart_two_out[1]["data"] ) )

        )
    );

}

//die(json_encode($bar_chart_two_out));

//set page options
$page_options['content_header']['title'] = $page_title;
$page_options['content_header']['excerpt'] = $page_excerpt;
//set flex chart module options by building and passing in the flex chart data array 
$page_options['flex_charts'] = set_flex_charts( $chart_data, 'home', $chart_filter );


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
                <?php importModule('flex_charts'); ?> 
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
