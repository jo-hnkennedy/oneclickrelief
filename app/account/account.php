<?php

include_once '../include/config.php';
include_once '../include/session.php';
include_once '../include/function.php';


//check user
if( !$user ) {
    header('Location: login.php');
}


//get campus id 
$campusId = get_campus_id( $user );

//set menu type
$menu_type = 'account';

//set dashboard
$page_name = 'account';
$page_title = 'Account';
$page_excerpt = 'Your account details';


//set account type menu
if( $account['type'] == 'district' || $account['type'] == 'campus' ) {
    $top_menu['account'] = $top_menu[ $account['type'] ];
}

?>
    <!DOCTYPE html>
    <html lang="en">
    
    <?php importModule('main_header'); ?> 

    <body id="page-top" class="loggedin hold-transition skin-<?php echo $account['layout']['skin_color']; ?> sidebar-mini">
        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <?php importModule('navbar_header'); ?>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php echo setMenu( $menu_type,  $top_menu, $page_name, $account ); ?>
                    </ul>
                </div>
            </div>
        </nav>
        <header class="main-header">
            <a href="#" class="logo"><?php echo ucwords($page_name); ?></a>
            <?php importModule('section_topnav'); ?>
        </header>
        <div class="wrapper">

            <?php importModule('sidebar_left'); ?> 
            <div class="content-wrapper">
                <?php importModule('content_header'); ?> 
                <section class="content p20 bg-white">
                    <div class="row">
                        <div id="account_details" class="text-center bg-white">
                            <div class="box bg-silver col-md-12">
                                <div class="box-content m10 p10">
                                    <h1 class="tag-title text-info">Account Details</h1>
                                    <hr>
                                </div>
                                <div class="col-md-6 bg-white">
                                    <div class="form-horizontal" role="form">
                                        <fieldset>
                                            <legend class="p20 block">Change Password</legend>
                                            <br>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="old_password">Old</label>
                                                <div class="col-sm-10">
                                                    <input type="password" name="old_password" placeholder="Old Password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="new_password">New</label>
                                                <div class="col-sm-10">
                                                    <input type="password" name="new_password" placeholder="New Password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="retype_password">Retype</label>
                                                <div class="col-sm-10">
                                                    <input type="password" name="retype_password" placeholder="Retype Password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="block middle p20">
                                                <a class="btn btn-success btn-lg plan-btn plan-app" href="#update">
                                                    <i class="icon-ok"></i> Update Password
                                                </a>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <hr class="primary wide">
                                    <div class="clear"></div>
                                </div>
                                <div class="col-md-6 bg-white">
                                    <div class="form-horizontal" role="form">
                                        <fieldset>
                                            <legend class="p20 block">Change Email Address</legend>
                                            <br>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="old_email">Old</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="old_email" placeholder="Old Email Address" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="new_email">New</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="new_email" placeholder="New Email Address" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="retype_email">Retype</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="retype_email" placeholder="Retype Email Address" class="form-control">
                                                </div>
                                            </div>
                                            <div class="block middle p20">
                                                <a class="btn btn-success btn-lg plan-btn plan-app" href="#update">
                                                    <i class="icon-ok"></i> Update Email Address
                                                </a>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <hr class="primary wide">
                                    <div class="clear"></div>
                                </div>
                                <div class="col-md-6 bg-white">
                                    <div class="form-horizontal" role="form">
                                        <fieldset>
                                            <legend class="p20 block">Change Account Names</legend>
                                            <br>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="user_name">Username</label>
                                                <div class="col-sm-10">
                                                    <input type="input" name="user_name" placeholder="Your Username" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="nick_name">Nickname</label>
                                                <div class="col-sm-10">
                                                    <input type="input" name="nick_name" placeholder="Your Nickname" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="display_name">Display</label>
                                                <div class="col-sm-10">
                                                    <input type="input" name="display_name" placeholder="Display Name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="block middle p20">
                                                <a class="btn btn-success btn-lg plan-btn plan-app" href="#update">
                                                    <i class="icon-ok"></i> Update Names
                                                </a>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <hr class="primary wide">
                                    <div class="clear"></div>
                                </div>
                                <div class="col-md-6 bg-white">
                                    <div class="form-horizontal" role="form">
                                        <fieldset>
                                            <legend class="p20 block">Send Notification Messages</legend>
                                            <br>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="user_name">Email</label>
                                                <div class="col-sm-10">
                                                    <select name="notify_email" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="nick_name">Website</label>
                                                <div class="col-sm-10">
                                                    <select name="notify_website" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="display_name">Mobile</label>
                                                <div class="col-sm-10">
                                                    <select name="notify_mobile" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="block middle p20">
                                                <a class="btn btn-success btn-lg plan-btn plan-app" href="#update">
                                                    <i class="icon-ok"></i> Update Notifications
                                                </a>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <hr class="primary wide">
                                    <div class="clear"></div>
                                </div>
                                <div class="col-md-12 bg-white">
                                    <div class="form-horizontal" role="form">
                                        <fieldset>
                                            <legend class="p20 block">Security Questions</legend>
                                            <br>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="education">Education</label>
                                                <div class="col-sm-4">
                                                    <select id="education" name="education" class="form-control">
                                                        <option value="" selected="selected">What's Your Education Level?</option>
                                                        <option value="Primary School">Primary School</option>
                                                        <option value="High School">High School</option>
                                                        <option value="College">College</option>
                                                        <option value="University">University</option>
                                                        <option value="PhD">PhD</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label" for="color">Color</label>
                                                <div class="col-sm-4">
                                                    <select id="color" name="color" class="form-control">
                                                      <option value="" selected="selected">What's Your Favorite Color?</option>
                                                      <option value="green">Green</option>
                                                      <option value="yellow">Yellow</option>
                                                      <option value="red">Red</option>
                                                      <option value="blue">Blue</option>
                                                      <option value="purple">Purple</option>
                                                      <option value="pink">Pink</option>
                                                      <option value="brown">Brown</option>
                                                      <option value="orange">Orange</option>
                                                      <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="value">Value</label>
                                                <div class="col-sm-4">
                                                    <select id="value" name="value" class="form-control">
                                                        <option value="" selected="selected">What Do You Value Most?</option>
                                                        <option value="Logic and Reason">Logic and Reason</option>
                                                        <option value="Compassion">Compassion</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label" for="interest">Interest</label>
                                                <div class="col-sm-4">
                                                    <select id="interest" name="interest" class="form-control">
                                                      <option value="" selected="selected">What Interest You The Most?</option>
                                                      <option value="What is real">What is real</option>
                                                      <option value="What is possible">What is possible</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="saying">Saying</label>
                                                <div class="col-sm-4">
                                                    <select id="saying" name="saying" class="form-control">
                                                      <option value="" selected="selected">What Saying Do You Prefer?</option>
                                                      <option value="Seeing is believing">Seeing is believing</option>
                                                      <option value="I think therefore I am">I think, therefore I am</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label" for="choice">Choice</label>
                                                <div class="col-sm-4">
                                                    <select id="choice" name="choice" class="form-control">
                                                      <option value="" selected="selected">What Choice Do You Like?</option>
                                                      <option value="To get things done and move on">To get things done and move on</option>
                                                      <option value="To leave your options open">To leave your options open</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label" for="analysis">Analysis</label>
                                                <div class="col-sm-4">
                                                    <select id="analysis" name="analysis" class="form-control">
                                                      <option value="" selected="selected">What Analysis Type Fits You?</option>
                                                      <option value="Take things at face value">Take things at face value</option>
                                                      <option value="Read between the lines">Read between the lines and look for underlying meaning</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label" for="understand">Understand</label>
                                                <div class="col-sm-4">
                                                    <select id="understand" name="understand" class="form-control">
                                                      <option value="" selected="selected">What's Better To Understand?</option>
                                                      <option value="The theory behind the solution to a problem">The theory behind the solution to a problem</option>
                                                      <option value="The application of the steps which solve the problem">The application of the steps which solve the problem</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <hr class="primary wide">
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
            <?php importModule('section_footer'); ?> 
            <?php importModule('sidebar_right'); ?> 
        </div>

        <?php importModule('main_footer'); ?> 

    </body>

    </html>
