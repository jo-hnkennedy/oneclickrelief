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
GLOBAL $session;
GLOBAL $page_options;
$po = $page_options;

//set placeholder text
$placeholder = array(
    'public'    => 'resources',
    'student'   => 'courses',
    'parent'   => 'grades',
    'campus'    => 'students',
    'district'  => 'campuses',
    'admin'     => 'users'
);
//set placeholder
$po['placeholder'] = $placeholder[ $account['type'] ];

?>
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!--
                    <?php if( !empty( $account['profile']['photo'] ) ) { ?>
                    <img src="<?php echo  $account['profile']['photo']; ?> ../img/user2-160x160.jpg" class="user-image" alt="User Image">
                    <?php } else { ?>
                    <img src="../img/user2-160x160.jpg" class="user-image" alt="User Image">
                    <?php } ?>
                    -->
                    <span class="hidden-xs"><?php echo $user['display']; ?></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="../img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        <p>
                            <?php echo $user['display']; ?> -
                            <spann><?php echo $user['email']; ?></spann><br>
                            <small>Account Type: <?php echo $account['type']; ?></small><br>
                            <small>Member since <?php echo time_elapsed_string( $account['created'] ); ?></small>
                        </p>
                        <br>
                        <br>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="account.php" class="btn btn-default btn-flat">Edit Account</a>
                        </div>
                        <div class="pull-right">
                            <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
            <li>
                <form action="#" method="get" class="sidebar-form top-search inline w20" id="SearchTopNav">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search <?php echo $po['placeholder']; ?> ...">
                        <span class="input-group-btn">
                            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</nav>
