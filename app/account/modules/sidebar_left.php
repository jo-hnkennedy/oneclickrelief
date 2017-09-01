<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}


GLOBAL $user;
GLOBAL $account;
GLOBAL $left_menu;
GLOBAL $page_options;
$po = $page_options;

//set user menu
$user_menu = get_sidebar_menu( $account['type'] );
//set current request uri
$request_uri = $_SERVER['REQUEST_URI'];
?>
<!-- imported sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <!--
            <div class="pull-left image">
                <?php if( !empty( $account['profile']['photo'] ) ) { ?>
                <img src="<?php echo  $account['profile']['photo']; ?> ../img/user2-160x160.jpg" class="img-circle" alt="User Image">
                <?php } else { ?>
                <img src="../img/user2-160x160.jpg" class="img-circle" alt="User Image">
                <?php } ?>
            </div>
            -->
            <div class="pull-left info">
                <p><?php echo $user['display']; ?></p>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php 
                foreach( $user_menu as $name => $menu ) { 
                    //set label, path, icon and subnav
                    $label = $menu[0];
                    $link = $name . $menu[1]; 
                    $icon = $menu[2];
                    $active = '';
                    $subnav = ( isset($menu['subnav']) ) ? $menu['subnav'] : false;
                    //check if active
                    if( strpos( $request_uri, $link ) !== false ) {
                        $active = 'active';
                    }
            ?>
            <li class="<?php echo $active; ?> treeview">
                <a href="<?php echo $link; ?>">
                    <i class="fa fa-<?php echo $icon; ?>"></i> <span><?php echo $label; ?></span>
                    <?php if( is_array( $subnav) ) { ?>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    <?php } ?>
                </a>
                <?php if( is_array( $subnav) ) { ?>
                <ul class="treeview-menu">
                    <?php 
                        foreach( $subnav as $sub ) { 
                            //set sub link, label
                            $sub_link = $sub[0];
                            $sub_label = $sub[1];
                            $sub_icon = $sub[2];
                            $sub_active = '';
                            //check if sublink is hash
                            if( substr( $sub_link, 0, 1) == '#' ) {
                                $sub_link = $link . $sub_link;
                            }
                            //chekc if icon empty
                            if( empty( $sub_icon ) ) {
                                $sub_icon = 'circle-o';
                            }
                            //check if active
                            if( strpos( $request_uri, $sub_link ) !== false ) {
                                $sub_active = 'active';
                            }
                    ?>
                    <li class="<?php echo $sub_active; ?>">
                        <a href="<?php echo $sub_link; ?>" class="page-scroll">
                            <i class="fa fa-<?php echo $sub_icon; ?>"></i> 
                            <?php echo $sub_label; ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>

        <?php if( $account['type'] != 'student' ) { ?>
        <a class="btn btn-primary m20" href="#add-group" data-toggle="modal" data-target="#new-group" data-dismiss="modal"> 
            <i class="fa fa-plus-circle"></i>  New Group
        </a>
        <?php } ?>
        <a class="btn btn-secondary m20" href="#feedback" data-toggle="modal" data-target="#feedback" data-dismiss="modal"> 
            <i class="fa fa-comment-o"></i> Feedback
        </a>
    </section>
    <!-- /.sidebar -->
</aside>