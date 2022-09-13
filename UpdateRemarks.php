<?php
$connection =	mysqli_connect('localhost' , 'root' ,'' ,'sigma');

if(isset($_POST['id'])){
	
	$remarks = $_POST['remarks'];
	$id = $_POST['id'];

	//  query to update data 
	 
	$result  = mysqli_query($connection , "UPDATE loan SET loan_remarks='$remarks' WHERE loan_id='$id'");

	if($result){
		echo 'data updated';
	}

}
?>