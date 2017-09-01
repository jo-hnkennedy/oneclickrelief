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

<div id="user-promoting" class="promo_progress">
    <div class="row">
        <h2 class="text-center">Promotion Progress<br/><small>Get promoted when you perform.</small></h2>
        <hr />
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-toggle="tooltip" data-placement="top" title="Customers">
                    <span class="sr-only">20%</span>
                    <span class="progress-type">Customers</span>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-toggle="tooltip" data-placement="top" title="Agents">
                    <span class="sr-only">20% </span>
                    <span class="progress-type">Agents</span>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 20%;" data-toggle="tooltip" data-placement="top" title="Retailers">
                    <span class="sr-only">20%</span>
                    <span class="progress-type">Retailers</span>
                </div>
            </div>
            <div class="progress-meter">
                <div class="meter meter-left" style="width: 25%;"><span class="meter-text">Started</span></div>
                <div class="meter meter-left" style="width: 25%;"><span class="meter-text">Training</span></div>
                <div class="meter meter-right" style="width: 20%;"><span class="meter-text">Agent</span></div>
                <div class="meter meter-right" style="width: 30%;"><span class="meter-text">Broker</span></div>
            </div>
        </div>
    </div>
</div>