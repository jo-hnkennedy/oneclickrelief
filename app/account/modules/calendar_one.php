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
<div class="box box-solid">
    <div class="box-header">
        <i class="fa fa-calendar"></i>
        <h3 class="box-title"><?php echo $po['title']; ?></h3>
        <!-- tools box -->
        <div class="pull-right box-tools">
            <!-- button with a dropdown -->
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bars"></i></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="#"><?php echo $po['add_text']; ?></a></li>
                    <li><a href="#"><?php echo $po['clear_text']; ?></a></li>
                    <li class="divider"></li>
                    <li><a href="#"><?php echo $po['view_text']; ?></a></li>
                </ul>
            </div>
            <?php if( $po['show_expand'] ) { ?>
            <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <?php } ?>
            <?php if( $po['show_remove'] ) { ?>
            <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            <?php } ?>
        </div>
        <!-- /. tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <!--The calendar -->
        <div id="calendar" style="width: 100%;"></div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-black">
        <div class="row">
            <div class="col-sm-6">
                <!-- Progress bars -->
                <?php foreach( $po['progress_left'] as $ps ) { ?>
                <div class="clearfix">
                    <span class="pull-left"><?php echo $ps[0]; ?></span>
                    <small class="pull-right"><?php echo $ps[1]; ?>%</small>
                </div>
                <div class="progress <?php echo $po['progress_style']; ?>">
                    <div class="progress-bar progress-bar-<?php echo $ps[2]; ?>" style="width: <?php echo $ps[1]; ?>%;"></div>
                </div>
                <?php } ?>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <?php foreach( $po['progress_right'] as $ps ) { ?>
                <div class="clearfix">
                    <span class="pull-left"><?php echo $ps[0]; ?></span>
                    <small class="pull-right"><?php echo $ps[1]; ?>%</small>
                </div>
                <div class="progress <?php echo $po['progress_style']; ?>">
                    <div class="progress-bar progress-bar-<?php echo $ps[2]; ?>" style="width: <?php echo $ps[1]; ?>%;"></div>
                </div>
                <?php } ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</div>