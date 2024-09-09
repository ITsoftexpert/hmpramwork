<?php

@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

if(isset($_GET['edit_instruction'])){
    $edit_id = $input->get('edit_instruction');
    $edit_slide = $db->select("instructions",array('instruction_id' => $edit_id));
    if($edit_slide->rowCount() == 0){
      echo "<script>window.open('index?dashboard','_self');</script>";
    }
    $row_edit = $edit_slide->fetch();
    $i_title = $row_edit->instruction_title;
    $i_desc = $row_edit->instruction_desc;
    $i_for = $row_edit->instruction_for;
    $i_icon = $row_edit->instruction_icon;
    $i_color_code = $row_edit->instruction_color_code;
}

?>

<div class="breadcrumbs">
<div class="col-sm-4">
<div class="page-header float-left">
<div class="page-title">
    <h1><i class="menu-icon fa fa-picture-o"></i> Instruction</h1>
</div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
<div class="page-title">
    <ol class="breadcrumb text-right">
        <li class="active">Edit Instruction</li>
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
        <h4 class="h4">Edit Instruction</h4>
    </div><!--- card-header Ends --->
    <div class="card-body"><!--- card-body Starts --->
        <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
            <div class="form-group row">
                <label class="col-md-3 control-label"> Section : </label>
                <div class="col-md-6">
                    <select class="form-control" name="instruction_for">
                        <option value="1" <?php if($i_for==1){ echo 'selected=selected'; } ?>>Buyer</option>
                        <option value="2" <?php if($i_for==2){ echo 'selected=selected'; } ?>>Seller</option>
                    </select>
                </div>
            </div>
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Instruction Title : </label>
                <div class="col-md-6">
                    <input type="text" name="instruction_title" class="form-control" value="<?= $i_title; ?>">
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Instruction Description : </label>
                <div class="col-md-6">
                    <textarea name="instruction_desc" class="form-control"><?= $i_desc; ?></textarea>
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Instruction Icon : </label>
                <div class="col-md-6">
                    <input type="text" name="instruction_icon" class="form-control" value="<?= $i_icon; ?>">
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Icon Color code in Hex Format: </label>
                <div class="col-md-6">
                    <select name="instruction_color_code" class="form-control">
                        <option value="text-primary" <?php if($i_color_code=='text-primary'){ echo 'selected=selected'; } ?>>Blue</option>
                        <option value="text-warning" <?php if($i_color_code=='text-warning'){ echo 'selected=selected'; } ?>>Yellow</option>
                        <option value="text-danger" <?php if($i_color_code=='text-danger'){ echo 'selected=selected'; } ?>>Red</option>
                        <option value="text-green1" <?php if($i_color_code=='text-green1'){ echo 'selected=selected'; } ?>>Green</option>
                        <option value="text-purple" <?php if($i_color_code=='text-purple'){ echo 'selected=selected'; } ?>>Blue</option>
                        <option value="text-nacho-cheese" <?php if($i_color_code=='text-nacho-cheese'){ echo 'selected=selected'; } ?>>Orange</option>
                    </select>
                </div>
            </div><!--- form-group row Ends --->
            <div class="form-group row">
                <!--- form-group row Starts --->
                <label class="col-md-3 control-label"></label>
                <div class="col-md-6">
                    <input type="submit" name="update" class="btn btn-success form-control" value="Update Instruction">
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
   
   $rules = array(
    "instruction_title" => "required",
    "instruction_desc" => "required",
    "instruction_for" => "required",
    "instruction_icon" => "required",
    "instruction_color_code" => "required");

   $val = new Validator($_POST,$rules);

   if($val->run() == false){

     Flash::add("form_errors",$val->get_all_errors());
     Flash::add("form_data",$_POST);
     echo "<script> window.open(window.location.href,'_self');</script>";

   }else{
    $instruction_title = $input->post('instruction_title');
    $instruction_desc = $input->post('instruction_desc');
    $instruction_for = $input->post('instruction_for');
    $instruction_icon = $input->post('instruction_icon');
    $instruction_color_code = $input->post('instruction_color_code');

     $update_slide = $db->update("instructions",array("language_id" => $adminLanguage,"instruction_title" => $instruction_title, "instruction_desc" => $instruction_desc,"instruction_for" => $instruction_for,"instruction_icon"=>$instruction_icon,"instruction_color_code"=>$instruction_color_code),array("instruction_id" => $edit_id));

         if($update_slide){
            $insert_log = $db->insert_log($admin_id,"instructions",$edit_id,"updated");
            echo "<script>alert('One Instruction has been Updated.');</script>";
            echo "<script>window.open('index?view_instructions','_self');</script>";
         }

      // }
   
   }

}

?>

<?php } ?>