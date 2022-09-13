<?php
    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }


$start = mysqli_real_escape_string($con,$_POST['start']);
$loan_id = mysqli_real_escape_string($con,$_POST['loan_id']);
$end = mysqli_real_escape_string($con,$_POST['end']);
$search = mysqli_real_escape_string($con,$_POST['search']);

//edited added loan_id
$sql1 = "SELECT payment_info_id as p_id,due_date from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id WHERE due_date BETWEEN '$start' and '$end' && remarks='Penalty after maturity date(System)' && loan_id='$loan_id';";
    $result = mysqli_query($con,$sql1);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        While ($row = mysqli_fetch_assoc($result)){

            $sql2 = "UPDATE payment SET remaining_balance=(SELECT CEILING((loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid)))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$row['due_date']."' && payment.loan_id='$loan_id' && status='updated') WHERE loan_id='$loan_id' && due_date='".$row['due_date']."'";
            
            $updateExcused = "UPDATE payment_info SET status='Excused',remarks='Waived' WHERE remarks='Penalty after maturity date(System)' && payment_info_id ='".$row['p_id']."'";
                        
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