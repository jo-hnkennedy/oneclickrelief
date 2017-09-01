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

//only allow if account type is district
if( $account['type'] != 'district'/* || 
    $account['type'] != 'campus'*/
) {
    header('Location: home.php');
}

//set dashboard
$page_name = 'dashboard';
$page_title = 'Dashboard';
$page_excerpt = 'An overview of your account';

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
                    <?php importModule('infobox_two'); ?> 
                    <div class="row">
                        <!-- Left col -->
                        <div class="col-lg-7 connectedSortable">
                            <?php importModule('todo_list'); ?>
                        </div>
                        <div class="col-lg-5 connectedSortable">
                            <?php importModule('charts_graph'); ?>
                        </div>
                        <div class="col-lg-12">
                            <?php importModule('charts_report'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?php importModule('map_one'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php importModule('infobox_three'); ?>
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
