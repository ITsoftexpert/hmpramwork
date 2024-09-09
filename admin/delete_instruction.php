<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{
	if(isset($_GET['delete_instruction'])){
		$id = $input->get('delete_instruction');
		$delete_instruction = $db->delete("instructions",array('instruction_id' => $id));
		if($delete_instruction){
			$insert_log = $db->insert_log($admin_id,"instructions",$id,"deleted");
			echo "<script>alert('Instruction deleted successfully.');</script>";
			echo "<script>window.open('index?view_instructions','_self');</script>";
		}
	}
?>
<?php } ?>