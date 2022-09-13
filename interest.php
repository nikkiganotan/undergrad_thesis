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
$amount = mysqli_real_escape_string($con,$_POST['amount']);
$insurance = mysqli_real_escape_string($con,$_POST['insurance']);
//$insurance = mysqli_real_escape_string($con,$_POST['insurance']);
if($number > 0)
{
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
    //for rates
    $sqlForRates = "SELECT * from rates";
    $rowRates = mysqli_fetch_assoc(mysqli_query($con,$sqlForRates));
    $sqlForInsurance = "SELECT insurance from loan where loan_id='$loan_id'";
    $rowInsurance = mysqli_fetch_assoc(mysqli_query($con,$sqlForInsurance));
    $insuranceRow = $rowInsurance['insurance'];
   // $sqlForLoan = "SELECT * from loan WHERE loan_id='$loan_id'";
//    $rowLoan = mysqli_fetch_assoc(mysqli_query($con,$sqlForLoan));
  //  $updateInsurance = $insurance + $rowLoan['insurance'];
    
   $queryForInterest = "INSERT INTO payment_info(payment_id,interest) VALUES((SELECT payment_id FROM (SELECT * FROM payment) AS `payment` WHERE loan_id='$loan_id' && due_date='$due'),CEILING($amount*(".$rowRates['interest']."/100)*($number/2)))";
    mysqli_query($con,$queryForInterest);
  
    $queryforRB = "UPDATE payment SET remaining_balance=(SELECT CEILING((loan_balance+coalesce(SUM(fines),0)+coalesce(SUM(interest),0)-coalesce((SUM(amount_paid)),0))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE loan.loan_id='$loan_id' && status='updated'),date_modified='".date("Y-m-d")."' WHERE loan_id='$loan_id' && due_date='$due'";
    mysqli_query($con,$queryforRB);
   
    $queryforBiMonthly = "UPDATE loan SET bi_monthly=(($amount+($amount*(".$rowRates['interest']."/100)*($number/2)))/$number),maturity_date='".$_POST["due"][$number-1]."',insurance=$insuranceRow+$insurance WHERE loan_id=$loan_id";
   if (!mysqli_query($con,$queryforBiMonthly)) {
            echo "Error: " . mysqli_error($con);

     }  

	echo "Added interest is: ",($amount*($rowRates['interest']/100)*($number/2));
}
else
{
	echo "Please Enter Due Date";
}

