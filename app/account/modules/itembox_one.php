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
<div class="small_profile">
    <div class="col-md-4">
        <div class="well well-sm">
            <div class="media">
                <a class="thumbnail pull-left" href="#">
                    <img class="media-object" src="../img/money.jpg">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">First Last Name</h4>
                    <p><span class="label label-info">888 photos</span> <span class="label label-warning">150 followers</span></p>
                    <p>
                        <a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-comment"></span> Message</a>
                        <a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-heart"></span> Favorite</a>
                        <a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-ban-circle"></span> Unfollow</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>