<?php
//error_reporting(0);
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{


?>
<div class="breadcrumbs">

  <div class="col-sm-4">
  <div class="page-header float-left">
    <div class="page-title">
      <h1><i class="menu-icon fa fa-picture-o"></i> Videos</h1>
    </div>
  </div>
  </div>
  <div class="col-sm-8">
  <div class="page-header float-right">
    <div class="page-title">
      <ol class="breadcrumb text-right">
        <li class="active">
          <a href="index?insert_video" class="btn btn-success">
            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add New Video</span>
          </a>
        </li>
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

<div class="card">
<!--- card Starts --->

<div class="card-header">
  <!--- card-header Starts --->
  <h4 class="h4">
    View Videos <br><small>These videos will show up in homepage and buyer/seller dashboards</small>
  </h4>
</div>
<!--- card-header Ends --->

<div class="card-body">
    <!--- card-body Starts --->

    <div class="row">
        <!--- row Starts --->

        <?php

        $get_videos = $db->select("videos",array("language_id" => $adminLanguage));
        while($row_videos = $get_videos->fetch()){
        $video_id = $row_videos->video_id;
        $video_name = $row_videos->video_title;
        $video_desc = $row_videos->video_desc;
        $video_section = $row_videos->video_section;
        $video_url =  $row_videos->video_url;

        
        $get_slides = $db->select("slider",array("language_id" => $adminLanguage));
        while($row_slides = $get_slides->fetch()){
        $slide_id = $row_slides->slide_id;
        $slide_name = $row_slides->slide_name;        
        $slide_image = getImageUrl("slider",$row_slides->slide_image);
        $s_extension = pathinfo($slide_image, PATHINFO_EXTENSION);

        //$video_image = getImageUrl("slider",$row_videos->slide_image); 
        //$s_extension = pathinfo($slide_image, PATHINFO_EXTENSION);

        ?>

            <div class="col-lg-4 col-md-6 mb-lg-3 mb-3">
                <!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Starts --->

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header">

                        <h5 class="h5 text-center"><?= $video_name; ?> </h5>

                    </div>

                    <div>
                        <video class="img-fluid" width="100%" controls>
                          <source src="<?php echo  $video_url ?>;" type="video/mp4">
                        </video>
                    </div>

                    <div class="card-body"><!--- card-body Starts --->
                      
                      <?php if($s_extension == "mp4" or $s_extension == "webm" or $s_extension == "ogg" or $s_extension == ""){ ?>
                        <video class="img-fluid" controls>
                          <source src="<?= $slide_image; ?>" type="video/mp4">
                        </video>
                      <?php }else{ ?>
                        <img src="<?= $slide_image; ?>" class="img-fluid">
                      <?php } ?>

                      <p><?= $video_desc;?></p>

                    </div><!--- card-body Ends --->

                    <div class="card-footer"><!--- card-footer Starts --->

                        <a href="index?delete_video=<?= $video_id; ?>" class="float-left btn btn-danger" title="Delete">

                            <i class="fa fa-trash text-white"></i> 

                        </a>&nbsp;&nbsp;&nbsp;
                        <a href="index?edit_video=<?= $video_id; ?>" class="float-left btn btn-success" title="edit" style="margin-left: 10px;">
                            <i class="fa fa-pencil text-white"></i> 
                        </a>
                        <a href="javascript:void(0);"  class="float-right btn btn-success">
                          <?php
                            if($video_section==1){
                              $video_section = 'Homepage';
                            }
                            elseif($video_section==2){
                              $video_section = 'Buyer';
                            }
                            else{
                              $video_section = 'Seller';
                            }
                            echo $video_section;
                          ?>
                        </a>
                        

                        <div class="clearfix"></div>

                    </div><!--- card-footer Ends --->

                </div><!--- card Ends --->

            </div><!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Ends --->

            <?php } } ?>

    </div><!--- row Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php } ?>