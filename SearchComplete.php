<?php  
    include 'completeFunction.php';
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
    <link rel="stylesheet" type="text/css" href="css/search.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <script src="js/ajax.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <title>List of Registered Clients</title>

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
                      <li>
                        <a href="notification.php">
                            <?php
                            if(count_data() > '0'){
                              echo count_data();
                            }
                           ?>
                           <img src="img/notifications-button.png" width="15px">
                        </a>
                      </li>
                    <?php
                      echo navigate_right();

                      ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container pad-1">
          <h3>Search Completed</h3>
            <br><br>
            <form action="SearchComplete.php" method="post">
                <div class="pad-2" id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input type="text" name="completeClient" class="search-query" placeholder="Search" id="myInput">
                        <div class="input-group-btn">
                            <button class="btn mybt mybtn2"  name="submit-complete"  type="submit" value="Search">
                              <img src="img/search2.png" width="15px">
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <br><br>
            <table class="table">
                <thead class="text-white">
                    <tr>
                        <th class="my-bg">Name</th>
                        <th class="my-bg">Remaining Balance</th>
                        <th class="my-bg">Maturity Date</th>
                        <th class="my-bg">Date Completed</th>
                        <th class="my-bg">Action</th>
                    </tr>
                </thead>
                <?php
                    echo searchComplete();
                    ?>
            </table>
        </div>
     
    </div>
    <script type="text/javascript" src="js/Table.js"></script>
    <script type="text/javascript" src="js/modal.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</body>

</html>
