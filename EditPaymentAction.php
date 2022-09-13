<?php

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'sigma');


// initialize variables
    $id = 0;
    $payID = 0;
    $datePaid = "";
    $amtPaid = "";
    $paymentType = "";
    $accNumber = "";
    $checkNumber = "";
    $refNumber = "";
    $inter= "";
    $fines= "";
    $marks ="";
    $loan_idforR = "";
    $update = false;
$other_income;

//save

    //Update Start
    if(isset($_POST['update'])){
        $loan_id = $_POST['loan_id'];
        $payID = $_POST['id'];
        $datePaid = $_POST['datePaid'];
        $amtPaid = $_POST['amountPaid'];
        $paymentType = $_POST['paymentType'];
        $accNumber = $_POST['accountNo'];
        $checkNumber = $_POST['checkNo'];
        $refNumber = $_POST['refNo'];
        $marks = $_POST['remarks'];
        $fines = $_POST['fines'];
        $other_income = $_POST['other_income'];
        if($datePaid != ""){
       $sql = "UPDATE payment INNER JOIN payment_info ON payment.payment_id=payment_info.payment_id SET payment_info.date_paid='$datePaid',payment_info.fines='$fines',payment_info.other_income='$other_income', payment_info.amount_paid='$amtPaid', payment_info.payment_type='$paymentType', payment_info.account_number='$accNumber', payment_info.check_no='$checkNumber', payment_info.ref_no='$refNumber', payment_info.remarks='$marks', payment_info.status='Updated' WHERE payment_info.payment_info_id='$payID'"; 
            if(!mysqli_query($db, $sql)){
            echo "Error: " . mysqli_error($db);
        }
        }else{
           $sql = "UPDATE payment INNER JOIN payment_info ON payment.payment_id=payment_info.payment_id SET payment_info.date_paid=NULL,payment_info.fines='$fines',payment_info.other_income='$other_income', payment_info.amount_paid='$amtPaid', payment_info.payment_type='$paymentType', payment_info.account_number='$accNumber', payment_info.check_no='$checkNumber', payment_info.ref_no='$refNumber', payment_info.remarks='$marks', payment_info.status='Updated' WHERE payment_info.payment_info_id=1"; 
            if(!mysqli_query($db, $sql)){
            echo "Error: " . mysqli_error($db);
        } 
        }
        
        //edited
    $sql1 = "SELECT due_date FROM payment WHERE loan_id='$loan_id' && payment_id IN (SELECT payment_id FROM payment_info WHERE status='updated' && loan_id='$loan_id');";
    $result = mysqli_query($db,$sql1);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        While ($row = mysqli_fetch_assoc($result)){

            $sql2 = "UPDATE payment SET remaining_balance=(SELECT CEILING((loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid)))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$row['due_date']."' && payment.loan_id='$loan_id' && status='updated') WHERE loan_id='$loan_id' && due_date='".$row['due_date']."'";
            
             if (!mysqli_query($db,$sql2)) {
            echo "Error: " . mysqli_error($con);

            }
        }
    }    
        
    ?>
<html> 
<body>
<form name='forSearch' action='EditPayment.php' method='POST'>
<input type=hidden name='loan_idforR' value="<?php echo $loan_id; ?>">  
</form>
</body>    

<script type='text/javascript'>
document.forSearch.submit();
</script>
</html>

<?php }
?>  

