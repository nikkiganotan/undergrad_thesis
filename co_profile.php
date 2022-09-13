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

?>

<?php

if(isset($_POST['id'])){
  

  $firstName = $_POST['firstName'];
  $middleName = $_POST['middleName'];
  $lastName = $_POST['lastName'];
  $number = $_POST['number'];
  $presentAddress = $_POST['presentAddress'];
  $businessAddress = $_POST['businessAddress'];
  $position = $_POST['position'];
  $firmName = $_POST['firmName'];
  $id = $_POST['id'];

  //  query to update data 
   
  $result  = mysqli_query($conn , "UPDATE co_borrower SET co_first_name='$firstName',
    co_middle_name ='$middleName',
    co_last_name='$lastName',
    co_contact_no='$number',
    co_address='$presentAddress', 
    co_business_address='$businessAddress',
    co_name_of_firm = '$firmName',
    co_position = '$position'
    WHERE co_borrower_id='$id'");

  if($result){
    echo 'data updated';
  }

}
?>

<?php
// Check existence of id parameter before processing further
if(isset($_GET["co_borrower_id"]) && !empty(trim($_GET["co_borrower_id"]))){
    // Prepare a select statement
    $sql = 'SELECT group_concat(concat(first_name," ", middle_name ," ",last_name) separator "<br>") 
    as name, co_borrower.co_borrower_id, co_first_name, co_middle_name ,co_last_name, employment, group_concat(co_name_of_firm separator ", ") 
    as co_name_of_firm, group_concat(co_business_address separator ", ") 
    as co_business_address, co_position, co_address, co_contact_no 
    FROM client join loan on client.client_id = loan.loan_id join co_loan on loan.loan_id = co_loan.loan_id join co_borrower on co_borrower.co_borrower_id = co_loan.co_borrower_id 
    WHERE co_borrower.co_borrower_id = ?;';

    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["co_borrower_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $co_first_name = $row["co_first_name"];
                $co_first_name = $row["co_middle_name"];
                $co_last_name = $row["co_last_name"];
                $name_of_firm = $row["co_name_of_firm"];
                $business_address = $row["co_business_address"];
                $present_address = $row["co_address"];
                $contact_no = $row["co_contact_no"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($conn);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <script src="js/ajax.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
	<title></title>
</head>
<body>

 <nav id="myNavbar" class="navbar nav-color" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="dashboard.php">SIGMA</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php
                echo navigate_it()
            ?>
            <ul class="nav navbar-nav navbar-right">
                <?php
                  echo navigate_right();

                ?>
            </ul>
        </div>
    </div>
</nav> 
<div class="pad-1 container">
	<div class="row">
      <div class="col-xs-12">
        
        <!-- User profile -->
        <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">User info
          </h4>
          </div>
          <div class="panel-body">
            <div class="profile__avatar">
              <img src="img/user.svg" width="40px">
            </div>
            <div class="profile__header">
              <div class="fz-25">
                <?php echo $row["co_first_name"]; ?>
                <?php echo $row["co_middle_name"]; ?>
                <?php echo $row["co_last_name"]; ?>
              </div><div class="fz-15"><strong>Related Client:  </strong><?php echo $row["name"]; ?></div>
            </div>
          </div>
        </div>
        <!-- Co borrower info -->
        <div class="panel panel-default">
          <div class="panel-heading">
          <h4 class="panel-title">User info
            <a href="#" class="pull-right" data-role="update" data-id="<?php echo $row['co_borrower_id'] ;?>"><img src="img/edit.png" width="20x"></a>
          </h4>
          </div>
          <div class="panel-body">
            <table class="table profile__table">
              <tbody>
                <tr style="display: none;" id="<?php echo $row['co_borrower_id']; ?>">
                  <td data-target="firstName"><?php echo $row["co_first_name"];?></td>
                  <td data-target="middleName"><?php echo $row["co_middle_name"]; ?></td>
                  <td data-target="lastName"><?php echo $row["co_last_name"]; ?></td>
                  <td data-target="businessAddress"><?php echo $row["co_business_address"]; ?></td>
                  <td data-target="presentAddress"><?php echo $row["co_address"]; ?></td>
                  <td data-target="number"><?php echo $row["co_contact_no"]; ?></td>
                  <td data-target="position"><?php echo $row["co_position"]; ?></td>
                  <td data-target="firmName"><?php echo $row["co_name_of_firm"]; ?></td>
                </tr>

                <tr>
                  <th><strong>Co-Borrower ID</strong></th>
                  <td><?php echo $row["co_borrower_id"]; ?></td>
                </tr>
                <tr>
                  <th><strong>Position</strong></th>
                  <td><?php echo $row["co_position"]; ?></td>
                </tr>
                <tr>
                  <th><strong>Name of Firm</strong></th>
                  <td><?php echo $row["co_name_of_firm"]; ?></td>
                </tr>
                <tr>
                  <th><strong>Business Address</strong></th>
                  <td><?php echo $row["co_business_address"]; ?></td>
                </tr>
                <tr>
                  <th><strong>Home Address</strong></th>
                  <td><?php echo $row["co_address"]; ?></td>
                </tr>
                <tr>
                  <th><strong>Contact no.</strong></th>
                  <td><?php echo $row["co_contact_no"]; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="width: 100%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" id="firstName" class="form-control">
          </div>
          <div class="form-group">
            <label>Middle Name</label>
            <input type="text" id="middleName" class="form-control">
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" id="lastName" class="form-control">
          </div>
          <div class="form-group">
            <label>Position</label>
            <input type="text" id="position" class="form-control">
          </div>
          <div class="form-group">
            <label>Contact number</label>
            <input type="text" id="number" class="form-control">
          </div>
          <div class="form-group">
            <label>Firm Name</label>
            <input type="text" id="firmName" class="form-control">
          </div>
          <div class="form-group">
            <label>Present Address</label>
            <input type="text" id="presentAddress" class="form-control">
          </div>
          <div class="form-group">
            <label>Business Address</label>
            <input type="text" id="businessAddress" class="form-control">
          </div>
          <input type="hidden" id="ClientId" class="form-control">
      </div>
      <div class="modal-footer">
        <a href="co_profile.php?co_borrower_id=<?php echo $row['co_borrower_id']; ?>" id="save" class="btn btn-primary pull-right">Update</a>
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script>
  $(document).ready(function(){

    //  append values in input fields
      $(document).on('click','a[data-role=update]',function(){

            var id  = $(this).data('id');
            var number  = $('#'+id).children('td[data-target=number]').text();
            var firstName  = $('#'+id).children('td[data-target=firstName]').text();
            var middleName  = $('#'+id).children('td[data-target=middleName]').text();
            var lastName  = $('#'+id).children('td[data-target=lastName]').text();
            var presentAddress = $('#'+id).children('td[data-target=presentAddress]').text();
            var businessAddress  = $('#'+id).children('td[data-target=businessAddress]').text();
            var position  = $('#'+id).children('td[data-target=position]').text();
            var firmName = $('#'+id).children('td[data-target=firmName]').text();

            $('#firstName').val(firstName);
            $('#lastName').val(lastName);
            $('#middleName').val(middleName);
            $('#number').val(number);
            $('#presentAddress').val(presentAddress);
            $('#businessAddress').val(businessAddress);
            $('#position').val(position);
            $('#firmName').val(firmName);
            $('#ClientId').val(id);
            $('#myModal').modal('toggle');
      });

      // now create event to get data from fields and update in database 

       $('#save').click(function(){
          var id  = $('#ClientId').val(); 
          var number =  $('#number').val();
          var presentAddress = $('#presentAddress').val();
          var businessAddress = $('#businessAddress').val();
          var firstName = $('#firstName').val();
          var middleName = $('#middleName').val();
          var lastName = $('#lastName').val();
          var firmName = $('#firmName').val();
          var position = $('#position').val();


          $.ajax({
              url      : 'co_profile.php',
              method   : 'post',  
              data     : {id: id, 
                          number:number, 
                          presentAddress:presentAddress,
                          businessAddress:businessAddress,
                          firstName:firstName,
                          middleName:middleName,
                          lastName:lastName,
                          position:position,
                          firmName:firmName},

              success  : function(response){
                            // now update user record in table 
                             $('#'+id).children('td[data-target=number]').text(number);
                             $('#'+id).children('td[data-target=presentAddress]').text(presentAddress);
                             $('#'+id).children('td[data-target=businessAddress]').text(businessAddress);
                             $('#'+id).children('td[data-target=firstName]').text(firstName);
                             $('#'+id).children('td[data-target=middleName]').text(middleName);
                             $('#'+id).children('td[data-target=lastName]').text(lastName);
                             $('#'+id).children('td[data-target=firmName]').text(firmName);
                             $('#'+id).children('td[data-target=position]').text(position);
                             $('#myModal').modal('toggle'); 

                         }
          });
       });
  });

</script>
</body>
</html>