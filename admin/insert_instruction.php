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
                <h1><i class="menu-icon fa fa-picture-o"></i> Instructions </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Add New Instruction</li>
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
                Insert New Instruction for Homepage<br>
                <small>This instruction will show in homepage</small>
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
                        <select class="form-control" name="instruction_for">
                            <option value="1">Buyer</option>
                            <option value="2">Seller</option>
                        </select>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Instruction Title : </label>
                    <div class="col-md-6">
                        <input type="text" name="instruction_title" class="form-control">
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Instruction Description : </label>
                    <div class="col-md-6">
                        <textarea name="instruction_desc" class="form-control"></textarea>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Instruction Icon : </label>
                    <div class="col-md-6">
                        <input type="text" name="instruction_icon" class="form-control"><br>
                        <span class="text-muted">Add Font Awesome icon code for ex. fa-info </span>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Icon Color code in Hex Format: </label>
                    <div class="col-md-6">
                        <select name="instruction_color_code" class="form-control">
                            <option value="text-primary">Blue</option>
                            <option value="text-warning">Yellow</option>
                            <option value="text-danger">Red</option>
                            <option value="text-green1">Green</option>
                            <option value="text-purple">Blue</option>
                            <option value="text-nacho-cheese">Orange</option>
                        </select>
                        <!-- <input type="text" name="instruction_color_code" class="form-control"><br> -->
                        <span class="text-muted">Add Font Awesome icon code for ex. fa-info </span>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        <input type="submit" name="submit" class="btn btn-success form-control" value="Add Instruction">
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
    "instruction_title" => "required",
    "instruction_desc" => "required",
    "instruction_for" => "required",
    "instruction_icon" => "required",
    "instruction_color_code" => "required");

   $val = new Validator($_POST,$rules);
   if($val->run() == false){
      Flash::add("form_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open('index?insert_instruction','_self');</script>";
   }else{
      $instruction_title = $input->post('instruction_title');
      $instruction_desc = $input->post('instruction_desc');
      $instruction_for = $input->post('instruction_for');
      $instruction_icon = $input->post('instruction_icon');
      $instruction_color_code = $input->post('instruction_color_code');

     $insert_instruction = $db->insert("instructions",array("language_id" => $adminLanguage,"instruction_title" => $instruction_title, "instruction_desc" => $instruction_desc,"instruction_for" => $instruction_for,"instruction_icon"=>$instruction_icon,"instruction_color_code"=>$instruction_color_code));

    if($insert_instruction){
        $insert_id = $db->lastInsertId();
        $insert_log = $db->insert_log($admin_id,"instructions",$insert_id,"inserted");
        echo "<script>alert('One Instruction has been Inserted.');</script>";
        echo "<script>window.open('index?view_instructions','_self');</script>";
    }
  }
}
?>

<?php } ?>