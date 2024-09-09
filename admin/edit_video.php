<?php

@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

if(isset($_GET['edit_video'])){
    $edit_id = $input->get('edit_video');
    $edit_slide = $db->select("videos",array('video_id' => $edit_id));
    if($edit_slide->rowCount() == 0){
      echo "<script>window.open('index?dashboard','_self');</script>";
    }
    $row_edit = $edit_slide->fetch();
    $s_title = $row_edit->video_title;
    $s_desc = $row_edit->video_desc;
    // $s_image = $row_edit->slide_image;
    $s_url = $row_edit->video_url;
    $video_section = $row_edit->video_section;
    $s_extension = pathinfo($s_image, PATHINFO_EXTENSION);
}

?>

<div class="breadcrumbs">
<div class="col-sm-4">
<div class="page-header float-left">
<div class="page-title">
    <h1><i class="menu-icon fa fa-picture-o"></i> Video</h1>
</div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
<div class="page-title">
    <ol class="breadcrumb text-right">
        <li class="active">Edit Video</li>
    </ol>
</div>
</div>
</div>
</div>

<div class="container">

<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
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
<div class="card"><!--- card Starts --->
    <div class="card-header"><!--- card-header Starts --->
        <h4 class="h4">Edit Video</h4>
    </div><!--- card-header Ends --->
    <div class="card-body"><!--- card-body Starts --->
        <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
            <input type="hidden" name="video_section" value="<?= $video_section; ?>">
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Video Title : </label>
                <div class="col-md-6">
                    <input type="text" name="video_title" class="form-control" value="<?= $s_title; ?>">
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Video Description : </label>
                <div class="col-md-6">
                    <textarea name="video_desc" class="form-control"><?= $s_desc; ?></textarea>
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Video Url : </label>
                <div class="col-md-6">
                    <input type="text" name="video_url" class="form-control" value="<?= $s_url; ?>">
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row">
                <!--- form-group row Starts --->
                <label class="col-md-3 control-label"></label>
                <div class="col-md-6">
                    <input type="submit" name="update" class="btn btn-success form-control" value="Update Video">
                </div>
            </div>
            <!--- form-group row Ends --->
        </form><!--- form Ends --->
    </div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div><!--- container Ends -->


<?php

if(isset($_POST['update'])){
   
   // $rules = array(
   // "slide_name" => "required",
   // "slide_desc" => "required",
   // "slide_url" => "required");

   // $val = new Validator($_POST,$rules);

   // if($val->run() == false){

   //   Flash::add("form_errors",$val->get_all_errors());
   //   Flash::add("form_data",$_POST);
   //   echo "<script> window.open(window.location.href,'_self');</script>";

   // }else{

      $video_title = $input->post('video_title');
      $video_desc = $input->post('video_desc');
      $video_url = $input->post('video_url');

      $slide_image = $_FILES['slide_image']['name'];  
      $tmp_slide_image = $_FILES['slide_image']['tmp_name'];   

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp','mp4','ogg','webm');
      $file_extension = pathinfo($slide_image, PATHINFO_EXTENSION);

      // if(!in_array($file_extension,$allowed) & !empty($slide_image)){
      //    echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
      // }else{

         // if(empty($slide_image)){
         //    $slide_image = $s_image;
         // }else{
         //    uploadToS3("slides_images/$slide_image",$tmp_slide_image);
         //    $isS3 = $enable_s3;
         // }

         $update_slide = $db->update("videos",array("video_title"=>$video_title,"video_desc"=>$video_desc,"video_url"=>$video_url,"video_section"=>$video_section),array("video_id" => $edit_id));

         if($update_slide){
            $insert_log = $db->insert_log($admin_id,"videos",$edit_id,"updated");
            echo "<script>alert('One Video has been Updated.');</script>";
            echo "<script>window.open('index?view_videos','_self');</script>";
         }

      // }
   
   // }

}

?>

<?php } ?>