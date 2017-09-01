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
$page_name = 'students';
$page_title = 'Students';
$page_excerpt = 'A list of students by: filter';

//set page options
$page_options = array(
    'table_multiple' => array(
        'gradtrack' => array(
            'title' => 'Graduation Tracker',
            'description' => '<div>Track your graduation progress</div><br>
                <span class="green bg-white p10">*Completed</span> 
                <span class="bg-white orange p10">*Incomplete</span>
                <span class="bg-white red p10">*Required</span>
            ',
            'main_class' => 'col-md-10 text-left bg-white middle p20',
            'table_class' => 'text-left table table-striped table-responsive table-condensed ',
            'thead_class' => array('text-left','text-left','text-left'),
            'tdata_class' => array('bold text-left','green text-left','green text-left'),
            'table_head' => array('Discipline','Foundation HSP','Distinguished Achievement Plan'),
            'table_data' => array(
                array( 
                    'English Language Arts', 
                    '<div>English I</div><div>English II</div><div>English III</div></div><div><div class="orange">An Advanced English course*</div>',
                    '<div>English I</div><div>English II</div><div>English III</div></div><div><div class="orange">English IV**</div>' 
                ),
                array( 
                    'Mathematics', 
                    '<div>Algebra I</div><div>Geometry</div></div><div><div class="orange">An Advanced Math course*</div>',
                    '<div>Algebra I</div><div>Geometry</div><div>Algebra II</div></div><div><div class="orange">An Additional Math Credit*</div>' 
                ),
                array( 
                    'Science', 
                    '<div>Biology</div><div>IPC or an advanced science course</div><div>An advanced science course</div>',
                    '<div>Biology</div><div>Chemistry</div></div><div><div class="orange">Physics*</div></div><div><div>An Additional Science Credit</div>' 
                ),
                array( 
                    'Social Studies', 
                    '<div>U.S. History</div><div class="orange">U.S. Government (half credit)*</div><div class="orange">Economics (half credit)*</div><div>World History or World Geography</div>',
                    '<div>U.S. History</div><div class="orange">U.S. Government (half credit)*</div><div class="orange">Economics (half credit)*</div><div>World History</div>'
                ),
                array( 
                    'Physical Education', 
                    '<div>One Credit</div>',
                    '<div>One Credit</div>' 
                ),
                array( 
                    'Languages Other Than English', 
                    '<div>Two Credits in the Same Language (including Comp Sci</div>',
                    '<div>Three Credits in the Same Language (including Comp Sci)</div>' 
                ),
                array( 
                    'Fine Arts', 
                    '<div>One Credit</div>',
                    '<div>One Credit</div>' 
                ),
                array( 
                    'Speech', 
                    '<div class="orange">Demonstrated proficiency*</div>',
                    '<div class="orange">Communication Applications or Professional Communications*</div>' 
                ),
                array( 
                    'Electives', 
                    '<div>You have 9 out of 5 needed credits</div>',
                    '<div>You have 9 out of 4.5 needed credits</div>' 
                )
            )
        ),
        'cte_progress' => array(
            'title' => 'CTE Progress',
            'description' => 'Marketing Pathway / Fashion Marketing Sequence',
            'main_class' => 'col-md-10 text-left bg-white middle p20',
            'table_class' => 'text-left table table-striped table-responsive table-condensed ',
            'thead_class' => array('text-left'),
            'tdata_class' => array('text-left orange'),
            'table_head' => array('Incomplete Courses'),
            'table_data' => array(
                array( 'Principles of Business, Marketing, and Finance' ),
                array( 'Fashion Marketing' ),
                array( 'Marketing Dynamics' ),
                array( 'Practicum in Marketing Dynamics' ),
            )
        ),
        'your_courses' => array(
            'title' => 'Your Courses',
            'description' => '',
            'main_class' => 'col-md-10 text-left bg-white middle p20',
            'table_class' => 'text-left table table-striped table-responsive table-condensed ',
            'thead_class' => array('text-left','text-left','text-left','text-left'),
            'tdata_class' => array('text-left','text-left','text-left','text-left'),
            'table_head' => array('Subject','Course','Period','Teacher'),
            'table_data' => array(
                array( 'Language Arts', 'ENG 1 A', '1', 'Mrs. Robinson' ),
                array( 'Language Arts', 'ENG 1 B', '2', 'Mrs. Robinson' ),
                array( 'Language Arts', 'READ 1 A', '3', 'Betty Sue' ),
                array( 'Language Arts', 'READ 1 B', '4', 'Betty Sue' ),
                array( 'Language Arts', 'ENG 2 A', '5', 'Mr. Skinner' ),
                array( 'Language Arts', 'ENG 2 B', '6', 'Mr. Skinner' ),
                array( 'Language Arts', 'ENG 3 A', '7', 'Mr. Skinner' ),
                array( 'Language Arts', 'ENG 3 B', '8', 'Mr. Skinner' ),
                array( 'Language Arts', 'ENG 4 A', '1', 'Mr. Skinner' ),
                array( 'Language Arts', 'ENG 5 B', '2', 'Mr. Skinner' ),
                array( 'Speech', 'COMMAPP', '7', 'Mr. Smith' )
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
                </section>
            </div>
            <?php importModule('section_footer'); ?> 
            <?php importModule('sidebar_right'); ?> 
        </div>

        <?php importModule('main_footer'); ?> 

    </body>

    </html>
