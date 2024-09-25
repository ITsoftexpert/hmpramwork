<?php
@session_start();
require_once("includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
  echo "<script>window.open('login','_self')</script>";
}

// HANDLE SUBMIT
// Change password
if (isset($_POST['submit_change_password'])) {
  $password = $input->post('password');
  $password_confirm = $input->post('password_confirm');
  if (strlen($password) < 8) {
    echo "<script>
    swal({
      type: 'warning',
      text: 'Invalid Password! Password must be 8 characters.',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
      window.open('settings?profile_settings','_self')
    });
    </script>";
    exit();
  } else if (!ctype_alnum($password)) {
    echo "<script>
      swal({
        type: 'warning',
        text: 'Invalid Password! Password must be alphanumeric characters.',
        timer: 3000,
        onOpen: function(){
          swal.showLoading()
        }
      }).then(function(){
        window.open('settings?profile_settings','_self')
      });
      </script>";
    exit();
  } else if ($password != $password_confirm) {
    echo "<script>
    swal({
      type: 'warning',
      text: '{$lang['alert']['dont_match']}',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
      window.open('settings?profile_settings','_self')
    });
    </script>";
    exit();
  }

  $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

  $update_password = $db->update("sellers", array("seller_pass" => $encrypted_password), array("seller_id" => $seller_id));
  if ($update_password) {
    echo "
		<script>
      swal({
        type: 'success',
        text: '{$lang['alert']['successfully_reset_pass']}',
        timer: 5000,
        onOpen: function(){
			    swal.showLoading()
			  }
        }).then(function(){
          // Read more about handling dismissals
          window.open('settings?profile_settings','_self')
        });
        </script>";
    exit();
  }
}
// Profile form
if (isset($_POST['submit'])) {
  // var_dump($_POST);
  // exit;

  $rules = array(
    "seller_name" => "required",
    "seller_country" => "required",
    "seller_language" => "required"
  );

  $messages = [
    "seller_name" => "Full Name Is required.",
    "seller_country" => "Country Is Required.",
    "seller_language" => "Main Conversational Language Is Required."
  ];

  $val = new Validator($_POST, $rules, $messages);

  if ($val->run() == false) {
    Flash::add("form_errors", $val->get_all_errors());
    Flash::add("form_data", $_POST);
    echo "<script> window.open('settings?profile_settings', '_self');</script>";
  } else {
    $seller_name = strip_tags($input->post('seller_name'));
    $seller_email = strip_tags($input->post('seller_email'));
    $seller_phone = strip_tags($input->post('country_code')) . " " . strip_tags($input->post('seller_phone'));
    $seller_country = strip_tags($input->post('seller_country'));
    $seller_city = strip_tags($input->post('seller_city'));
    $seller_address = strip_tags($input->post('seller_address'));
    $seller_timezone = strip_tags($input->post('seller_timezone'));
    $seller_language = strip_tags($input->post('seller_language'));
    $seller_headline = strip_tags($input->post('seller_headline'));
    $seller_about = strip_tags($input->post('seller_about'));
    $profile_photo = strip_tags($input->post('profile_photo'));
    $profile_photo = strip_tags($input->post('profile_photo'));
    $seller_address_img1 = strip_tags($input->post('seller_address_img1'));
    $seller_address_img2 = strip_tags($input->post('seller_address_img2'));
    $seller_postal_code = strip_tags($input->post('seller_postal_code'));
    $seller_region = strip_tags($input->post('seller_region'));
    $form_state = strip_tags($input->post('form_state'));


    $cover_photo = $_FILES['cover_photo']['name'];
    $cover_photo_tmp = $_FILES['cover_photo']['tmp_name'];
    $allowed = array('image/jpeg', 'jpg', 'gif', 'png', 'tif');
    $cover_file_extension = pathinfo($cover_photo, PATHINFO_EXTENSION);

    if (!in_array($cover_file_extension, $allowed) & !empty($cover_photo)) {
      echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
      echo "<script> window.open('settings?profile_settings', '_self');</script>";
      exit();
    }

    // edit 
    $updateForm = [
      "seller_id" => $login_seller_id,
      "seller_name" => $seller_name,
      "seller_phone" => $seller_phone,
      "seller_country" => $seller_country,
      "seller_city" => $seller_city,
      "seller_address" => $seller_address,
      "seller_timezone" => $seller_timezone,
      "seller_headline" => $seller_headline,
      "seller_about" => $seller_about,
      "seller_address_img1" => $seller_address_img1,
      "seller_address_img2" => $seller_address_img2,
      "seller_postal_code" => $seller_postal_code,
      "seller_region" => $seller_region,
      "seller_language" => $seller_language,
      "status" => 0,
    ];


    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

    // Upload profile photo if provided
    if (!empty($profile_photo)) {
      $profile_photo_ext = pathinfo($profile_photo, PATHINFO_EXTENSION);
      if (in_array($profile_photo_ext, $allowedTypes)) {
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], "user_images/" . $profile_photo);
        $updateForm["seller_image"] = $profile_photo;
        $updateForm["seller_image_s3"] = $enable_s3;
      } else {
        echo "<script>alert('Invalid file type for profile photo.');</script>";
        echo "<script>window.open('settings?profile_settings', '_self');</script>";
        exit();
      }
    }

    if (!empty($cover_photo)) {
      $cover_file_extension = pathinfo($cover_photo, PATHINFO_EXTENSION);
      if (in_array($cover_file_extension, $allowedTypes)) {
        $cover_photo = pathinfo($cover_photo, PATHINFO_FILENAME) . "_" . time() . ".$cover_file_extension";
        move_uploaded_file($cover_photo_tmp, "cover_images/" . $cover_photo);
        $updateForm["seller_cover_image"] = $cover_photo;
        $updateForm["seller_cover_image_s3"] = $enable_s3;
      } else {
        echo "<script>alert('Invalid file type for cover photo.');</script>";
        echo "<script>window.open('settings?profile_settings', '_self');</script>";
        exit();
      }
    }

    function editImage($fileInputName, $targetDir, $allowedTypes, $maxFileSize, $existingFileName = null) {
      // Check if a new file is uploaded
      if (!empty($_FILES[$fileInputName]["name"])) {
          $fileName = basename($_FILES[$fileInputName]["name"]); // Get the new file name
          // Sanitize the file name
          $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
          // Generate a unique file name to prevent overwriting
          $fileName = uniqid() . "_" . $fileName;
          $targetFilePath = $targetDir . $fileName; // Target file path
          $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); // File extension (in lowercase)
  
          // Check if the file type is allowed
          if (in_array($fileType, $allowedTypes)) {
              // Check the file size
              if ($_FILES[$fileInputName]["size"] <= $maxFileSize) {
                  // Check if the uploads folder is writable
                  if (!is_dir($targetDir) || !is_writable($targetDir)) {
                      return "Sorry, the upload directory is not writable.";
                  }
  
                  // Delete the old file if a new one is uploaded
                  if ($existingFileName && file_exists($targetDir . $existingFileName)) {
                      unlink($targetDir . $existingFileName); // Delete the old file
                  }
  
                  // Move the new file to the target directory
                  if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFilePath)) {
                      return "The file " . htmlspecialchars($fileName) . " has been uploaded and the old file has been deleted.";
                  } else {
                      return "Sorry, there was an error uploading your file.";
                  }
              } else {
                  return "Sorry, your file is too large. The maximum file size allowed is 1MB.";
              }
          } else {
              return "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
          }
      } else {
          return "No new file uploaded. Existing image remains unchanged.";
      }
  }
  
  // Usage example
  $targetDir = "uploads/address/"; // Directory where files will be uploaded
  $allowedTypes = array('jpg', 'png', 'jpeg', 'gif'); // Allowed file types
  $maxFileSize = 1048576; // 1MB in bytes
  
  // Existing file name (can be fetched from the database or elsewhere)
  $existingFile1 = "existing_image1.jpg";
  $existingFile2 = "existing_image2.jpg";
  
  // Edit first image
  echo editImage("seller_address_img1", $targetDir, $allowedTypes, $maxFileSize, $existingFile1);
  
  // Edit second image
  echo editImage("seller_address_img2", $targetDir, $allowedTypes, $maxFileSize, $existingFile2);
  


    if ($form_state) {
      $existseller_data = $db->select("sellers_profile_tmp", array("seller_id" => $login_seller_id));
      while ($rowdata_exist = $existseller_data->fetch()) {
        $status = $rowdata_exist->status;
        if ($status == 1) {
          $inserted = $db->update("sellers_profile_tmp", $updateForm, ['seller_id' => $seller_id, 'status' => 1]);
        } elseif ($status == 2) {
          $inserted = $db->update("sellers_profile_tmp", $updateForm, ['seller_id' => $seller_id, 'status' => 2]);
        }
      }
    } else {

      function uploadImage($fileInputName, $targetDir, $allowedTypes, $maxFileSize) {
        $fileName = basename($_FILES[$fileInputName]["name"]); // Get the file name
        // Sanitize the file name
        $fileName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
        // Generate a unique file name to prevent overwriting
        $fileName = uniqid() . "_" . $fileName;
        $targetFilePath = $targetDir . $fileName; // Target file path
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION)); // File extension (in lowercase)
        
        // Check if the file type is allowed
        if (in_array($fileType, $allowedTypes)) {
            // Check the file size
            if ($_FILES[$fileInputName]["size"] <= $maxFileSize) {
                // Check if the uploads folder is writable
                if (!is_dir($targetDir) || !is_writable($targetDir)) {
                    return "Sorry, the upload directory is not writable.";
                }
                // Move the file to the target directory
                if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFilePath)) {
                    return "The file " . htmlspecialchars($fileName) . " has been uploaded.";
                } else {
                    return "Sorry, there was an error uploading your file.";
                }
            } else {
                return "Sorry, your file is too large. The maximum file size allowed is 1MB.";
            }
        } else {
            return "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
        }
    }
    
    $targetDir = "uploads/address/"; // Directory where files will be uploaded
    $allowedTypes = array('jpg', 'png', 'jpeg', 'gif'); // Allowed file types
    $maxFileSize = 1048576; // 1MB in bytes
    
    // Upload first image
    echo uploadImage("seller_address_img1", $targetDir, $allowedTypes, $maxFileSize);
    
    // Upload second image
    echo uploadImage("seller_address_img2", $targetDir, $allowedTypes, $maxFileSize);
    



      $inserted = $db->insert("sellers_profile_tmp", $updateForm);
    }

    if ($inserted) {
      echo "<script>
              swal({
                type: 'success',
                text: 'Profile settings updated successfully!',
                timer: 3000,
                onOpen: function(){
                  swal.showLoading()
                }
              }).then(function(){
                // Read more about handling dismissals
                window.open('settings?profile_settings','_self');
              });
              </script>";
    } else {
      echo "<script>
              swal({
                type: 'warning',
                text: 'Profile settings couldnot updated!',
                timer: 3000,
                onOpen: function(){
                  swal.showLoading()
                }
              }).then(function(){
                // Read more about handling dismissals
                window.open('settings?profile_settings','_self');
              });
              </script>";
    }
  }
}

