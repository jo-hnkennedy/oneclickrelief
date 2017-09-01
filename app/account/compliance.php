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
$page_name = 'indicators';
$page_title = 'Indicators';
$page_excerpt = 'The plan to help you graduate';

//set page options
$page_options = array(
    'table_multiple' => array(
        'student_report' => array(
            'title' => 'HB2804 Report',
            'description' => 'House Bill (HB) 2804 Implementation - Texas Education Agency - Report',
            'main_class' => 'col-md-10 text-left bg-white middle p20',
            'table_class' => 'text-left table table-responsive p20',
            'thead_class' => array('bold text-left h4 p20'),
            'tdata_class' => array('text-left h4 p20'),
            'table_head' => array('Completion Status'),
            'table_data' => array(
                array( 
                    '<strong>70.898%</strong> of students have successfully completed the curriculum requirements for the distinguished level of achievement' . 
                    '<div class="progress"><div class="progress-bar progress-bar-striped bg-green progress-bar-animated" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div></div><br>'
                ),
                array( 
                    '<strong>0.929%</strong> of students have successfully completed the curriculum requirements for an endorsement under Section 28.025'  . 
                    '<div class="progress"><div class="progress-bar progress-bar-striped bg-red progress-bar-animated" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div></div><br>'
                ),
                array( 
                    '<strong>53.560%</strong> of students have successfully completed an AP course' . 
                    '<div class="progress"><div class="progress-bar progress-bar-striped bg-blue progress-bar-animated" role="progressbar" aria-valuenow="53" aria-valuemin="0" aria-valuemax="100"></div></div><br>' 
                ),
                array( 
                    '<strong>0%</strong> of students have at least 12 hours of postsecondary credit required for the foundation high school program' . 
                    '<div class="progress"><div class="progress-bar progress-bar-striped bg-red progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div><br>' 
                ),
                array( 
                    '<strong>15.659%</strong> of students have completed a coherent sequence of career and technical courses' . 
                    '<div class="progress"><div class="progress-bar progress-bar-striped bg-orange progress-bar-animated" role="progressbar" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div></div><br>' 
                ),
            )
        )
    ),
    'flex_charts' => array(
        'score_chart' => array(
            'type' => 'pie',
            'title' => 'Chavez CTE Breakdown',
            'description' => 'CTE Breakdown for Chavez High School',
            'main_class' => 'col-md-6 text-left bg-white',
            'header_class' => 'hide',
            'footer_class' => 'hide',
            'data' => array(
                array(
                    'value' =>  48.5,
                    'color' =>  "#f56954",
                    'highlight' =>  "#f56954",
                    'label' =>  "1 Credit Away"
                ), 
                array(
                    'value' =>  33.3,
                    'color' =>  "#00a65a",
                    'highlight' =>  "#00a65a",
                    'label' =>  "3+ Credits Away"
                ), 
                array(
                    'value' =>  5,
                    'color' =>  "#f39c12",
                    'highlight' =>  "#f39c12",
                    'label' =>  "2 Credits Away"
                ), 
                array(
                    'value' =>  15.7,
                    'color' =>  "#00c0ef",
                    'highlight' =>  "#00c0ef",
                    'label' =>  "Completed"
                )
            )
        ),
        'excel_chart' => array(
            'type' => 'bar',
            'title' => '1 Credit Away',
            'description' => 'Pathways of Students Who Are Only 1 Credit Away',
            'main_class' => 'col-md-6 text-left bg-white',
            'header_class' => 'hide',
            'footer_class' => 'hide',
            'data' => array(
                'labels' => array( "A.F.NR", "S.T.E.M.", "I.T.", "H.S" ),
                'datasets' => array(
                    array(
                        "label" => "1 Credit Away",
                        "fillColor" => "#f56954",
                        "strokeColor" => "#b54a3a",
                        "pointColor" => "rgba(210, 214, 222, 1)",
                        "pointStrokeColor" => "#c1c7d1",
                        "pointHighlightFill" => "#fff",
                        "pointHighlightStroke" => "rgba(220,220,220,1)",
                        "data" => array( 175, 80, 50, 20 )
                    )
                )
            )
        )
    )
);

//get filter
$filter = ( isset( $_GET['filter'] ) ) ? $_GET['filter'] : false;
//get menu items
$menu_items = $left_menu[ $account['type'] ][ $page_name ]['subnav'];
//get menu labels
$menu_labels = array_column( $menu_items, 1 );

//set excerpt
$page_excerpt = '<div class="dropdown m20">';
$page_excerpt .= '<a href="#" class="dropdown-toggle bg-white black p10" data-toggle="dropdown"> Change ' . $page_title . ' <span class="caret"></span></a>';
$page_excerpt .= '<ul class="dropdown-menu">';
foreach( $menu_items as $item ) { 
    $page_excerpt .= '<li><a href="' . $item[0] . '" class="text-black">' . $item[1] . '</a></li>';
}
$page_excerpt .= '</ul></div>';

//check filter
if ( $filter ) {
    //get menu items
    $filter_query = $page_name . '.php?filter=' . $filter;
    //set menu key
    $menu_key = array_search( $filter_query, array_column( $menu_items, 0 ) );
    //check for key
    if( $menu_key !== false ) {
        //set page title
        $page_title = $left_menu[ $account['type'] ][ $page_name ][0];
        $page_title .= ' - ' . $menu_items[ $menu_key ][1];
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">
    
    <?php importModule('main_header'); ?> 

    <body id="page-top" class="loggedin hold-transition skin-<?php echo $account['layout']['skin_color']; ?> sidebar-mini">
        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                    </button>
		    <a class="navbar-brand page-scroll" href="home.php">Mesa onTime</a>
                </div>
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
                    <?php importModule('table_multiple'); ?> 
                    <div class="col-md-10 middle bg-white">
                        <?php importModule('flex_charts'); ?> 
                    </div>
                </section>
            </div>
            <?php importModule('section_footer'); ?> 
            <?php importModule('sidebar_right'); ?> 
        </div>

        <?php importModule('main_footer'); ?> 

    </body>

    </html>
