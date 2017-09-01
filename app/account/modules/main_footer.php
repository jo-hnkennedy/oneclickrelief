<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}



GLOBAL $page_title;
GLOBAL $page_name;
GLOBAL $scr_dpdr;

//set script file
$scr_file = $scr_dpdr . $page_name . '.js';

GLOBAL $page_options;
$po = $page_options;

//check for print
$print = ( isset( $_GET['print'] ) ) ? true : false;

?>


<?php if( $account['type'] != 'student' ) { ?>
<!-- Upload dialog -->
<div class="upload-modal modal fade" id="upload" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h2 class="modal-title" id="myModalLabel">Upload File</h2>
            </div>
            <div class="modal-body">
                <div class="text-center middle"> 
                    <h4 class="bold"> Select a group below to upload new data </h4>
                    <div class="w80 middle">If you are unsure about file type and layout, pleaes click the &quot;Sample Schema&quot; button next to each group for an example.<br><br></div>
                </div>
                <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title bold">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                        User Upload</a>
                      </h3>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse in">
                      <div class="panel-body">
                          <div class="table-responsive">
                            <table class="table-striped table-hover w100">
                                <tr>
                                    <td class="p10">
                                        <label class="btn btn-primary" for="district_admin_upload">
                                            <input id="district_admin_upload" type="file" style="display:none" onchange="$('#upload-file-info_district').html(this.files[0].name)">
                                            <i class="fa fa-plus-circle"></i> District Admin
                                        </label>
                                        <span class='label label-info' id="upload-file-info_district"></span>
                                    </td>
                                    <td class="p10">
                                        <a href="http://indeedeng.github.io/imhotep/files/nasa_19950801.tsv" target="_blank" class="upload-modal-link"><i class="fa fa-download"></i> Download Sample Schema</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p10">
                                        <label class="btn btn-primary" for="campus_admin_upload">
                                            <input id="campus_admin_upload" type="file" style="display:none" onchange="$('#upload-file-info_campus').html(this.files[0].name)">
                                            <i class="fa fa-plus-circle"></i> Campus Admin
                                        </label>
                                        <span class='label label-info' id="upload-file-info_campus"></span>
                                    </td>
                                    <td class="p10">
                                        <a href="http://indeedeng.github.io/imhotep/files/nasa_19950801.tsv" target="_blank" class="upload-modal-link"><i class="fa fa-download"></i> Download Sample Schema</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p10">
                                        <label class="btn btn-primary" for="student_upload">
                                            <input id="student_upload" type="file" style="display:none" onchange="$('#upload-file-info_student').html(this.files[0].name)">
                                            <i class="fa fa-plus-circle"></i> Student
                                        </label>
                                        <span class='label label-info' id="upload-file-info_student"></span>
                                    </td>
                                    <td class="p10">
                                        <a href="http://indeedeng.github.io/imhotep/files/nasa_19950801.tsv" target="_blank" class="upload-modal-link"><i class="fa fa-download"></i> Download Sample Schema</a>
                                    </td>
                                </tr>
                            </table>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title bold">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                        Transcripts</a>
                      </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                      <div class="panel-body">
                          <div class="table-responsive">
                            <table class="table-striped table-hover w100">
                                <tr>
                                    <td class="p10">
                                        <label class="btn btn-primary" for="transcript_upload">
                                            <input id="transcript_upload" type="file" style="display:none" onchange="$('#upload-file-info_transcript').html(this.files[0].name)">
                                            <i class="fa fa-plus-circle"></i> Transcript
                                        </label>
                                        <span class='label label-info' id="upload-file-info_transcript"></span>
                                    </td>
                                    <td class="p10">
                                        <a href="http://indeedeng.github.io/imhotep/files/nasa_19950801.tsv" target="_blank" class="upload-modal-link"><i class="fa fa-download"></i> Download Sample Schema</a>
                                    </td>
                                </tr>
                            </table>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title bold">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                        Schedules</a>
                      </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                      <div class="panel-body">
                          <div class="table-responsive">
                            <table class="table-striped table-hover w100">
                                <tr>
                                    <td class="p10">
                                        <label class="btn btn-primary" for="schedule_upload">
                                            <input id="schedule_upload" type="file" style="display:none" onchange="$('#upload-file-info_schedule').html(this.files[0].name)">
                                            <i class="fa fa-plus-circle"></i> schedule
                                        </label>
                                        <span class='label label-info' id="upload-file-info_schedule"></span>
                                    </td>
                                    <td class="p10">
                                        <a href="http://indeedeng.github.io/imhotep/files/nasa_19950801.tsv" target="_blank" class="upload-modal-link"><i class="fa fa-download"></i> Download Sample Schema</a>
                                    </td>
                                </tr>
                            </table>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New group dialog -->
<div class="new-group-modal modal fade" id="new-group" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h2 class="modal-title" id="myModalLabel">New Group</h2>
            </div>
            <div class="modal-body">
                <div class="text-center middle"> 
                    <h4 class="bold"> Add a new group section</h4>
                    <div class="w80 middle">When done, press the &quot;Create Group&quot; button. Your group will appear in the left sidebar. <br><br></div>
                </div>
                <div class="form-horizontal bg-silver p20">
                    <fieldset>
                        <script type="text/javascript">
                            function addGroup() {
                                if( $('#group_name').val() != '' ){ 
                                    var name = $('#group_name').val();
                                    var html = '<li class=""><a href="groups.php?filter='+name+'" class="page-scroll"><i class="fa fa-circle-o"></i> '+name+'</a></li>';
                                    var link = $('ul.sidebar-menu li.treeview a[href*="groups.php"]');
                                    var list = link.siblings('ul').append(html);
                                    //open menu
                                    if( !list.parent().hasClass('menu-open') ) {
                                        list.parent().trigger('click');
                                    }
                                    //close dialog
                                    $('#new-group').modal('hide');
                                }
                            }
                        </script>
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="name">Group Name</label>  
                          <div class="col-md-9">
                            <input id="group_name" name="name" type="text" placeholder="i.e STEM" class="form-control input-md inline w50">
                            <button id="create_group" name="create_group" class="btn btn-primary inline" onclick="addGroup()">Create Group</button>
                          </div>
                          <div class="clear"></div>
                        </div>
                        <div class="form-group">
                          <div class="text-center middle block bold">Upload Multiple Groups</div>  
                          <div class="col-md-12">
                            <a href="http://indeedeng.github.io/imhotep/files/nasa_19950801.tsv" target="_blank" class="upload-modal-link text-center middle block w100"><i class="fa fa-download"></i> Download Sample Schema</a>
                            <?php importModule('upload_one'); ?> 
                          </div>
                          <div class="clear"></div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New group dialog -->
<div class="feedback-modal modal fade" id="feedback" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h2 class="modal-title" id="myModalLabel">Give Your Feedback</h2>
            </div>
            <div class="modal-body">
                <div class="text-center middle"> 
                    <h4 class="bold"> Give us your awesome feedback</h4>
                    <div class="w80 middle"> Help make Mesa onTime even better with your honest feedback! <br><br></div>
                </div>
                <div class="form-horizontal bg-silver p20">
                    <div class="form-area">
                        <form role="form">
                            <input type="hidden" name="send" value="feedback">
                            <h3 style="margin-bottom: 25px; text-align: center;">Send Us Your Feedback</h3>
                            <div class="form-group">
                                <input type="text" class="form-control" id="feedback_name" name="name" placeholder="Name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="feedback_email" name="email" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="feedback_text" type="textarea" placeholder="feedback .." maxlength="140" rows="6"></textarea>
                                <span class="help-block"><p id="characterLeft" class="help-block "></p></span>
                            </div>
                            <button type="button" id="feedback_submit" name="submit" class="btn btn-primary pull-right m10">Submit Form</button>
                            <button type="button" id="feedback_clear" name="clear" class="btn btn-default pull-right  m10">Clear Form</button>
                            <div id="feedback_none"></div>
                        </form>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php } ?>


