<?php

  include 'OFFunction.php';
  include 'notification_fetch.php'; 
  include 'navigation.php';

$con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }

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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/search.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <script src="js/ajax.js"></script>
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
            
<script type="text/javascript" src="js/modal.js"></script>
    <script type="text/javascript" src="js/custom.js"></script> 
            <div class="container pad-1">

                <h2 class="p-5 text-center">List of Clients with Completed Payments</h2>

                <br>
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
                    <tbody>
                    <?php
                    $results_per_page = 10;                        
                        $sql1 = "SELECT loan_id,client_id from loan;";
                        $result = mysqli_query($con,$sql1);
                        $resultCheck = mysqli_num_rows($result);

                        if($resultCheck > 0){
                            $number_of_results = mysqli_num_rows($result);
                            $number_of_pages = ceil($number_of_results/$results_per_page);
                            // determine which page number visitor is currently on
                            if (!isset($_GET['page'])) {
                              $page = 1;
                            } else {
                              $page = $_GET['page'];
                            }
                            // determine the sql LIMIT starting number for the results on the displaying page
                            $this_page_first_result = ($page-1)*$results_per_page;
                            $sql='SELECT loan_id,client_id from loan LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
                            $result = mysqli_query($con, $sql);

                            While ($row = mysqli_fetch_assoc($result)){
                                $sql2 = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
                                
                                $rowRemain = mysqli_fetch_assoc(mysqli_query($con,$sql2));  
                                $remaining = $rowRemain['rb']; 
                                
                                if($remaining <= 0){
                                    $sqlForList = "SELECT concat(first_name,' ',middle_name,' ',last_name) as name,maturity_date,MAX(date_paid) as date_complete from client JOIN loan on client.client_id = loan.client_id JOIN payment on loan.loan_id = payment.loan_id JOIN payment_info on payment.payment_id = payment_info.payment_id WHERE payment.loan_id=".$row['loan_id']."";
                                
                                    $rowList = mysqli_fetch_assoc(mysqli_query($con,$sqlForList));  
                                    ?>
                                    <tr>
                                        <td><a href="Profile.php?loan_id=<?php echo $row["loan_id"]?> "><?php echo $rowList['name']?></a></td>
                                    <td>0</td>
                                    <td><?php echo $rowList['maturity_date']?></td>
                                    <td><?php echo $rowList['date_complete']?></td>
                                    <td> 
                                        <form method="post" action="transactions.php">
                                           <input type="hidden" name="loan_id" value='<?php echo $row['loan_id']; ?>' />
                                           <input type="submit" class="btn" value="Transactions" />
                                        </form>
                                        </td>    
                                    </tr>
                    <?php
                                }
                            }
                        }
                    
                    
                    ?>
                        
                        
                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <div class="pagination-wrap pull-right">
                            <ul class="pagination pagination-v3">
                                <?php
                                    // display the links to the pages
                                    for ($page=1;$page<=$number_of_pages;$page++) {
                                      echo '<LI><a href="completedLoans.php?page=' . $page . '">' . $page . '</a></li>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>


</html>
