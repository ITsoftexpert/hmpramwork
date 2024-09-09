<?php 
  $get_videos = $db->select("videos",array("language_id" => $siteLanguage,"video_section" => 3));
  $i = 1;
  while($row_videos = $get_videos->fetch()){
    $video_id = $row_videos->video_id;
    $video_name = $row_videos->video_title;
    $video_desc = $row_videos->video_desc;
    $video_section = $row_videos->video_section;
    $video_url = $row_videos->video_url;
    
?>
<div class="carousel-item <?php echo $i;?> <?php if($i==1){ echo 'active'; } ?>">
  <div class="row align-items-center">
    <div class="col-md-5">
      <iframe width="100%" height="100%" src="<?= $video_url;?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div class="col-md-7">
      <label class="text-body-larger"><?= $video_name;?>
        <!-- <span class="text-secondary"><b class="px-1">|</b>HÆRFEST</span> -->
      </label>
      <div class="text-headline-smaller font-italic font-italic font-weight-bold">"<?= $video_desc; ?>"</div>
    </div>
  </div>
</div>
<?php $i++; }?>