// IS PROFILE UPATE REQUEST IS SENT
// sellers_profile_tmp
$qSellerUpdate = $db->query("SELECT * FROM sellers_profile_tmp WHERE seller_id = :seller_id ORDER BY 1 DESC LIMIT 1", ['seller_id' => $login_seller_id]);
$oSellerUpdate = $qSellerUpdate->fetch();
$reviewRemark = null;
$tblSeller = "sellers";
$modificationMsg = '';
if ($oSellerUpdate) {
  $userStatus = $oSellerUpdate->status;
  $reviewRemark = $userStatus == 0 ? 'review' : ($userStatus == 2 ? 'modification' : 'active');
  $modificationMsg = $userStatus == 2 ? $oSellerUpdate->feedback : '';
  $tblSeller = "sellers_profile_tmp";

  $login_seller_name = $oSellerUpdate->seller_name;
  $login_seller_phone = $oSellerUpdate->seller_phone;
  $login_seller_country = $oSellerUpdate->seller_country;
  $login_seller_city = $oSellerUpdate->seller_city;
  $login_seller_address = $oSellerUpdate->seller_address;
  $login_seller_address_img1 = $oSellerUpdate->seller_address_img1;
  $login_seller_address_img2 = $oSellerUpdate->seller_address_img2;
  $login_seller_postal_code = $oSellerUpdate->seller_postal_code;
  $login_seller_region = $oSellerUpdate->seller_region;
  $login_seller_timzeone = $oSellerUpdate->seller_timezone;
  $login_seller_language = $oSellerUpdate->seller_language;
  $login_seller_image = $oSellerUpdate->seller_image;
  $login_seller_image_s3 = $oSellerUpdate->seller_image_s3;
  $login_seller_cover_image = $oSellerUpdate->seller_cover_image;
  $login_seller_cover_image_s3 = $oSellerUpdate->seller_cover_image_s3;
  $login_seller_headline = $oSellerUpdate->seller_headline;
  $login_seller_about = $oSellerUpdate->seller_about;
}

