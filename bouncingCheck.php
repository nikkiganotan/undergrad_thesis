<?php
    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }


$check = mysqli_real_escape_string($con,$_POST['check']);
$loan_id = mysqli_real_escape_string($con,$_POST['loan_id']);
$search = mysqli_real_escape_string($con,$_POST['search']);

$sqlForRates = "SELECT * from rates";
    $rowRates = mysqli_fetch_assoc(mysqli_query($con,$sqlForRates));

$sqlForLate = "SELECT bi_monthly FROM loan WHERE loan_id='$loan_id'";
$row1 = mysqli_fetch_assoc(mysqli_query($con,$sqlForLate));
$bi_monthly = $row1['bi_monthly'];
$fine = $bi_monthly * $rowRates['penalty_bouncing_check']/100;

 if(!empty($check)){
$sql1 = "UPDATE payment_info SET status ='Bounced',remarks='Bouncing Check' WHERE check_no='$check'";
if (!mysqli_query($con,$sql1)) {
    echo "Error: " . mysqli_error($con);
}
 $sql = "INSERT INTO `payment_info`(`payment_id`,`fines`,remarks) VALUES ((SELECT payment_id FROM (SELECT * FROM payment_info) AS `payment_info` WHERE check_no='$check'),$fine,'Penalty for bouncing check#-$check');";
        if(!mysqli_query($con, $sql)){
            echo "Error: " . mysqli_error($con);
 }
     //edited
  $sql1 = "SELECT due_date FROM payment WHERE loan_id='$loan_id' && payment_id IN (SELECT payment_id FROM payment_info WHERE status='updated' && loan_id='$loan_id');";
    $result = mysqli_query($con,$sql1);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        While ($row = mysqli_fetch_assoc($result)){

            $sql2 = "UPDATE payment SET remaining_balance=(SELECT (loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$row['due_date']."' && payment.loan_id='$loan_id' && status='updated') WHERE loan_id='$loan_id' && due_date='".$row['due_date']."'";
            
             if (!mysqli_query($con,$sql2)) {
            echo "Error: " . mysqli_error($con);

            }
        }
    }   
 }
?>
<html> 
<body>
<form name='forSearch' action='search.php' method='POST'>
<input type=hidden name='search' value="<?php echo $search; ?>">  
</form>
</body>    

<script type='text/javascript'>
document.forSearch.submit();
</script>
</html>