<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

if(isset($_GET['remove_plan'])){
	
$delete_id = $input->get('remove_plan');
	
$delete_plan = $db->delete("membership_table",array('id' => $delete_id));

if($delete_plan){

$insert_log = $db->insert_log($admin_id,"membership_plan",$delete_id,"deleted");

echo "<script>alert('Plan has been Deleted.');</script>";

echo "<script>window.open('index?membership_plans','_self');</script>";

}


}

?>

<?php } ?>