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
<div class="container" id="upload_one">
  <div class="panel panel-default">
    <div class="panel-heading"><strong>Upload Files</strong></div>
    <div class="panel-body">

      <!-- Standar Form -->
      <h4>Select files from your computer</h4>
      <form action="" method="post" enctype="multipart/form-data" id="js-upload-form">
        <div class="form-inline">
          <div class="form-group">
            <input type="file" name="files[]" id="js-upload-files" class="m20" multiple>
          </div>
          <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Upload files</button>
        </div>
      </form>

      <!-- Drop Zone -->
      <h4>Or drag and drop files below</h4>
      <div class="upload-drop-zone" id="drop-zone">
        Just drag and drop files here
      </div>

      <!-- Progress Bar -->
      <div class="progress hide">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
          <span class="sr-only">0% Complete</span>
        </div>
      </div>

      <!-- Upload Finished -->
      <div class="js-upload-finished hide">
        <h3>Uploaded files</h3>
        <div class="list-group">
          <a href="#" class="list-group-item list-group-item-success"><span class="badge alert-success pull-right">Success</span>image-01.jpg</a>
          <a href="#" class="list-group-item list-group-item-success"><span class="badge alert-success pull-right">Success</span>image-02.jpg</a>
        </div>
      </div>
    </div>
  </div>
</div>