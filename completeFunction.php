<?php 

function searchComplete(){


if(isset($_POST['submit-complete'])){

    $output='';
    $conn=mysqli_connect('localhost','root','','sigma');
    $search = mysqli_real_escape_string($conn, $_POST['completeClient']);
    
    $sql1 = "SELECT * from loan join client on loan.client_id = client.client_id WHERE last_name LIKE '%$search%' || first_name LIKE '%$search%' || middle_name LIKE '%$search%' || concat(first_name,' ',middle_name,' ',last_name) LIKE '%$search%';";
    $result = mysqli_query($conn,$sql1);
    $resultCheck = mysqli_num_rows($result);

      if($resultCheck > 0){
          while ($row = mysqli_fetch_assoc($result)){
              $sql2 = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
              
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sql2));  
              $remaining = $rowRemain['rb']; 
              
              if($remaining <= 0){
                  $sqlForList = "SELECT concat(first_name,' ',middle_name,' ',last_name) as name,maturity_date,MAX(date_paid) as date_complete from client JOIN loan on client.client_id = loan.client_id JOIN payment on loan.loan_id = payment.loan_id JOIN payment_info on payment.payment_id = payment_info.payment_id WHERE payment.loan_id=".$row['loan_id']."";
              
                  $rowList = mysqli_fetch_assoc(mysqli_query($conn,$sqlForList));  
                  
                  $output.= '<tr>
                  <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$rowList["name"].'</a></td>
                  <td>0</td>
                  <td>'.$rowList["maturity_date"].'</td>
                  <td>'.$rowList["date_complete"].'</td>
                  <td>
                    <form method="post" action="transactions.php">
                         <input type="hidden" name="loan_id" value="'.$row["loan_id"].'" />
                         <input type="submit" value="Transactions" />
                      </form>
                  </td>    
                  </tr>';

              }
          }
      }else{
          $output .= ' <tr> <td>NO CLIENT WITH THAT NAME<td> </tr>';
      }
          
  
      return $output;  

      }
}

?>