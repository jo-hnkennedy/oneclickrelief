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
    <div class="box-header">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">To Do List</h3>
        <div class="box-tools pull-right">
            <ul class="pagination pagination-sm inline">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
            </ul>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="todo-list">
            <li>
                <!-- drag handle -->
                <span class="handle">
                    <i class="fa fa-ellipsis-v"></i>
                    <i class="fa fa-ellipsis-v"></i>
                </span>
                <!-- checkbox -->
                <input type="checkbox" value="">
                <!-- todo text -->
                <span class="text">Make sure you get above a 70 in Physics</span>
                <!-- Emphasis label -->
                <small class="label label-danger"><i class="fa fa-clock-o"></i> 3 days</small>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                </div>
            </li>
            <li>
                <span class="handle">
                    <i class="fa fa-ellipsis-v"></i>
                    <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="">
                <span class="text">Don't miss more than 4 days of school</span>
                <small class="label label-primary"><i class="fa fa-clock-o"></i> 2 months</small>
                <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                </div>
            </li>
            <li>
                <span class="handle">
                    <i class="fa fa-ellipsis-v"></i>
                    <i class="fa fa-ellipsis-v"></i>
                </span>
                <input type="checkbox" value="">
                <span class="text">Check your messages and notifications</span>
                <small class="label label-default"><i class="fa fa-clock-o"></i> 2 months</small>
                <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                </div>
            </li>

        </ul>
    </div>

    <!-- /.box-body -->
    <div class="box-footer clearfix no-border m10">
        <button type="button" class="btn btn-default btn-sm pull-right"><i class="fa fa-plus"></i> All Done </button>
    </div>
</div>
