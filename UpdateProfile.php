<?php
include 'Include/connection.php';

if(isset($_POST['id'])){
  
  $position = $_POST['position'];
  $employment = $_POST['employment'];
  $number = $_POST['number'];
  $presentAddress = $_POST['presentAddress'];
  $firmName = $_POST['firmName'];
  $spouse = $_POST['spouse'];
  $businessAddress = $_POST['businessAddress'];
  $id = $_POST['id'];

  //  query to update data 
   
  $result  = mysqli_query($conn , "UPDATE client SET position='$position', contact_no='$number', name_of_firm = '$firmName', present_address='$presentAddress', name_of_spouse='$spouse', business_address='$businessAddress' WHERE client_id='$id'");

  if($result){
    echo 'data updated';
  }

}
?>