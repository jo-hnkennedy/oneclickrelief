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
<!-- Info Boxes Style 2 -->
<div class="info-box bg-yellow">
    <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">Inventory</span>
        <span class="info-box-number">5,200</span>
        <div class="progress">
            <div class="progress-bar" style="width: 50%"></div>
        </div>
        <span class="progress-description">
                                50% Increase in 30 Days
                              </span>
    </div>
    <!-- /.info-box-content -->
</div>
<!-- /.info-box -->
<div class="info-box bg-green">
    <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">Mentions</span>
        <span class="info-box-number">92,050</span>
        <div class="progress">
            <div class="progress-bar" style="width: 20%"></div>
        </div>
        <span class="progress-description">
                                20% Increase in 30 Days
                              </span>
    </div>
    <!-- /.info-box-content -->
</div>
<!-- /.info-box -->
<div class="info-box bg-red">
    <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">Downloads</span>
        <span class="info-box-number">114,381</span>
        <div class="progress">
            <div class="progress-bar" style="width: 70%"></div>
        </div>
        <span class="progress-description">
                                70% Increase in 30 Days
                              </span>
    </div>
    <!-- /.info-box-content -->
</div>
<!-- /.info-box -->
<div class="info-box bg-aqua">
    <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">Direct Messages</span>
        <span class="info-box-number">163,921</span>
        <div class="progress">
            <div class="progress-bar" style="width: 40%"></div>
        </div>
        <span class="progress-description">
                                40% Increase in 30 Days
                              </span>
    </div>
    <!-- /.info-box-content -->
</div>
<!-- /.info-box -->
