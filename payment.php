<?php


    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }
    $sql;
    $payment = mysqli_real_escape_string($con,$_POST['payment']);
    $date = mysqli_real_escape_string($con,$_POST['date']);
    $payment_type = mysqli_real_escape_string($con,$_POST['payment_type']);
    $loan_id = mysqli_real_escape_string($con,$_POST['loan_id']);
    $remarks = mysqli_real_escape_string($con,$_POST['remarks']);
    $choice = mysqli_real_escape_string($con,$_POST['choice']);
    $search = mysqli_real_escape_string($con,$_POST['search']);
    $account = mysqli_real_escape_string($con,$_POST['accNum']);
    $check = mysqli_real_escape_string($con,$_POST['checkNum']);
    $ref = mysqli_real_escape_string($con,$_POST['refNum']);

    

    //for rates
    $sqlForRates = "SELECT * from rates";
    $rowRates = mysqli_fetch_assoc(mysqli_query($con,$sqlForRates));


    
    //for Late Payment
    $sqlForLate = "SELECT bi_monthly FROM loan WHERE loan_id='$loan_id'";
    $row1 = mysqli_fetch_assoc(mysqli_query($con,$sqlForLate));
    $bi_monthly = $row1['bi_monthly'];

    //for Lack
    $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$loan_id";
    $rowRemain = mysqli_fetch_assoc(mysqli_query($con,$sqlForRemain));    
        


  //for Advance Payment
    $sqlForAdvance = "SELECT if(SUM(amount_paid)-($bi_monthly*(SELECT COUNT(DISTINCT(payment.payment_id)) from payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE loan_id = $loan_id && amount_paid != 0)+(SUM(fines)+SUM(interest)))>0,SUM(amount_paid)-($bi_monthly*(SELECT COUNT(DISTINCT(payment.payment_id)) from payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE loan_id = $loan_id && amount_paid != 0)+(SUM(fines)+SUM(interest)+other_income)),0) as `AP` from payment_info join payment ON payment_info.payment_id = payment.payment_id WHERE loan_id='$loan_id' && status='updated'";

    $row2 = mysqli_fetch_assoc(mysqli_query($con,$sqlForAdvance));
    $AP = $row2['AP'];
    $lack = $row1['bi_monthly'] * ($rowRates['penalty_lack']/100);

    $sqlForLack = "SELECT if(SUM(amount_paid)-($bi_monthly*(SELECT COUNT(DISTINCT(payment.payment_id)) from payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE loan_id = $loan_id && amount_paid != 0)+(SUM(fines)+SUM(interest)))<0,($bi_monthly*(SELECT COUNT(DISTINCT(payment.payment_id)) from payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE loan_id = $loan_id && amount_paid != 0)+(SUM(fines)+SUM(interest)+other_income))-SUM(amount_paid),0) as `lack` from payment_info join payment ON payment_info.payment_id = payment.payment_id WHERE loan_id='$loan_id' && status='updated'";

        $rowLack = mysqli_fetch_assoc(mysqli_query($con,$sqlForLack));

    $lackPayment = $rowLack['lack'];
/**  
    if($AP+$payment > ($bi_monthly+$row2['fines']+$row2['interest'])){
        
        $sql = "INSERT INTO `payment_info`(`payment_id`, `date_paid`, `amount_paid`, `payment_type`, `remarks`,`account_number`,`check_no`,`ref_no`) VALUES ((SELECT payment_id FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice') ,'$date',$bi, '$payment_type' ,'$remarks','$account','$check','$ref');";
        if(!mysqli_query($con,$sql)){
            echo "Error: " . mysqli_error($con);
        }
        if($advance <= $bi_monthly){
             $sqlAdvanceInsert = "INSERT into payment_info (`payment_id`,`payment_type`,`remarks`,`amount_paid`,`account_number`,`check_no`,`ref_no`) VALUES ((SELECT payment_id+1 FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice'),'$payment_type','Over payment from past due', $advance,'$account','$check','$ref')";   
             if(!mysqli_query($con,$sqlAdvanceInsert)){
                 echo "Error: " . mysqli_error($con);
             }
        }else{
            $ctr = 1;
            while($advance > $bi_monthly){
            $sqlForAdvanceWithFines = "SELECT SUM(amount_paid) as `AP`,fines,SUM(interest) as interest from payment_info join payment ON payment_info.payment_id = payment.payment_id WHERE loan_id='$loan_id' && status='updated' && payment.payment_id=(SELECT payment_id+$ctr FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice');";
            $rowAdvanceWithFines = mysqli_fetch_assoc(mysqli_query($con,$sqlForAdvanceWithFines)); 
                
            $biAdvance = ($bi_monthly+$rowAdvanceWithFines['fines']+$rowAdvanceWithFines['interest'])-$rowAdvanceWithFines['AP'];    
            $sqlAdvanceInsert = "INSERT into payment_info (`payment_id`,`payment_type`,`remarks`,`amount_paid`,`account_number`,`check_no`,`ref_no`) VALUES ((SELECT payment_id+$ctr FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice'),'$payment_type','Over payment from past due', CEILING($biAdvance),'$account','$check','$ref')";
            $advance = $advance - $biAdvance;
            $ctr++;
                if(!mysqli_query($con,$sqlAdvanceInsert)){
                 echo "Error: " . mysqli_error($con);
                }            
            }
            $sqlAdvanceInsert = "INSERT into payment_info (`payment_id`,`payment_type`,`remarks`,`amount_paid`,`account_number`,`check_no`,`ref_no`) VALUES ((SELECT payment_id+$ctr FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice'),'$payment_type','Over payment from past due', $advance,'$account','$check','$ref') ";
            if(!mysqli_query($con,$sqlAdvanceInsert)){
                 echo "Error: " . mysqli_error($con);
             }
        }
        
        
        
        
    //for Lacking Payment 
        
    }else**/ if($payment+$AP < ($bi_monthly+$lackPayment) && ($payment+$AP < $rowRemain['rb'])){
    
    $sqlLackingInsert = "INSERT INTO `payment_info`(`payment_id`, `date_paid`, `amount_paid`, `payment_type`, `remarks`, `fines`,`account_number`,`check_no`,`ref_no`) VALUES ((SELECT payment_id FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice'),'$date',$payment,'$payment_type', 'Payment was Lacking(Penalty)',CEILING($lack),'$account','$check','$ref');";
        if(!mysqli_query($con, $sqlLackingInsert)){
            echo "Error: " . mysqli_error($con);
        }
        
    }else{
    $sql = "INSERT INTO `payment_info`(`payment_id`, `date_paid`, `amount_paid`, `payment_type`, `remarks`,`account_number`,`check_no`,`ref_no`) VALUES ((SELECT payment_id FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$choice'),'$date',$payment,'$payment_type','$remarks','$account','$check','$ref');";
        if(!mysqli_query($con, $sql)){
            echo "Error: " . mysqli_error($con);
        }
            
    }







    $sql1 = "SELECT due_date FROM payment WHERE loan_id='$loan_id' && payment_id IN (SELECT payment_id FROM payment_info WHERE status='updated' && loan_id='$loan_id');";
    $result = mysqli_query($con,$sql1);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        While ($row = mysqli_fetch_assoc($result)){

            $sql2 = "UPDATE payment SET remaining_balance=(SELECT CEILING((loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid)))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$row['due_date']."' && payment.loan_id='$loan_id' && status='updated') WHERE loan_id='$loan_id' && due_date='".$row['due_date']."'";
            
             if (!mysqli_query($con,$sql2)) {
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