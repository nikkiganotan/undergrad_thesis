<?php
    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }

$number = count($_POST["due"]);
$due = mysqli_real_escape_string($con,$_POST['due']['0']);
$loan_id = mysqli_real_escape_string($con,$_POST['loan_id']);


$sqlForRates = "SELECT * from rates";
$rowRates = mysqli_fetch_assoc(mysqli_query($con,$sqlForRates));


$sqlForID = "SELECT count(due_date) as ctr,loan_balance,interest_rate,loan_class,original_amount,insurance from payment join loan on loan.loan_id = payment.loan_id where payment.loan_id = ".$loan_id."";
$rowForLoan = mysqli_fetch_assoc(mysqli_query($con,$sqlForID));

$sqlForRemain = "SELECT count(due_date) as due,coalesce(sum(amount_paid)-sum(fines)-sum(interest),0) as remain from loan join payment on payment.loan_id = loan.loan_id join payment_info on payment.payment_id = payment_info.payment_id where loan.loan_id =".$loan_id." && payment_type IS NOT NULL && status='UPDATED';";
$rowRemain = mysqli_fetch_assoc(mysqli_query($con,$sqlForRemain));

$remain = $rowRemain['remain'];
$count = $rowRemain['due'];
$loan_class = $rowForLoan['loan_class'];
$orig = $rowForLoan['original_amount'];
$interest = $rowForLoan['interest_rate'];
$mat_date = mysqli_real_escape_string($con, $_POST["due"][$number-1]);


if($number > 0)
{
        
    if($loan_class=="Add"){
            $loan_balance = $orig+($orig * ($interest/100) * (($number+$rowForLoan['ctr'])/2)) + ($orig * ($rowRates['service_handling_fee']/100));
    }else if($loan_Class=="Deducted"){
            $loan_balance = $orig;
    }
    
    $queryforRB = "UPDATE loan SET loan_balance=$loan_balance,bi_monthly =(CEILING(($loan_balance-$remain)/($number+".$rowForLoan['ctr']."-$count))),maturity_date='".$mat_date."' WHERE loan_id='$loan_id'";
    mysqli_query($con,$queryforRB);
    
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["due"][$i] == ''))
		{
                        $number--;
		}else{
            $sql = "INSERT INTO payment(due_date,loan_id,date_modified) VALUES('".mysqli_real_escape_string($con, $_POST["due"][$i])."',$loan_id,CURDATE())";
            
			if (!mysqli_query($con,$sql)) {
            echo "Error: " . mysqli_error($con);

            }  
        }

	}
    //edited
    $sqlUpdateBalance = "SELECT due_date FROM payment WHERE loan_id='$loan_id' && payment_id IN (SELECT payment_id FROM payment_info WHERE status='Updated');";
            $resultUpdateBalance = mysqli_query($con,$sqlUpdateBalance);
            $resultCheckBalance = mysqli_num_rows($resultUpdateBalance);
            if($resultCheckBalance > 0){
                While ($rowUpdateBalance = mysqli_fetch_assoc($resultUpdateBalance)){

                    $sqlUpdate = "UPDATE payment SET remaining_balance=(SELECT (loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$rowUpdateBalance['due_date']."' && payment.loan_id='$loan_id' && status='updated') WHERE loan_id='$loan_id' && due_date='".$rowUpdateBalance['due_date']."'";

                     if (!mysqli_query($con,$sqlUpdate)) {
                    echo "Error: " . mysqli_error($con);

                    }
                }
            }

	echo "Successfully added";
}
else
{
	echo "Please Enter Due Date";
}

