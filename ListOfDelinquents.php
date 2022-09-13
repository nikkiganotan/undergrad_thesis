<?php  
    include 'delinquentFunction.php';
    include 'navigation.php';
    include 'notification_fetch.php';
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
    <link rel="stylesheet" type="text/css" href="css/search.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <script src="js/ajax.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>List of Delinquents</title>

</head>

<body>
    <div class=" no-padding">
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

            <h2 class="p-5 text-center">List of Delinquents</h2>
      
            <br><br>

            <table class="table">
                <thead class="text-white">
                    <tr>
                        <th class="my-bg text-white">Account Name</th>
                        <th class="my-bg text-white" >Co Borrower</th>
                        <th class="my-bg text-white" >Co Borrower 2</th>
                        <th class="my-bg text-white">Balance</th>
                        <th class="my-bg text-white" >Date</th>
                    </tr>
                </thead>
                <?php
                  echo ListOfDelinquents();
                  ?>                   
            </table>
            <div class="row">
                <div class="col">
                    <div class="pagination-wrap pull-right">
                        <ul class="pagination pagination-v3">

                        <?php
                            echo page_delinquent();

                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <button onclick="document.getElementById('id01').style.display='block'" class="reports"><img src="img/report.png" width="30px"></button>
          <div id="id01" class="w3-modal">
              <div class="w3-modal-content">
                  <div class="w3-container p-5">
                      <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                      <form class="text-center" method="POST" action="excel.php">
                          <h2>Monthly Reports</h2>
                          <div class="py-3 ">
                              <input class="b-2" name="generate_Delinquents" type="submit" value="List Of Delinquents">
                          </div>
                          <div class="py-3 ">
                              <input class="b-2" name="AgingRecievable" type="submit" value="Aging of Receivable">
                          </div>
                          <div class="py-3 ">
                              <input class="b-2" name="SummaryOfRecievable" type="submit" value="Summary Of Receivable">
                          </div>
                          <h2 style="padding-top: 10px;">Summary of Bookings<br>Monthy Report</h2>
                          <input class="i-2" type="month" name="testDate" id="myMonth">
                          <div class="py-3 ">
                              <input class="b-2" name="generate_Bookings" type="submit" value="Summary of bookings">
                          </div>
                      </form>
                  </div>
              </div>
          </div>
    </div>
    <script type="text/javascript" src="js/Table.js"></script>
    <script type="text/javascript" src="js/modal.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</body>

</html>