<!-- /.control-sidebar -->
<div class="control-sidebar-bg"></div>
<!-- jQuery 2.2.3 -->
<script src="../vendor/jquery/jquery.min.js"></script>
<?php if($print) { ?>
<!-- Print Page -->
<script src="../js/print.js"></script>
<?php } ?>
<!-- jQuery UI 1.11.4 -->
<script src="../vendor/jquery/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../vendor/datatables/pack/datatables.min.js"></script>
<script src="../vendor/datatables/dataTables.bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="../vendor/raphael/raphael-min.js"></script>
<!-- jQuery -->
<script src="../vendor/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="../vendor/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../vendor/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../vendor/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="../vendor/moment/moment.2.11.2.min.js"></script>
<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../vendor/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../vendor/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../vendor/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../js/app.min.js"></script>
<!-- Set Chart JS -->
<script src="../vendor/chartjs/Chart.min.js"></script>
<!-- Admin Dashboard 1 -->
<script src="../js/dashboard.js"></script>
<!-- Admin Dashboard 2 -->
<script src="../js/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../js/demo.js"></script>
<!-- Plugin JavaScript -->
<script src="../vendor/jquery/jquery.easing.min.js"></script>
<!-- jQuery -->
<script src="../vendor/scrollreveal/scrollreveal.min.js"></script>
<!-- jQuery -->
<script src="../vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<!-- Theme JavaScript -->
<script src="../js/creative.js"></script>
<script src="../js/wysiwyg.js"></script>
<script src="../js/login.js"></script>
<script src="../js/index.js"></script>
<script src="../js/account.js"></script>
<script src="../js/charts.js"></script>

<?php if(  is_file( $scr_file ) ) { ?>
<script src="<?php echo $scr_file; ?>"></script>
<?php } ?>
