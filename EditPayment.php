<?php
      include 'notification_fetch.php'; 
      include 'navigation.php';
	include 'EditPaymentAction.php';
    $loan_idforR = mysqli_real_escape_string($db,$_POST['loan_idforR']);

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

header('Cache-Control: no cache');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <!-- OJT PROJECT CSS-->
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <script type="text/javascript" src="js/test.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <title>Edit Payment</title>

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
<div class="container pad-1">

            <h2 class="p-5 text-center">History of Transactions</h2>
            <br><br>

            <table class="table">
                    <thead class="text-white">
                        <tr>
                        <th class="my-bg" width="10%">Date</th>
                        <th class="my-bg">Amount Paid</th>
                        <th class="my-bg">Payment Type</th>
                        <th class="my-bg">Account Number</th>
                        <th class="my-bg">Check Number</th>
                        <th class="my-bg">Ref Number</th>
                        <th class="my-bg">Interest</th>
                        <th class="my-bg">Fines</th>
                        <th class="my-bg">Other Income</th>
                        <th class="my-bg" width="30%">Remarks</th>
                        <th class="my-bg">Status</th>
                        <th class="my-bg">Action</th>
                        </tr>
                    </thead>
  
  <!-- populate table from mysql database -->
                <?php
                $paymentInfo = mysqli_query($db, "SELECT other_income,payment_info_id,payment.remaining_balance, payment_info.payment_id, payment.due_date, payment.loan_id, payment_info.date_paid, payment_info.amount_paid, payment_info.payment_type, payment_info.account_number, payment_info.check_no, payment_info.ref_no, payment_info.interest, payment_info.fines, payment_info.remarks, payment_info.status FROM payment INNER JOIN payment_info ON payment_info.payment_id=payment.payment_id WHERE loan_id=".$loan_idforR." ORDER by payment_info_id DESC");
                while($row = mysqli_fetch_array($paymentInfo)) { ?>
                <tr>
                    <td><?php echo $row['date_paid'];?></td>
                    <td><?php echo $row['amount_paid'];?></td>
                    <td><?php echo $row['payment_type'];?></td>
                    <td><?php echo $row['account_number'];?></td>
                    <td><?php echo $row['check_no'];?></td>
                    <td><?php echo $row['ref_no'];?></td>
                    <td><?php echo $row['interest'];?></td>
                    <td><?php echo $row['fines'];?></td>
                     <td><?php echo $row['other_income'];?></td>
                    <td><?php echo $row['remarks'];?></td>
                    <td><?php echo $row['status'];?></td>
                    <td>
                    <form method="post" action="EditPaymentUpdate.php">
                       <input type="hidden" name="loan_idforR" value='<?php echo $loan_idforR ?>' />
                       <input type="hidden" name="edit" value='<?php echo $row['payment_info_id']; ?>' />
                       <input type="submit" value="Edit" />
                    </form>
                    </td>
                </tr>
                <?php } ;?>
            </table>
        </div>
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->

    <script>
        //custom select box

        $(function() {
            $('select.styled').customSelect();
        });

    </script>

</body>

</html>
