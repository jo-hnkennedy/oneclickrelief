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
$page_name = 'admin';
$page_title = 'Admin';
$page_excerpt = 'Administrator Area';
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
                        <section class="col-lg-7 connectedSortable">
                            <?php importModule('charts_tabbed'); ?>
                            <?php importModule('chatbox_one'); ?>
                            <?php importModule('todo_list'); ?>
                            <?php importModule('email_quick'); ?>
                        </section>
                        <section class="col-lg-5 connectedSortable">
                            <?php importModule('map_two'); ?>
                            <?php importModule('charts_graph'); ?>
                            <?php importModule('calendar_one'); ?>
                        </section>
                        <?php importModule('section_header'); ?>
                        <?php importModule('user_tools'); ?>
                        <?php importModule('check_numbers'); ?>
                        <?php importModule('past_winners'); ?>
                        <?php importModule('generate_numbers'); ?>
                        <?php importModule('footer_contact'); ?>
                        <?php importModule('lottery_games'); ?>
                        <?php if( $account['type'] == 'admin' ) { ?>
                        <?php importModule('find_users'); ?>
                        <?php importModule('open_console'); ?>
                        <?php } ?>
                    </div>
                    <?php importModule('infobox_one'); ?>
                    <?php importModule('charts_report'); ?>
                    <div class="row">
                        <div class="col-md-8">
                            <?php importModule('map_one'); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php importModule('chatbox_two'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php importModule('members_list'); ?>
                                </div>
                            </div>
                            <?php importModule('table_orders'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php importModule('infobox_three'); ?>
                            <?php importModule('charts_pie'); ?>
                            <?php importModule('products_list'); ?>
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