require 'admin/timezones.php';

$phone_no = explode(" ", $login_seller_phone ?? "");
?>


<hr />
<?php
$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");
if (is_array($form_errors)) {
?>
  <div class="alert alert-danger">
    <!--- alert alert-danger Starts --->
    <ul class="list-unstyled mb-0">
      <?php $i = 0;
      foreach ($form_errors as $error) {
        $i++;
      ?>
        <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
      <?php } ?>
    </ul>
  </div>
  <!--- alert alert-danger Ends --->
<?php } ?>
<h5 class="mb-2 full-width-b"><span class="text-align-center-a">Profile Information</span></h5>
<?php if ($reviewRemark == 'review') { ?>
  <div class="alert alert-warning text-align-center">
    Your profile is under review.
  </div>
<?php } ?>
<?php if ($reviewRemark == 'modification') { ?>
  <div class="alert alert-primary text-align-center">
    Your profile needs modification, here is the message from ADMIN: <br /> <?= $modificationMsg ?>
    <style>
      .profile-setting {
        display: flex !important;
      }

      .profile-setting:first-child {
        display: flex;
      }

      .btn-submit {
        display: block !important;
      }
    </style>
  </div>
<?php } ?>
<?php if ($reviewRemark == 'active') { ?>
  <div class="alert alert-success text-align-center box-shadow-8b">
    Your profile is active.
  </div>
  <style>
    .profile-setting {
      display: flex !important;
    }

    .profile-setting:first-child {
      display: flex;
    }

    .btn-submit {
      display: block !important;
    }

    .save_changes_btn_profile {
      border: none;
      padding: 9px;
    }
  </style>
<?php
}
// SHOW FORMS IF the modification request is needed or review is null
if (is_null($reviewRemark) || $reviewRemark == 'modification' || $reviewRemark == 'active') {
?>
  <form method="post" enctype="multipart/form-data" runat="server" autocomplete="off" id="progressiveForm">
    <div class="form-group row profile-setting">
      <div class="col-md-6">
        <label for=""><?= $lang['label']['full_name']; ?></label> <br>
        <input type="text" name="seller_name" value="<?= $login_seller_name; ?>" class="form-control box-shadow-allpros" required>
      </div>
      <div class="col-md-6 phoneNo">
        <label for=""> <?= $lang['label']['phone']; ?></label> <br>
        <div class="input-group">
          <span class="input-group-addon p-0 border-0 rounded-0" style="width: 28%;">
            <?php include("includes/country_codes.php"); ?>
          </span>
          <input type="text" class="form-control box-shadow-allpros" name="seller_phone" placeholder="<?= $lang['placeholder']['phone']; ?>" value="<?= @$phone_no[1]; ?>" required />
        </div>
        <small class="text-muted">Please enter 10 digit phone number only like this: <b>2561040358</b> </small>
      </div>
    </div>


    <div class="form-group row profile-setting"><!-- form-group row Starts -->

      <div class="col-md-12">
        <label for="">Address</label> <br>
        <input type="text" name="seller_address" placeholder="Enter your address" value="<?= $login_seller_address; ?>" class="form-control box-shadow-allpros" required="" />
      </div>


    </div><!-- form-group row Ends -->
    <div class="form-group row profile-setting"><!-- form-group row Starts -->
      <div class="col-md-6">
        <label for="">Adress proof doc. front view</label><br>
        <input type="file" name="seller_address_img1" accept="image/*" class="form-control box-shadow-allpros" id="seller_address_img1" onchange="addressImgOne(event)" />
        <p id="seller_address_img1input"></p>
        <input type="hidden" name="seller_address_img1" value="<?= $login_seller_address_img1 ?>">

        <p class="mt-2">
          This document is your address proof Hiremyprofile
        </p>
        <?php if (!empty($login_seller_address_img1)) { ?>
          <img src="<?= $site_url ?>/uploads/address/<?= $login_seller_address_img1 ?>" width="80" class="img-thumbnail img-circle" />
        <?php } else { ?>
          <img id="addressImgOne" width="80" max-filesize>
        <?php } ?>
      </div>
      <div class="col-md-6">
        <label for=""> Adress proof doc. back view </label> <br>
        <input type="file" name="seller_address_img2" accept="image/*" class="form-control box-shadow-allpros" id="seller_address_img2" onchange="addressImgTwo(event)" />
        <p id="seller_address_img2input"></p>
        <input type="hidden" name="seller_address_img2" value="<?= $login_seller_address_img2 ?>">

        <p class="mt-2">
          This document is your address proof Hiremyprofile
        </p>
        <?php if (!empty($login_seller_address_img2)) { ?>
          <img src="<?= $site_url ?>/uploads/address/<?= $login_seller_address_img2 ?>" width="80" class="img-thumbnail img-circle" />
        <?php } else { ?>
          <img id="addressImgTwo" width="80" max-filesize>
        <?php } ?>
      </div>
    </div>
    <div class="form-group row profile-setting"><!-- form-group row Starts -->
      <div class="col-md-6">
        <label for=""><?= $lang['label']['city']; ?></label>
        <input type="text" name="seller_city" id="district" placeholder="<?= $lang['placeholder']['city']; ?>" value="<?= $login_seller_city; ?>" class="form-control box-shadow-allpros" required="" />
      </div>
      <div class="col-md-6">
        <label for="">ZIP / Postal code </label><br>
        <input type="text" name="seller_postal_code" id="postalCode" placeholder="Enter your ZIP / Postal code" value="<?= $login_seller_postal_code; ?>" class="form-control box-shadow-allpros" required="" />
      </div>
    </div><!-- form-group row Ends -->



    <div class="form-group row profile-setting"><!-- form-group row Starts -->
      <div class="col-md-6">
        <label for=""> State / Region </label><br>
        <input type="text" name="seller_region" id="region" placeholder="Enter your state / region" value="<?= $login_seller_region; ?>" class="form-control box-shadow-allpros" required="" />
      </div>
      <div class="col-md-6">
        <label for=""> <?= $lang['label']['country']; ?> </label><br>
        <select name="seller_country" id="country" class="form-control box-shadow-allpros" required>
          <?php
          $get_countries = $db->select("countries");
          while ($row_countries = $get_countries->fetch()) {
            $id = $row_countries->id;
            $name = $row_countries->name;

            $get_code = $db->select("country", ['nicname' => $name]);

            echo "<option value='$name'" . ($name == $login_seller_country ? "selected" : "") . ">$name</option>";
          }
          ?>
        </select>
      </div>
    </div><!-- form-group row Ends -->



    <div class="form-group row profile-setting">
      <div class="col-md-6">
        <label for=""> <?= $lang['label']['timezone']; ?> </label> <br>
        <select name="seller_timezone" id="timezone" class="form-control box-shadow-allpros site_logo_type" required="">
          <?php foreach ($timezones as $key => $zone) { ?>
            <option <?= ($login_seller_timzeone == $zone) ? "selected=''" : ""; ?> value="<?= $zone; ?>">
              <?= $zone; ?>
            </option>
          <?php } ?>
        </select>
      </div>


      <div class="col-md-6">
        <label for=""><?= $lang['label']['main_language']; ?> </label><br>
        <select name="seller_language" class="form-control box-shadow-allpros" required="">
          <?php if ($login_seller_language == 0) { ?>
            <option class="hidden" value=""> Select Language </option>
            <?php
            $get_languages = $db->select("seller_languages");
            while ($row_languages = $get_languages->fetch()) {
              $language_id = $row_languages->language_id;
              $language_title = $row_languages->language_title;
            ?>
              <option value="<?= $language_id; ?>"> <?= $language_title; ?> </option>
            <?php } ?>
          <?php } else { ?>
            <?php
            $get_languages = $db->select("seller_languages");
            while ($row_languages = $get_languages->fetch()) {
              $language_id = $row_languages->language_id;
              $language_title = $row_languages->language_title;
            ?>
              <option value="<?= $language_id; ?>" <?php if ($language_id == $login_seller_language) {
                                                      echo "selected";
                                                    } ?>> <?= $language_title; ?> </option>
            <?php } ?>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group row profile-setting">
      <div class="col-md-6">
        <label for=""> <?= $lang['label']['profile_photo']; ?> </label> <br>
        <input type="file" name="profile_photo" class="form-control box-shadow-allpros" accept="image/*" id="profileImg" onchange="loadFile(event)">
        <p id="profileInfo"></p>
        <input type="hidden" name="profile_photo">

        <p class="mt-2">
          <?= str_replace('{site_name}', $site_name, $lang['note']['profile_photo']); ?>
        </p>
        <?php if (!empty($login_seller_image)) { ?>
          <img src="<?= $site_url ?>/user_images/<?= $login_seller_image ?>" width="80" class="img-thumbnail img-circle" />
        <?php } else { ?>
          <img id="output" width="80" max-filesize>
        <?php } ?>
      </div>
      <div class="col-md-6">
        <label for=""> <?= $lang['label']['cover_photo']; ?></label> <br>
        <input type="file" name="cover_photo" id="cover" class="form-control box-shadow-allpros" accept="image/*" onchange="loadinFile(event)">
        <p id="pictureInfo"></p>
        <p class="mt-2">
          <?= str_replace('{url}', "$site_url/{$_SESSION['seller_user_name']}", $lang['note']['cover_photo']); ?>
        </p>
        <?php if (!empty($login_seller_cover_image)) { ?>
          <img src="<?= $site_url ?>/cover_images/<?= $login_seller_cover_image ?>" width="80" class="img-thumbnail img-circle" />
        <?php } else { ?>
          <img id="flicker" width="80">
        <?php } ?>
      </div>
    </div>

    <div class="form-group row profile-setting">
      <div class="col-md-6">
        <label for=""> <?= $lang['label']['headline']; ?> </label> <br>
        <textarea name="seller_headline" id="textarea-headline" rows="4" class="form-control box-shadow-for3" required maxlength="200"><?= $login_seller_headline; ?></textarea>
        <span class="float-right mt-1">
          <span class="count-headline"> 0 </span> / 200 <?= $lang['label']['max']; ?>
        </span>
      </div>

      <div class="col-md-6">
        <label for=""> <?= $lang['label']['description']; ?></label>
        <textarea name="seller_about" id="textarea-about" rows="4" class="form-control box-shadow-for3" required maxlength="350" placeholder="<?= $lang['placeholder']['description']; ?>"><?= $login_seller_about; ?></textarea>
        <span class="float-right mt-1">
          <span class="count-about"> 0 </span> / 350 <?= $lang['label']['max']; ?>
        </span>
      </div>
    </div>
    <hr>
    <input type="hidden" name="form_state" value="<?= $reviewRemark ?>">
    <div class="di-flex btn-submit">
      <button type="submit" name="submit" class="btn btn-success save_changes_btn_profile">
        <i class="fa fa-floppy-o"></i> &nbsp; <?= $lang['button']['save_changes']; ?>
      </button>
    </div>
  </form>
<?php
} // $reviewRemark
else {
?>
  <div class="row">
    <div class="col-md-3"><?= $lang['label']['full_name']; ?></div>
    <div class="col-md-9"><?= $login_seller_name; ?></div>

    <div class="col-md-3"><?= $lang['label']['phone']; ?></div>
    <div class="col-md-9"><?= $login_seller_phone; ?></div>

    <div class="col-md-3"><?= $lang['label']['country']; ?></div>
    <div class="col-md-9"><?= $login_seller_country; ?></div>

    <div class="col-md-3"><?= $lang['label']['city']; ?></div>
    <div class="col-md-9"><?= $login_seller_city; ?></div>

    <div class="col-md-3">Address</div>
    <div class="col-md-9"><?= $login_seller_address; ?></div>

    <div class="col-md-3">Address file front</div>
    <div class="col-md-9"><?php if (!empty($login_seller_address_img1)) { ?>
        <img src="<?= $site_url ?>/uploads/address/<?= $login_seller_address_img1 ?>" width="80" class="img-thumbnail img-circle" />
      <?php } ?>
    </div>

    <div class="col-md-3">Address file back</div>
    <div class="col-md-9"><?php if (!empty($login_seller_address_img2)) { ?>
        <img src="<?= $site_url ?>/uploads/address/<?= $login_seller_address_img2 ?>" width="80" class="img-thumbnail img-circle" />
      <?php } ?>
    </div>


    <div class="col-md-3">ZIP / Postal code</div>
    <div class="col-md-9"><?= $login_seller_postal_code; ?></div>

    <div class="col-md-3">State / Region </div>
    <div class="col-md-9"><?= $login_seller_region; ?></div>

    <div class="col-md-3"><?= $lang['label']['timezone']; ?></div>
    <div class="col-md-9"><?= $login_seller_city; ?></div>

    <div class="col-md-3"><?= $lang['label']['profile_photo']; ?></div>
    <div class="col-md-9"><?php if (!empty($login_seller_image)) { ?>
        <img src="<?= $site_url ?>/user_images/<?= $login_seller_image ?>" width="80" class="img-thumbnail img-circle" />
      <?php } ?>
    </div>

    <div class="col-md-3"><?= $lang['label']['cover_photo']; ?></div>
    <div class="col-md-9"><?php if (!empty($login_seller_cover_image)) { ?>
        <img src="<?= $site_url ?>/cover_images/<?= $login_seller_cover_image ?>" width="80" class="img-thumbnail img-circle" />
      <?php } ?>
    </div>

    <div class="col-md-3"><?= $lang['label']['headline']; ?></div>
    <div class="col-md-9"><?= $login_seller_headline; ?></div>

    <div class="col-md-3"><?= $lang['label']['description']; ?></div>
    <div class="col-md-9"><?= $login_seller_about; ?></div>
  </div>
<?php } ?>

<!--  -->

<hr>
<h5 class="mb-4 full-width-a"><span class="text-align-center-a">Account Information</span></h5>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <div class="col-md-6">
      <label class=""> <?= $lang['label']['username']; ?> </label>
      <input type="text" name="seller_user_name" value="<?= $login_seller_user_name; ?>" class="form-control box-shadow-allpros" required readonly>
    </div>
    <div class="col-md-6">
      <label class=""> <?= $lang['label']['email']; ?> </label>
      <input type="text" name="seller_email" value="<?= $login_seller_email; ?>" class="form-control box-shadow-allpros" required readonly>
    </div>
  </div>

  <?php
  if ($login_seller_pass == "") { ?>

    <div class="form-group row">
      <div class="col-md-6">
        <label class=""> <?= $lang['label']['password']; ?> </label>
        <input type="password" name="password" id="profilepassopen" value="" placeholder="Enter new password" class="form-control box-shadow-allpros" required minlength="8" pattern="[a-zA-Z0-9]+">
        <img src="<?= $site_url ?>/images/closed-eye.png" id="profileimgopen">
      </div>
      <div class="col-md-6">
        <label class=""> <?= $lang['label']['password_confirm']; ?> </label>
        <input type="password" name="password_confirm" value="" id="profileconfirmclosed" placeholder="Confirm password" class="form-control box-shadow-allpros" required minlength="8" pattern="[a-zA-Z0-9]+">
        <img src="<?= $site_url ?>/images/closed-eye.png" id="profileimgclosed">
      </div>
    </div>

    <div class="di-flex">
      <button type="submit" name="submit_change_password" class="btn btn-success submit_change_password_btnstyle">
        <i class="fa fa-unlock-alt"></i> &nbsp; <?= $lang['button']['change_password']; ?>
      </button>
    </div>
  <?php
  } else {
    //   echo "bye";
  }

  ?>


</form>





<script>
  $(document).ready(function() {
    $image_crop = $('#image_demo').croppie({
      enableExif: true,
      viewport: {
        width: 200,
        height: 200,
        type: 'square' //circle
      },
      boundary: {
        width: 100,
        height: 250
      }
    });

    function crop(data) {
      var reader = new FileReader();
      reader.onload = function(event) {
        $image_crop.croppie('bind', {
          url: event.target.result
        }).then(function() {
          console.log('jQuery bind complete');
        });
      }
      reader.readAsDataURL(data.files[0]);
      $('#insertimageModal').modal('show');
      $('input[type=hidden][name=img_type]').val($(data).attr('name'));
    }
    $(document).on('change', 'input[type=file]:not(#cover)', function() {
      var size = $(this)[0].files[0].size;
      var ext = $(this).val().split('.').pop().toLowerCase();
      if ($.inArray(ext, ['jpeg', 'jpg', 'gif', 'png']) == -1) {
        alert('Your File Extension Is Not Allowed.');
        $(this).val('');
      } else {
        crop(this);
      }
    });
    $('.crop_image').click(function(event) {
      $('#wait').addClass("loader");
      var name = $('input[type=hidden][name=img_type]').val();
      $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
      }).then(function(response) {
        $.ajax({
          url: "crop_upload",
          type: "POST",
          data: {
            image: response,
            name: $('input[type=file][name=' + name + ']').val().replace(/C:\\fakepath\\/i, '')
          },
          success: function(data) {
            $('#wait').removeClass("loader");
            $('#insertimageModal').modal('hide');
            $('input[type=hidden][name=' + name + ']').val(data);
          }
        });
      });
    });

    function updateHeadlineCounter() {
      var textarea_headline = $("#textarea-headline").val();
      $(".count-headline").text(textarea_headline.length);
    }

    function updateAboutCounter() {
      var textarea_about = $("#textarea-about").val();
      $(".count-about").text(textarea_about.length);
    }

    // Update counters on input events
    $("#textarea-headline").on('input', updateHeadlineCounter);
    $("#textarea-about").on('input', updateAboutCounter);

    // Update counters on page load (for edit mode)
    $(document).ready(function() {
      updateHeadlineCounter();
      updateAboutCounter();
    });


  });


  // //////////////////////////////////////////////////////////////////

  var profileimgopen = document.getElementById("profileimgopen");
  var profilepassopen = document.getElementById("profilepassopen");

  profileimgopen.onclick = function() {
    if (profilepassopen.type == "password") {
      profilepassopen.type = "text"
      profileimgopen.src = "<?= $site_url ?>/images/open-eye.png";
    } else {
      profilepassopen.type = "password"
      profileimgopen.src = "<?= $site_url ?>/images/closed-eye.png";
    }
  }



  var profileimgclosed = document.getElementById("profileimgclosed");
  var profileconfirmclosed = document.getElementById("profileconfirmclosed");

  profileimgclosed.onclick = function() {
    if (profileconfirmclosed.type == "password") {
      profileconfirmclosed.type = "text"
      profileimgclosed.src = "<?= $site_url ?>/images/open-eye.png";
    } else {
      profileconfirmclosed.type = "password"
      profileimgclosed.src = "<?= $site_url ?>/images/closed-eye.png";
    }
  }


  // ///////////////

  document.getElementById('seller_address_img1').addEventListener('change', function() {
    var file = this.files[0];
    var fileInfo = "File type: " + file.type + "<br>" +
      "File size: " + file.size + " bytes";
    document.getElementById('seller_address_img1input').innerHTML = fileInfo;
  });


  // ///////////////

  document.getElementById('seller_address_img2').addEventListener('change', function() {
    var file = this.files[0];
    var fileInfo = "File type: " + file.type + "<br>" +
      "File size: " + file.size + " bytes";
    document.getElementById('seller_address_img2input').innerHTML = fileInfo;
  });

  // ///////////////

  document.getElementById('profileImg').addEventListener('change', function() {
    var file = this.files[0];
    var fileInfo = "File type: " + file.type + "<br>" +
      "File size: " + file.size + " bytes";
    document.getElementById('profileInfo').innerHTML = fileInfo;
  });

  //    /////////////////////

  document.getElementById('cover').addEventListener('change', function() {
    var file = this.files[0];
    var fileInfo = "File type: " + file.type + "<br>" +
      "File size: " + file.size + " bytes";
    document.getElementById('pictureInfo').innerHTML = fileInfo;
  });
