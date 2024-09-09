<?php
@session_start();
$get_videos = $db->select("videos", array("language_id" => $siteLanguage, "video_section" => 1));
$i = 1;
while ($row_videos = $get_videos->fetch()) {
  $video_id = $row_videos->video_id;
  $video_name = $row_videos->video_title;
  $video_desc = $row_videos->video_desc;
  $video_section = $row_videos->video_section;
  $video_url = $row_videos->video_url;

?>
  <div class="carousel-item <?php echo $i; ?> <?php if ($i == 1) {
                                                echo 'active';
                                              } ?>">
    <div class="row align-items-center">
      <div class="col-md-6" style=" padding:1rem 0 0.6rem 1rem; text-align:center;">
        <iframe width="614" height="460" src="<?= $section_video_url_5; ?>" class="box-shadow-videos-section" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
      <div class="col-md-6 p-5">
        <h2 class="video_name_styling"><?= $video_name; ?>
          <!-- <span class="text-secondary"><b class="px-1">|</b>HÆRFEST</span> -->
        </h2>
        <div class="video_description_styling"><?= $video_desc; ?></div>
      </div>
    </div>
  </div>
<?php $i++;
} ?>