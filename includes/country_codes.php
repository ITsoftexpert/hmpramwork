<style>
.box-shadow-allcc{
	  /* box-shadow:0px 0px 5px gray ,inset 0px 0px 15px lightgray; */
      border:1px solid lightgray;
      height:45px !important;
      padding-top:7px;
	  border-radius: 5px 0 0 5px !important;
      font-size: 15px;
		}
</style>
<select name="country_code" class="form-control border-right-0 box-shadow-allcc">

	<?php 

	$get_countries = $db->select("countries");
	while($row_countries = $get_countries->fetch()){
	
	$id = $row_countries->id;
	$country = $row_countries->name;
	$code = $row_countries->code;

	if(!empty($code)){
	
	?>

	<option value="+<?= $code; ?>" <?= ($country == @$login_seller_country)?"selected":""; ?>><?= $country; ?> (+<?= $code ?>)</option>

	<?php } ?>

	<?php } ?>

</select>