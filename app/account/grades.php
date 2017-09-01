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
$page_name = 'grades';
$page_title = 'Grades';
$page_excerpt = 'An overview of your grades';

//set page options
$page_options = array(
    'table_multiple' => array(

        'your_grades' => array(
            'title' => 'Your Grades',
            'description' => 'A list of your completed courses, score and credit received.',
            'main_class' => 'col-md-10 text-left bg-white middle p20',
            'table_class' => 'text-left table table-striped table-responsive table-condensed ',
            'thead_class' => array('text-left','text-left','text-left','text-left'),
            'tdata_class' => array('text-left','text-left','text-left','text-left'),
            'table_head' => array('Subject','Course','Score','Credit'),
            'table_data' => array(
                array( 'Language Arts', 'ENG 1 A', '78', '0.5' ),
                array( 'Language Arts', 'ENG 1 B', '78', '0.5' ),
                array( 'Language Arts', 'READ 1 A', '78', '0.5' ),
                array( 'Language Arts', 'READ 1 B', '78', '0.5' ),
                array( 'Language Arts', 'ENG 2 A', '78', '0.5' ),
                array( 'Language Arts', 'ENG 2 B', '78', '0.5' ),
                array( 'Language Arts', 'ENG 3 A', '78', '0.5' ),
                array( 'Language Arts', 'ENG 3 B', '78', '0.5' ),
                array( 'Language Arts', 'ENG 4 A', '78', '0.5' ),
                array( 'Language Arts', 'ENG 5 B', '78', '0.5' ),
                array( 'Speech', 'COMMAPP', '78', '0.5' )
            )
        )
    ),
    'flex_charts' => array(
        'score_chart' => array(
            'type' => 'pie',
            'title' => 'Score Chart',
            'description' => 'Your scores in grade percentages',
            'main_class' => 'col-md-6 text-left bg-white',
            'header_class' => 'hide',
            'footer_class' => 'hide',
            'data' => array(
                array(
                    'value' =>  50,
                    'color' =>  "#f56954",
                    'highlight' =>  "#f56954",
                    'label' =>  "Grade A => 90 - 100"
                ), 
                array(
                    'value' =>  40,
                    'color' =>  "#00a65a",
                    'highlight' =>  "#00a65a",
                    'label' =>  "Grade B => (80 - 89)"
                ), 
                array(
                    'value' =>  25,
                    'color' =>  "#f39c12",
                    'highlight' =>  "#f39c12",
                    'label' =>  "Grade C => (70 - 79)"
                ), 
                array(
                    'value' =>  20,
                    'color' =>  "#00c0ef",
                    'highlight' =>  "#00c0ef",
                    'label' =>  "Grade D => (60 - 69)"
                ), 
                array(
                    'value' =>  1,
                    'color' =>  "#3c8dbc",
                    'highlight' =>  "#3c8dbc",
                    'label' =>  "Grade F => (0 - 59)"
                )
            )
        ),
        'excel_chart' => array(
            'type' => 'pie',
            'title' => 'Excel Chart',
            'description' => 'The subjects you most excel in',
            'main_class' => 'col-md-6 text-left bg-white',
            'header_class' => 'hide',
            'footer_class' => 'hide',
            'data' => array(
                array(
                    'value' =>  60,
                    'color' =>  "#f56954",
                    'highlight' =>  "#f56954",
                    'label' =>  "Mathematics"
                ), 
                array(
                    'value' =>  10,
                    'color' =>  "#00a65a",
                    'highlight' =>  "#00a65a",
                    'label' =>  "English Language Arts"
                ), 
                array(
                    'value' =>  4,
                    'color' =>  "#f39c12",
                    'highlight' =>  "#f39c12",
                    'label' =>  "Science"
                ), 
                array(
                    'value' =>  5,
                    'color' =>  "#00c0ef",
                    'highlight' =>  "#00c0ef",
                    'label' =>  "Social Studies"
                ), 
                array(
                    'value' =>  5,
                    'color' =>  "#3c8dbc",
                    'highlight' =>  "#3c8dbc",
                    'label' =>  "Physical Education"
                ),
                array(
                    'value' =>  1,
                    'color' =>  "#c22bef",
                    'highlight' =>  "#3c8dbc",
                    'label' =>  "Fine Arts"
                ),
                array(
                    'value' =>  3,
                    'color' =>  "#2fdccf",
                    'highlight' =>  "#3c8dbc",
                    'label' =>  "Electives"
                ),
                array(
                    'value' =>  1,
                    'color' =>  "#2fdc2f",
                    'highlight' =>  "#3c8dbc",
                    'label' =>  "Speech"
                ),
                array(
                    'value' =>  9,
                    'color' =>  "#c7dc2f",
                    'highlight' =>  "#3c8dbc",
                    'label' =>  "Other Languages"
                )
            )
        )
    )
);


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
