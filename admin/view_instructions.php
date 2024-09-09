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
		    <h1><i class="menu-icon fa fa-table"></i> Instructions</h1>
		  </div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
		   <div class="page-title">
				<ol class="breadcrumb text-right">
					<li class="active">
					   <a href="index?insert_instruction" class="btn btn-success">
					   	<i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Instruction</span>
					   </a>
					</li>
				</ol>
		   </div>
		</div>
	</div>
</div>

<div class="container"><!--- container Starts --->
<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h3">Instructions</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<div class="table-responsive">
<table class="table table-bordered"><!--- table table-bordered table-hover Starts --->	
	<thead>
	<tr>
		<th>Title</th>
		<th>Description</th>
		<th>Type</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody><!--- tbody Starts --->
	<?php
	$instructions = $db->query("select * from instructions");
	while($instruction = $instructions->fetch()){?>
	<tr>
	<td><?= $instruction->instruction_title; ?></td>
	<td><?= $instruction->instruction_desc; ?></td>
	<td><?php if($instruction->instruction_for == 1){ echo 'Buyer'; }else{ echo 'Seller';} ?></td>
	<td>
		<div class="dropdown"><!--- dropdown Starts --->
		  <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
		  <div class="dropdown-menu"><!--- dropdown-menu Starts --->
		    <a class="dropdown-item" href="index?edit_instruction=<?= $instruction->instruction_id; ?>">
		      <i class="fa fa-edit"></i> Edit 
		    </a>
		    <a class="dropdown-item" href="index?delete_instruction=<?= $instruction->instruction_id; ?>">
		    	<i class="fa fa-trash"></i> Delete
		    </a>
		  </div><!--- dropdown-menu Ends --->
		</div><!--- dropdown Ends --->
	</td>
	</tr>
	<?php } ?>
	</tbody>
</table>
</div>

</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- row Ends --->
</div><!--- container Ends --->
<?php } ?>