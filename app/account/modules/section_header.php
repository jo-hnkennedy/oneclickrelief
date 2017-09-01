<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}


//set section title
$section_title = 'Mesa onTime';

GLOBAL $page_options;
$po = $page_options;

?>
<section class="long header dashboard" id="dashboard">
    <div class="header-content">
        <div class="header-content-inner">
            <h1 class="homeHeading sr-text">Admin</h1>
            <hr>
            <p class="sr-text">Your lottery command and control center.</p>
            <a href="#checkme" class="btn btn-primary btn-xl page-scroll">Check My Numbers</a>
            <a href="#tools" class="btn btn-default btn-xl page-scroll">View All Tools</a>
        </div>
    </div>
</section>