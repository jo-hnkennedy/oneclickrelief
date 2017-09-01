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
<div id="profile-widget" class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/flashmurphy/128.jpg">
            </a>
            <div class="media-body">
                <h2 class="media-heading">John Raymons</h2> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="btn-group btn-group-justified">
            <a class="btn btn-default" role="button"><i class="fa fa-eye"></i> 172</a>
            <a class="btn btn-default" role="button"><i class="fa fa-comment"></i> 34</a>
            <a class="btn btn-default highlight" role="button"><i class="fa fa-heart"></i> 210</a>
        </div>
    </div>
</div>

<div id="user-widget" class="list-group">

    <a href="#" class="list-group-item">
        <i class="fa fa-user fa-lg pull-right"></i>
        <p class="list-group-item-text">Edit user</p>
    </a>
    <a href="#" class="list-group-item">
        <i class="fa fa-bar-chart-o fa-lg pull-right"></i>
        <p class="list-group-item-text">Web statistics</p>
    </a>
    <a href="#" class="list-group-item">
        <i class="fa fa-wrench fa-lg pull-right"></i>
        <p class="list-group-item-text">Upload settings</p>
    </a>
    <a href="#" class="list-group-item">
        <i class="fa fa-calendar fa-lg pull-right"></i>
        <p class="list-group-item-text">Events</p>
    </a>
</div>