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
<section class="bg-dark" id="contact">
    <div class="container">
        <div class="col-md-4 col-sm-12 text-center" id="comment">
            <div class="well">
                <h4>What is on your mind?</h4>
                <form class="input-group">
                    <input type="hidden" name="send" value="comment">
                    <input type="text" id="comment_text" class="form-control input-sm chat-input" name="comment" placeholder="Write your message here..." />
                    <div class="input-group hide user_comment">
                        <input type="text" id="comment_name" class="form-control input-sm w50 inline" name="name" placeholder="Name" />
                        <input type="email" id="comment_email" class="form-control input-sm w50 inline" name="email" placeholder="Email" />
                    </div>
                    <span class="input-group-btn" id="add_comment">     
                    <a href="#contact" class="btn btn-primary btn-sm page-scroll"><span class="glyphicon glyphicon-comment"></span> Add Comment</a>
                    </span>
                </form>
                <br>
                <div id="comment_status" class="text-center"></div>
                <hr>
                <ul id="comment_list" class="list-unstyled ui-sortable">
                    <strong class="pull-left primary-font">James</strong>
                    <small class="pull-right text-muted"><?php echo time_elapsed_string( date( "Y-m-d H:i:s", time()-5 ) ); ?></small>
                    </br>
                    <li class="ui-state-default text-left">This is the most awesome thing I've ever used for checking lottery numbers. I like the fact that it gives you a break down on why certain numbers fair out better than others.</li>
                    </br>
                    <strong class="pull-left primary-font">Taylor</strong>
                    <small class="pull-right text-muted"><?php echo time_elapsed_string( date( "Y-m-d H:i:s", time()-500 ) ); ?></small>
                    </br>
                    <li class="ui-state-default text-left">I don't play the lotto often, but when I do, it makes sense to check my numbers. I also like how I can save my numbers and get some pretty detailed reports to look at. </li>
                    </br>
                    <strong class="pull-left primary-font">Mitch</strong>
                    <small class="pull-right text-muted"><?php echo time_elapsed_string('2017-06-1 17:16:18'); ?></small>
                    </br>
                    <li class="ui-state-default text-left">If there was only a way to say thank you. I used this tool and won $500 playing the Pick3 game. I know not everyone wins but without this website, I probably wouldn't have. </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4 col-sm-12" id="message">
            <div class="form-area">
                <form role="form">
                    <input type="hidden" name="send" value="message">
                    <br style="clear:both">
                    <h3 style="margin-bottom: 25px; text-align: center;">Send Us Your Feedback</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" id="message_name" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="message_email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="message_mobile" name="mobile" placeholder="Mobile Number" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="subject" id="message_subject" required>
                            <option value="">Select A Subject </option>
                            <option value="Add A Game"> Request To Add A Game </option>
                            <option value="Add A Feature"> Request To Add A Feature </option>
                            <option value="User Account Problem"> Problems With User Account </option>
                            <option value="Agents Account Problem"> Problems With Agents Account </option>
                            <option value="Retailer Account Problem"> Problems With Retailer Account </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="message_text" type="textarea" id="feedback" placeholder="Message .." maxlength="140" rows="9"></textarea>
                        <span class="help-block"><p id="characterLeft" class="help-block "></p></span>
                    </div>
                    <button type="button" id="message_submit" name="submit" class="btn btn-primary pull-right m10">Submit Form</button>
                    <button type="button" id="message_clear" name="clear" class="btn btn-default pull-right  m10">Clear Form</button>
                    <div id="message_none"></div>
                </form>
            </div>
        </div>
        <div class="col-md-3">
            <div class="news-features">
                <a href="#carousel_blog" class="page-scroll">
                    <div class="poster"><img alt="" src="../img/money.jpg"></div>
                    <div class="caption white">Why now is always the right time to play.</div>
                </a>
            </div>
            <div class="news-features">
                <a href="#carousel_blog" class="page-scroll">
                    <div class="poster"><img alt="" src="../img/fetch.jpg"></div>
                    <div class="caption white">The secrets to winning the big lottery game.</div>
                </a>
            </div>
            <div class="news-features">
                <a href="#carousel_blog" class="page-scroll">
                    <div class="poster"><img alt="" src="../img/winning.jpg"></div>
                    <div class="caption white">How life changes after winning the big jackpot?</div>
                </a>
            </div>
        </div>
    </div>
</section>
