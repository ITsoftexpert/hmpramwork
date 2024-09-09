<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
}else{
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-picture-o"></i> Videos </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Add New Video</li>
                </ol>
            </div>
        </div>
    </div>

</div>


<div class="container">


<div class="row">
<!--- 2 row Starts --->
<div class="col-lg-12">
    <!--- col-lg-12 Starts --->
<?php 
$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");
if(is_array($form_errors)){
?>
<div class="alert alert-danger"><!--- alert alert-danger Starts --->
<ul class="list-unstyled mb-0">
<?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
<li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
<?php } ?>
</ul>
</div><!--- alert alert-danger Ends --->
<?php } ?>
    <div class="card">
        <!--- card Starts --->
        <div class="card-header">
            <!--- card-header Starts --->
            <h4 class="h4">
                Insert New Video for Homepage<br>
                <small>This video will show in homepage</small>
            </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
            <!--- card-body Starts --->
            <form action="" method="post" enctype="multipart/form-data">
                <!--- form Starts --->
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <label class="col-md-3 control-label"> Section : </label>
                    <div class="col-md-6">
                        <select class="form-control" name="video_section">
                            <option value="1">Homepage</option>
                            <option value="2">Buyer</option>
                            <option value="3">Seller</option>
                        </select>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Video Title : </label>
                    <div class="col-md-6">
                        <input type="text" name="video_title" class="form-control">
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Video Description : </label>
                    <div class="col-md-6">
                        <textarea name="video_desc" class="form-control"></textarea>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Video Url : </label>
                    <div class="col-md-6">
                        <input type="text" name="video_url" class="form-control"><br>
                        <span class="text-muted">Include https://www.youtube.com/embed/abcdefgh </span>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        <input type="submit" name="submit" class="btn btn-success form-control" value="Add Video">
                    </div>
                </div>
                <!--- form-group row Ends --->
            </form>
            <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
    </div>
    <!--- card Ends --->
</div>
<!--- col-lg-12 Ends --->
</div>
<!--- 2 row Ends --->

</div><!--- Container Ends --->

<?php

if(isset($_POST['submit'])){

   $rules = array(
    "video_title" => "required",
    "video_desc" => "required",
    "video_url" => "required",
    "video_section" => "required");

   $val = new Validator($_POST,$rules);

   if($val->run() == false){

      Flash::add("form_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open('index?insert_slide','_self');</script>";

   }else{

      $video_title = $input->post('video_title');
      $video_desc = $input->post('video_desc');
      $video_url = $input->post('video_url');
      $video_section = $input->post('video_section');;


     $insert_slide = $db->insert("videos",array("language_id" => $adminLanguage,"video_title" => $video_title, "video_desc" => $video_desc,"video_url" => $video_url,"video_section"=>$video_section));

     if($insert_slide){
        $insert_id = $db->lastInsertId();
        $insert_log = $db->insert_log($admin_id,"videos",$insert_id,"inserted");
        echo "<script>alert('One Video has been Inserted.');</script>";
        echo "<script>window.open('index?view_videos','_self');</script>";
     }

  }

}

?>

<?php } ?>