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
$page_name = 'endorsements';
$page_title = 'Endorsements';
$page_excerpt = 'A look into your graduation plan';

//set page options
$page_options = array(
    'infobox_two' => array(
    ),
    'todo_list' => array(
    ),
    'charts_graph' => array(
    ),
    'charts_report' => array(
    ),
    'map_one' => array(
    ),
    'infobox_three' => array(
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
                    <div class="row">
                        <div class="col-md-12">
                            <iframe src="/pdf/SidebySideGraduationPrograms_030114.pdf" style="width:100%;height:2000px"></iframe>
                        </div>
                    </div>
                </section>
            </div>
            <?php importModule('section_footer'); ?> 
            <?php importModule('sidebar_right'); ?> 
        </div>

        <?php importModule('main_footer'); ?> 

    </body>

    </html>
