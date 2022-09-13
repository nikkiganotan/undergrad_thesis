<?php


$username = $_POST['username'];
$password = $_POST['password'];
$position = $_POST['position'];


$conn = mysqli_connect('localhost' , 'root' ,'' ,'sigma');

$sql = "INSERT INTO employee (username, password, em_position)
VALUES ('$username', '$password', '$position')";


    $allUser = mysqli_query($conn,"SELECT username FROM employee WHERE username = '$username'");

	if(mysqli_num_rows($allUser) >= 1){
	    echo "<script>
	          alert('User is already registered');
	          window.location.href='../Usermanagement.php';
	          </script>";
	}else{
	    $result = mysqli_query($conn, $sql);

	    echo "<script>
	          alert('User Creation Success');
	          window.location.href='../Usermanagement.php';
	          </script>";
	}

?>