<?php
    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }


$loan_id = mysqli_real_escape_string($con,$_POST['loan_id']);
$search = mysqli_real_escape_string($con,$_POST['search']);

$sql1 = "SELECT * from payment_info join payment on payment.payment_id=payment_info.payment_id WHERE remarks='Income from legal fees' and other_income != 0 && loan_id='$loan_id';";
    $result = mysqli_query($con,$sql1);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        While ($row = mysqli_fetch_assoc($result)){

            $sql2 = "UPDATE payment SET remaining_balance=(SELECT CEILING((loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid)))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$row['due_date']."' && payment.loan_id='$loan_id' && status='updated') WHERE loan_id='$loan_id' && due_date='".$row['due_date']."'";
            
            $updateExcused = "UPDATE payment_info SET status='Excused',remarks='Waived' WHERE remarks='Income from legal fees' && payment_id ='".$row['payment_id']."'";
                        
            if (!mysqli_query($con,$updateExcused) || !mysqli_query($con,$sql2)) {
            echo "Error: " . mysqli_error($con);

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