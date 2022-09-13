<?php

$output = '';  
$con = mysqli_connect("localhost", "root", "", "sigma"); 
$loan_id =$_POST['loan'];




//=============================================================================================Active Account

if(isset($_POST["exportTransaction"]))
 {
  
    
 	$sql1 = "SELECT * FROM client join loan on client.client_id = loan.client_id where loan_id='".$loan_id."';";
    $result2 = mysqli_query($con,$sql1);
    $resultCheck2 = mysqli_num_rows($result2);
    if($resultCheck2 > 0){

            While ($row1 = mysqli_fetch_assoc($result2)){
                $sql2 = "SELECT due_date,remaining_balance FROM payment WHERE loan_id='".$row1['loan_id']."' ORDER by due_date ASC";
                $result3 = mysqli_query($con,$sql2);
                $resultCheck1 = mysqli_num_rows($result3);
                $firstName = $row1['first_name'];
                $middleName = $row1['middle_name'];
                $lastName = $row1['last_name'];

                $output .= '<table class="table" border=1>
                    <tr>
                    <td colspan=7><strong><font size=2>NAME:</font></strong> 
                      '.$firstName.' '.$middleName.' '.$lastName.'
                      <td>
                    </tr>';
                
                $output .= '
                    <thead class="text-white">
                    <tr>
                    <th style="font-size: 15px " colspan=3>
                    <strong>Date Booked: </strong>'.$row1['date_booked'].'
                    </th>
                    <th style="font-size: 15px " colspan=2>
                    <strong>Bi-Monthly Payment:</strong>'.$row1['bi_monthly'].'
                    </th>
                    <th style="font-size: 15px " colspan=3>
                    <strong>Maturity Date:</strong>'.$row1['maturity_date'].'
                    </th>
                    </tr>

                    <tr>
                      <th class="my-bg">Date</th>
                      <th class="my-bg">Check # EW</th>
                      <th class="my-bg">Ref/OR#</th>
                      <th class="my-bg">Payment</th>
                      <th class="my-bg">Interest</th>
                      <th class="my-bg">Fines</th>
                      <th class="my-bg">Balance</th>
                      <th class="my-bg">Other Income</th>
                    </tr>
                  </thead>';

                if($resultCheck1 > 0){
                    While ($row3 = mysqli_fetch_assoc($result3)){
                        $sqlForPayInfo = "SELECT payment.payment_id as pid,GROUP_CONCAT(payment_type) as payment_type,GROUP_CONCAT(check_no) as check_no,GROUP_CONCAT(ref_no) as ref_no,SUM(amount_paid) as amount_paid,SUM(interest) as interest,SUM(fines) as fines,GROUP_CONCAT(remarks) as remarks,SUM(other_income) as other_income FROM payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE (status ='updated' || status is NULL) && due_date='".$row3['due_date']."' && loan_id='".$row1['loan_id']."'; ";
                        $rowForPayInfo = mysqli_fetch_assoc(mysqli_query($con,$sqlForPayInfo));
                        
                        $sqlForCheck = "SELECT check_no from payment_info JOIN payment on payment.payment_id = payment_info.payment_id WHERE loan_id='".$row1['loan_id']."' && check_no IS NOT NULL && status= 'Updated'";
                    
                            $output .='<tbody id="myTable">
                            <tr>
                              <td>'.$row3['due_date'].'</td> 
                              <td>'.$rowForPayInfo['check_no'] .'</td>
                              <td>'.$rowForPayInfo['ref_no'].'</td>
                              <td>'.$rowForPayInfo['amount_paid'].'</td>
                              <td>'.$rowForPayInfo['interest'].'</td>
                              <td>'.$rowForPayInfo['fines'].'</td>
                              <td>'.$row3['remaining_balance'].'</td>
                              <td>'.$rowForPayInfo['other_income'].'</td>
                            </tr>
                            </tbody>  ';

                  }
                }
                $output .= '<tr/>';
            }
    }

      $output .= '</table>';
      header("Content-Type:application/xls");
      header("Content-Disposition: attachment; filename=".$firstName." ".$middleName." ".$lastName.".xls");
      echo $output;


  }

 
 ?>