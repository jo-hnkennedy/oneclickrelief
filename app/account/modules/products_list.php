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
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Recently Added Products</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="products-list product-list-in-box">
            <li class="item">
                <div class="product-img">
                    <img src="../img/default-50x50.gif" alt="Product Image">
                </div>
                <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Samsung TV
                                  <span class="label label-warning pull-right">$1800</span></a>
                    <span class="product-description">
                                      Samsung 32" 1080p 60Hz LED Smart HDTV.
                                    </span>
                </div>
            </li>
            <!-- /.item -->
            <li class="item">
                <div class="product-img">
                    <img src="../img/default-50x50.gif" alt="Product Image">
                </div>
                <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Bicycle
                                  <span class="label label-info pull-right">$700</span></a>
                    <span class="product-description">
                                      26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                    </span>
                </div>
            </li>
            <!-- /.item -->
            <li class="item">
                <div class="product-img">
                    <img src="../img/default-50x50.gif" alt="Product Image">
                </div>
                <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Xbox One <span class="label label-danger pull-right">$350</span></a>
                    <span class="product-description">
                                      Xbox One Console Bundle with Halo Master Chief Collection.
                                    </span>
                </div>
            </li>
            <!-- /.item -->
            <li class="item">
                <div class="product-img">
                    <img src="../img/default-50x50.gif" alt="Product Image">
                </div>
                <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">PlayStation 4
                                  <span class="label label-success pull-right">$399</span></a>
                    <span class="product-description">
                                      PlayStation 4 500GB Console (PS4)
                                    </span>
                </div>
            </li>
            <!-- /.item -->
        </ul>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">
        <a href="javascript:void(0)" class="uppercase">View All Products</a>
    </div>
    <!-- /.box-footer -->
</div>
