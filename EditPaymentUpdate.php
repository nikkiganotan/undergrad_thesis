<?php
	include 'EditPaymentAction.php';
    include 'notification_fetch.php'; 
    include 'navigation.php';
?>
<!-- EDIT START -->
<?php 
    $loan_idforR = mysqli_real_escape_string($db,$_POST['loan_idforR']);

        $id = $_POST['edit'];
        $update = true;
        $record = mysqli_query($db, "SELECT other_income,payment_info_id,payment.remaining_balance, payment_info.payment_id, payment.due_date, payment.loan_id, payment_info.date_paid, payment_info.amount_paid, payment_info.payment_type, payment_info.account_number, payment_info.check_no, payment_info.ref_no, payment_info.interest, payment_info.fines, payment_info.remarks, payment_info.status FROM payment INNER JOIN payment_info ON payment_info.payment_id=payment.payment_id WHERE payment_info.payment_info_id='$id'");

        if (!empty($record)) {
            $n = mysqli_fetch_array($record);
            $datePaid = $n['date_paid'];
            $amtPaid = $n['amount_paid'];
            $paymentType = $n['payment_type'];
            $accNumber = $n['account_number'];
            $checkNumber = $n['check_no'];
            $refNumber =$n['ref_no'];
            $inter =$n['interest'];
            $fines =$n['fines'];
            $marks = $n['remarks'];
            $stats = $n['status'];
            $other_income = $n['other_income'];
        }
    
?>
<!-- EDIT END -->

<HTML>
    
<head>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
  <link rel="stylesheet" type="text/css" href="css/custom.css">
  <link rel="stylesheet" type="text/css" href="css/table.css">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/notification.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
  <link rel="stylesheet" type="text/css" href="css/navigation2.css">
  <script type="text/javascript" src="js/test.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

    <title>Edit Payment</title>
    </head>
<body>

 <div class="container">
        <!--main content start-->
    <section id="main-content">
        <section class="wrapper site-min-height">

            <!-- BASIC FORM ELELEMENTS -->
            <div class="row mt">
                <div class="col-lg-12">
                    <div class="form-panel">
                        <!-- START CREATE FORM -->
                        <form class="form-horizontal style-form" action="EditPaymentAction.php" method="POST">
                            
                            <input type="hidden" name="id" value="<?php echo $id ?>">
                            <input type="hidden" name="loan_id" value="<?php echo $loan_idforR ?>">


                            <center>
                                <h1 class="mb"><i class="fa fa-angle-right"></i>
                                    Edit Payment
                                </h1>
                            </center>

                            <div class="form-group">
                                <label>Date Paid</label>
                                <div>
                                    <input type="text" class="form-control" name="datePaid" value="<?= $datePaid ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Amount Paid</label>
                                <div>
                                    <input type="text" class="form-control" name="amountPaid" value="<?= $amtPaid ?>">
                                </div>
                            </div>
                            <!-- edited -->
                            <div class="form-group">
                                <label>Payment Type</label>
                                <div>
                                    <select name="paymentType" class="form-control selectpicker" >
                                        <option <?php if ($paymentType =='Cheque') echo "selected"?>>Cheque</option>
                                        <option <?php if ($paymentType =='Cash') echo "selected"?>>Cash</option>
                                        <option <?php if ($paymentType =='Bank Deposit') echo "selected"?>>Bank Deposit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Account Number</label>
                                <div>
                                    <input type="text" class="form-control" name="accountNo" value="<?= $accNumber ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Check No</label>
                                <div>
                                    <input type="text" class="form-control" name="checkNo" value="<?= $checkNumber ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Ref Number</label>
                                <div>
                                    <input type="number" class="form-control" name="refNo" value="<?= $refNumber ?>">
                                </div>
                            </div>

                           <div class="form-group">
                                <label>Interest</label>
                                <div>
                                    <input type="text" class="form-control" name="interest" value="<?= $inter ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Fines</label>
                                <div>
                                    <input type="text" class="form-control" name="fines" value="<?= $fines ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Other Income</label>
                                <div>
                                    <input type="text" class="form-control" name="other_income" value="<?= $other_income ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <div>
                                    <input type="text" class="form-control" name="remarks" value="<?= $marks ?>">
                                </div>
                            </div>

                            <button style="width: 20%;margin: auto;"class="btn btn-success btn-lg btn-block" type="submit" name="update">Update</button>

                        </form>
                        <!-- END CREATE FORM -->

                         

                    </div>
                </div>
            </div>

        </section>
    </section>
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
</HTML>
