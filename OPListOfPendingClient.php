<?php

  include 'OFFunction.php';
  include 'notification_fetch.php'; 
  include 'navigation.php';


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

<!DOCTYPE html>
<html>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>List Of Pending</title>

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
            <div class="container pad-1">

                <h2 class="p-5 text-center">List of Pending Clients</h2>


                <h3>Pending Account</h3>
                <br>
                <br><br>
                <table class="table">
                    <thead class="text-white">
                        <tr>
                            <th class="my-bg">Account Name</th>
                            <th class="my-bg">Contact Number</th>
                            <th class="my-bg">Business Address</th>
                            <th class="my-bg">Present Address</th>
                            <th class="my-bg">Requested Amount</th>
                            <th class="my-bg">Status</th>
                            <th class="my-bg">Date Joined</th>
                            <th colspan="2" class="text-center my-bg">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            echo PendingList();

                        ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <div class="pagination-wrap pull-right">
                            <ul class="pagination pagination-v3">
                            <?php
                                echo page_pending();

                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Client Status</h4>
              </div>
              <div class="modal-body">

                  <select id="registered" class="form-control">
                    <option selected disabled>Pending</option>
                    <option>Approved</option>
                    <option>Denied</option>
                  </select>

                    <input type="hidden" id="ClientId" class="form-control">


              </div>
              <div class="modal-footer">
                <a href="OPListOfPendingClient.php" id="save" class="btn btn-primary pull-right">Update</a>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>
    </body>

</html>
