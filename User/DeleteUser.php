<?php 
$conn=mysqli_connect('localhost','root','','sigma');

if(isset($_GET['employee_id'])){

  $employee_id = $_GET['employee_id'];
    
  $allUser = mysqli_query($conn,"SELECT username FROM employee");

  if($employee_id == '1'){
      echo "<script>
            alert('Cannot Delete Operation Manager');
            window.location.href='../Usermanagement.php';
            </script>";
  }else{
        
      $query = mysqli_query($conn, "DELETE FROM employee WHERE employee_id = '$employee_id'");

      echo "<script>
            alert('Delete Success');
            window.location.href='../Usermanagement.php';
            </script>";
  }
}

?>