</script>


<script>
  var loadinFile = function(event) {
    var flicker = document.getElementById("flicker");
    flicker.src = URL.createObjectURL(event.target.files[0]);
    flicker.onload = function() {
      URL.revokeObjectURL(flicker.src)
    }
  }
</script>
<script>
  var loadFile = function(event) {
    var output = document.getElementById("output");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src)
    }
  }
</script>

<script>
  var addressImgOne = function(event) {
    var addressImgOne = document.getElementById("addressImgOne");
    addressImgOne.src = URL.createObjectURL(event.target.files[0]);
    addressImgOne.onload = function() {
      URL.revokeObjectURL(addressImgOne.src)
    }
  }
</script>

<script>
  var addressImgTwo = function(event) {
    var addressImgTwo = document.getElementById("addressImgTwo");
    addressImgTwo.src = URL.createObjectURL(event.target.files[0]);
    addressImgTwo.onload = function() {
      URL.revokeObjectURL(addressImgTwo.src)
    }
  }
</script>


<script>
  document.getElementById("progressiveForm").addEventListener("input", function(event) {
    handleProgression(event);
  });

  document.getElementById("progressiveForm").addEventListener("change", function(event) {
    handleProgression(event);
  });

  function handleProgression(event) {
    var currentElement = event.target.closest(".profile-setting");
    var nextElement = findNextElement(currentElement);

    if (nextElement && isFilledAndValid(event.target)) {
      nextElement.style.display = "flex";
    }

    checkAllInputsFilled(); // Check if the last textarea is filled
  }

  function findNextElement(currentElement) {
    var nextElement = currentElement.nextElementSibling;
    while (nextElement) {
      if (nextElement.querySelector("input, select, textarea")) {
        return nextElement;
      }
      nextElement = nextElement.nextElementSibling;
    }
    return null;
  }

  function isFilledAndValid(element) {
    if (element.tagName === "INPUT" || element.tagName === "TEXTAREA") {
      return element.checkValidity() && element.value.trim() !== "";
    } else if (element.tagName === "SELECT") {
      return element.selectedIndex !== 0;
    }
    return false;
  }

  function checkAllInputsFilled() {
    var lastTextarea = document.querySelector("#textarea-about");
    var submitButton = document.querySelector(".btn-submit");
    submitButton.style.display = lastTextarea.value.trim() !== "" ? "block" : "none";
  }

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  }

  function showPosition(position) {
    const lat = position.coords.latitude;
    const long = position.coords.longitude;

    // Fetch location data using Nominatim API
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}&zoom=10`)
      .then(response => response.json())
      .then(data => {
        document.getElementById("country").value = data.address.country || '';
        document.getElementById("region").value = data.address.state || data.address.region || '';
        document.getElementById("district").value = data.address.state_district || data.address.county || '';
        document.getElementById("postalCode").value = data.address.postcode || '';

        // Trigger progression to next input
        handleAutoFilledElements();

        // Fetch timezone using the lat/long coordinates
        fetch(`https://worldtimeapi.org/api/timezone`)
          .then(response => response.json())
          .then(timezoneData => {
            const timeZone = timezoneData.find(zone => zone.includes(data.address.country_code));
            document.getElementById("timezone").value = timeZone || 'Timezone not found';

            // Trigger progression to next input after timezone is filled
            handleAutoFilledElements();
          })
          .catch(error => {
            console.error("Error fetching timezone data:", error);
          });
      })
      .catch(error => {
        console.error("Error fetching location data:", error);
      });
  }

  function handleAutoFilledElements() {
    const autoFilledElements = document.querySelectorAll("#country, #region, #district, #postalCode, #timezone");
    autoFilledElements.forEach(element => {
      if (isFilledAndValid(element)) {
        var currentElement = element.closest(".profile-setting");
        var nextElement = findNextElement(currentElement);
        if (nextElement) {
          nextElement.style.display = "flex";
        }
      }
    });
  }

  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        alert("User denied the request for Geolocation.");
        break;
      case error.POSITION_UNAVAILABLE:
        alert("Location information is unavailable.");
        break;
      case error.TIMEOUT:
        alert("The request to get user location timed out.");
        break;
      case error.UNKNOWN_ERROR:
        alert("An unknown error occurred.");
        break;
    }
  }

  // Auto-fill location details when the page loads
  window.onload = getLocation;
