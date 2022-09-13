<?php
$connection = mysqli_connect('localhost' , 'root' ,'' ,'sigma');


if(isset($_GET['client_id'])){
  
  $id = $_GET['client_id'];

  //  query to update data 
   
  $result  = mysqli_query($connection , "UPDATE client SET registered_status='Denied' WHERE client_id='$id'");
  header("location:OPListOfPendingClient.php"); 

}
?>