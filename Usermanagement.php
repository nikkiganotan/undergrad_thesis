<?php
  include 'notification_fetch.php';
  include 'navigation.php';
  include 'Include/connection.php';
?>

<?php
session_start();
//Checking User Logged or Not
if(empty($_SESSION['user'])){
    header('location:index.php');
}

//timeout after 5 sec
if(isset($_SESSION['user'])) {
    if((time() - $_SESSION['last_time']) > 1800) {
      header("location:logout.php");  
    }
}


//Restrict User or Moderator to Access Admin.php page
if($_SESSION['user']['em_position']=='Office Staff'){
    header('location:dashboard.php');
}
?>
<?php


if(isset($_POST['id'])){
  
  $position = $_POST['position'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $id = $_POST['id'];
  //  query to update data 
   

    if($id == 1){
        $result  = mysqli_query($conn , "UPDATE employee SET
          password='$password', username = '$username'
          WHERE employee_id='$id'");

    }else{
        $result  = mysqli_query($conn , "UPDATE employee SET em_position='$position', 
        password='$password', username = '$username'
        WHERE employee_id='$id'");

    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" type="text/css" href="css/table.css">
  <link rel="stylesheet" type="text/css" href="css/custom.css">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/notification.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
  <link rel="stylesheet" type="text/css" href="css/navigation2.css">
  <link rel="stylesheet" type="text/css" href="css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="css/footer.css">
  <script src="js/ajax.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <title>User Management</title>
</head>
<body>
<div class="no-padding">
    <nav id="myNavbar" class="navbar nav-color" role="navigation">
      <div class="container">
          <div class="navbar-header">
              <a class="navbar-brand" href="dashboard.php">SIGMA</a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php
              echo navigate_it();
            ?>
              <ul class="nav navbar-nav navbar-right">
                <?php
                echo navigate_right();

                ?>
              </ul>
          </div>
      </div>
  </nav>
  <div class="container  pad-85">
    <h2 class="text-center p-u">USER MANAGEMENT</h2>
    <table class="table">
      <thead>
        <tr>
          <th class="my-bg text-white" >Username</th>
          <th class="my-bg text-white" >Password</th>
          <th class="my-bg text-white" >User Level</th>
          <th class="my-bg text-white" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
            $table  = mysqli_query($conn ,'SELECT * FROM employee WHERE employee_id');
            while($row  = mysqli_fetch_array($table)){ ?>
                <tr id="<?php echo $row['employee_id']; ?>">
                  <td data-target="username"><?php echo $row['username']; ?></td>
                  <td data-target="password"><?php echo $row['password']; ?></td>
                  <td data-target="position"><?php echo $row['em_position']; ?></td>
                  <td class="centered"><a href="#" data-role="update" data-id="<?php echo $row['employee_id'] ;?>">Update</a></td>
                  <td class="centered"><a href="./User/DeleteUser.php?employee_id=<?php echo $row['employee_id'] ;?>" >
                    Delete
                  </a></td>
                </tr>
           <?php }
         ?>
         <tr>
          <form action="User/adduser.php" method="POST">
            <td>
              <input class="u-text2" type="text" name="username" required>
            </td>
            <td>
              <input class="u-text2" type="password" name="password" required>  
            </td>
            <td>
              <select name="position">
                <option>Office Staff</option>
                <option>Operations Manager</option>
              </select>
            </td>
            <td class="centered" colspan="2"><input class="btn" type="submit" name="submit" value="Add new user"></td>
          </form>
         </tr>
      </tbody>
    </table>
  </div>
</div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit User</h4>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="username" class="form-control">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" id="password" class="form-control">
              </div>
              <div class="form-group">
                <label>Position</label>
                <select id="position" class="form-control">
                  <option selected disabled>----position status ----</option>
                  <option>Office Staff</option>
                  <option>Operations Manager</option>
                </select>
              </div>
                <input type="hidden" id="EmployeeId" class="form-control">
          </div>
          <div class="modal-footer">
            <a href="Usermanagement.php" id="save" class="btn btn-primary pull-right">Update</a>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

</body>

<script>
  $(document).ready(function(){

    //  append values in input fields
      $(document).on('click','a[data-role=update]',function(){

            var id  = $(this).data('id');
            var username  = $('#'+id).children('td[data-target=username]').text();
            var password  = $('#'+id).children('td[data-target=password]').text();
            var position  = $('#'+id).children('td[data-target=position]').text();


            $('#username').val(username);
            $('#password').val(password);
            $('#position').val(position);
            $('#EmployeeId').val(id);
            $('#myModal').modal('toggle');
      });

      // now create event to get data from fields and update in database 

       $('#save').click(function(){
          var id  = $('#EmployeeId').val(); 
          var position =  $('#position').val();
          var username =  $('#username').val();
          var password =  $('#password').val();

          $.ajax({
              url      : 'Usermanagement.php',
              method   : 'post',  
              data     : {id: id, username:username, password:password, position:position},

              success  : function(response){
                            // now update user record in table 
                             $('#'+id).children('td[data-target=username]').text(username);
                             $('#'+id).children('td[data-target=password]').text(password);
                             $('#'+id).children('td[data-target=position]').text(position);
                             $('#myModal').modal('toggle'); 

                         }
          });
       });
  });

</script>
</html>