</script>



<style>
  @media (max-width:768px) {
    .full-width-a {
      width: 100%;
      /* border:1px solid green; */
      font-size: 18px !important;
      display: flex;
      margin-top: -10px !important;
    }

    .full-width-b {
      width: 100%;
      /* border:1px solid green; */
      font-size: 18px !important;
      display: flex;
      margin-top: 20px !important;
    }

    .text-align-center-a {
      text-align: center;
      margin: auto;
      /* color: #186b64; */
    }

    .full-width {
      width: 100%;
      display: flex;
      /* border:1px solid green; */
    }

    .text-align-center {
      text-align: center;
      margin: auto;
    }
  }

  .box-shadow-allpros {
    /* box-shadow:0px 0px 5px gray ,inset 0px 0px 15px lightgray; */
    border: 1px solid lightgray;
    height: 45px !important;
    padding-top: 7px;
    font-size: 15px;
    margin-bottom: 10px;
  }

  .box-shadow-for3 {
    /* box-shadow:0px 0px 5px gray ,inset 0px 0px 25px lightgray; */
    border: 1px solid lightgray;
    font-size: 15px;
  }

  .increase-width2 {
    width: 100%;
    /* margin: auto; */
    height: 50px !important;
    /* box-shadow:2px 2px 5px black, inset 0px 0px 15px black; */
    border: 1px solid gray;
    font-size: 16px !important;
  }

  .submit_change_password_btnstyle {
    padding: 9px 10px;
  }

  .di-flex {
    width: 100%;
    text-align: end;
  }

  .font-size-16 {
    font-size: 16px;
    /* font-weight: 500; */
  }


  #profileimgclosed {
    margin: -43px 10px 0 0;
    width: 18px;
    float: inline-end
  }

  #profileimgopen {
    margin: -43px 10px 0 0;
    width: 18px;
    float: inline-end
  }

  .profile-setting {
    display: none;
  }

  .profile-setting:first-child {
    display: flex;
  }

  .btn-submit {
    display: none;
  }
</style>