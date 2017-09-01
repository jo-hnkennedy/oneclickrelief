<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}


GLOBAL $page_options;
$po = $page_options;

?>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> <?php echo Date('d.m.y'); ?>
    </div>
    <strong>Copyright &copy; <?php echo Date('Y'); ?> <a href="/">Mesa Digital, LLC</a>.</strong> All rights reserved.
</footer>