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
<aside class="bg-dark" id="find_user">
    <div class="container text-center">
        <div class="call-to-action">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="section-heading sr-text">Find User Account</h2>
                <hr class="primary">
                <p class="sr-text">search for a user by email or username.</p>
            </div>
            <div class="col-lg-12 text-center">
                <form method="GET" action="search.php">
                    <div class="form-group middle col-lg-5">
                        <input type="text" name="user" value="" placeholder=".ie. JohnDoe" class="form-control input-lg inline w60">
                        <input type="submit" class="btn btn-primary btn-lg inline w30" value="Find User">
                    </div>
                </form>
            </div>
        </div>
    </div>
</aside>