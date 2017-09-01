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
<div class="coupon_box col-md-6">
    <div class="panel panel-success coupon">
        <div class="panel-heading" id="head">
            <div class="panel-title" id="title">
                <i class="fa fa-github fa-2x"></i>
                <span class="hidden-xs">Automatic Transmission Service</span>
                <span class="visible-xs">Automatic Transmission Service</span>
            </div>
        </div>
        <div class="panel-body">
            <img src="http://i.imgur.com/e07tg8R.png" class="coupon-img img-rounded">
            <div class="col-md-9">
                <ul class="items">
                    <li>Add up to 5 quarts of motor oil (per specification)</li>
                    <li>Complimentary multi-point inspection</li>
                    <li>Drain and refill trnasmission fluid</li>
                    <li>System inspection</li>
                </ul>
            </div>
            <div class="col-md-3">
                <div class="offer text-success">
                    <span class="usd"><sup>$</sup></span>
                    <span class="number">39</span>
                    <span class="cents"><sup>95</sup></span>
                </div>
            </div>
            <div class="col-md-12">
                <p class="disclosure">Using Genuine Oil Filter and multigrade oil up to vehicle specification. Lube as necessary. Ester Oil or Synthetic available at additional cost. Excludes hazardous waste fee, tax and shop supplies, where applicable. Offer not valid with previous charges or with any other offers or specials. Customer must offer at time of write-up. No cash value.</p>
            </div>
        </div>
        <div class="panel-footer">
            <div class="coupon-code">
                Code: GBWO2
                <span class="print">
                            <a href="#" class="btn btn-link"><i class="fa fa-lg fa-print"></i> Print Coupon</a>
                        </span>
            </div>
            <div class="exp">Expires: Sep 30, 2016</div>
        </div>
    </div>
</div>
