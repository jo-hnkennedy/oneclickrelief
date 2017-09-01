<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}


GLOBAL $user;
GLOBAL $account;
GLOBAL $page_name;
GLOBAL $page_options;
$po = $page_options;

?>
<section id="tools">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading sr-text">Advanced Tools</h2>
                <hr class="primary">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-percent text-primary sr-icons"></i>
                    <h3>Check My Numbers</h3>
                    <p class="text-muted sr-text">Get more insight about your lottery numbers with an advanced report.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="#checkme" class="btn btn-default btn-xl page-scroll bg-silver">Check My Numbers</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-cogs text-primary sr-icons"></i>
                    <h3>Generate Numbers</h3>
                    <p class="text-muted sr-text">Generate better numbers using our proprietary number crunching system.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="#generate" class="btn btn-default btn-xl page-scroll bg-silver">Generate Numbers</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-th-list text-primary sr-icons"></i>
                    <h3>See Winning Numbers</h3>
                    <p class="text-muted sr-text">View a history of all past winning numbers for all supported games.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="#winners" class="btn btn-default btn-xl page-scroll bg-silver">See Past Winners</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-life-ring text-primary sr-icons"></i>
                    <h3>Save My Numbers</h3>
                    <p class="text-muted sr-text">Keep all your lottery numbers in one place and check them whenever.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="/account/saved.php" class="btn btn-default btn-xl page-scroll bg-silver">View Saved Numbers</a>
                    </p>
                </div>
            </div>
            <div class="clear block"><br></div>
            <?php if( $page_name == 'pricing' || ( $account && $account['type'] != 'user' ) ) { ?>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-user-circle text-primary sr-icons"></i>
                    <h3>Build Number Profile</h3>
                    <p class="text-muted sr-text">Use all the numbers that are personal to you in ways that help you win.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="/account/profile.php" class="btn btn-default btn-xl page-scroll bg-silver">Build Number Profile</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-line-chart text-primary sr-icons"></i>
                    <h3>See Lottery Trends</h3>
                    <p class="text-muted sr-text">See charts of trends that show the many changes in lottery games over time.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="/account/trends.php" class="btn btn-default btn-xl page-scroll bg-silver">See Lottery Trends</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-search-plus text-primary sr-icons"></i>
                    <h3>Get Lottery Insights</h3>
                    <p class="text-muted sr-text">Get the big picture on your numbers and how they compare with past winners. </p>
                    <hr>
                    <p class="text-center middle">
                        <a href="/account/insights.php" class="btn btn-default btn-xl page-scroll bg-silver">Get Lottery Insights</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="service-box">
                    <i class="fa fa-4x fa-mobile text-primary sr-icons"></i>
                    <h3>Text Message Reports</h3>
                    <p class="text-muted sr-text">Get a simple report sent to your mobile phone when you text to check numbers.</p>
                    <hr>
                    <p class="text-center middle">
                        <a href="/account/messages.php#alerts" class="btn btn-default btn-xl page-scroll bg-silver">View SMS Alerts</a>
                    </p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>