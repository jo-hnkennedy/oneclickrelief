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
<aside class="bg-dark" id="checkme">
    <div class="container text-center">
        <div class="call-to-action">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="section-heading sr-text">Check My Numbers</h2>
                <hr class="primary">
                <p class=" sr-text">Compare your numbers against all previous winning numbers.</p>
            </div>
            <div class="col-lg-12 text-center">
                <?php echo setNumbersInput( 'check_numbers', 'check_numbers', 'check_numbers'); ?>
                <?php echo setGamesMenu( 'select_game_check', 'selectGame', 'checkme' ); ?>
                <p class="small status text_status">
                    <br>
                </p>
                <div class="numbers_report bg-white hide" id="checkme_section">
                    <h2 class="section-heading">Simple Report</h2>
                    <div class="block text-center clear" id="checkme_results"></div>
                </div>
            </div>
        </div>
    </div>
</aside>
