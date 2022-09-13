<?php 

function searchDelinquent(){


if(isset($_POST['submit-summary'])){

    
    $output='';
    $conn=mysqli_connect('localhost','root','','sigma');
    $search = mysqli_real_escape_string($conn, $_POST['summaryDelinquents']);
    $query = "SELECT loan.loan_id, first_name, middle_name, last_name, 
    bi_monthly, date_booked, maturity_date, delinquent_status,loan_remarks
    from client 
    inner join loan on client.client_id = loan.client_id 
    inner join payment on loan.loan_id = payment.loan_id
    WHERE registered_status='Approved' AND concat(first_name,middle_name,last_name) LIKE '%$search%'  group by loan.loan_id";

    $result = mysqli_query($conn, $query);

     while($row = mysqli_fetch_array($result))
      {      

          $id = $row['loan_id'];

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];
              if ($remaining!='0') {
                $output .=  '
                      <tr id='. $row['loan_id'] .'>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td>'.$row["bi_monthly"].'</td>';
                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) AND loan.loan_id=$id ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));

            $output .=  '
                        <td>'.$datePaidRemarks["date_paid"].'</td>
                        <td>'.$row["date_booked"].'</td>
                        <td>'.$row["maturity_date"].'</td>
                        <td data-target="remarks">'.$row["loan_remarks"].'</td>
                        <td data-target="status">'.$row["delinquent_status"].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>';
                        if ($_SESSION['user']['em_position']=='Operations Manager') {
                          if ($row["maturity_date"] < date('Y-m-d')) {
                                $output .='<td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                                      </tr>';
                        }
                }
             }          
          }
  
      return $output;  

      }
}

function ListOfDelinquents(){
    $output='';
    include './Include/Delinquents.php';

    while ($id = mysqli_fetch_assoc($result)) {

      $loanID = $id['loan_id'];

        $query1 = "SELECT loan.loan_id as loan, client.client_id 
        as client,concat(first_name,' ',middle_name,' ',last_name) as `account_name`,
        maturity_date from loan
        inner join client on client.client_id = loan.client_id
        inner join payment on payment.loan_id = loan.loan_id
        WHERE loan.loan_id = '$loanID' group by loan.loan_id ORDER BY account_name";

      $result1 = mysqli_query($conn, $query1);
      
  
      while($row = mysqli_fetch_assoc($result1))
      { 

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining > 0){
            $output.='
                  <tr>  
                  <td><a href="Profile.php?loan_id='.$row["loan"].'">'.$row["account_name"].'</a></td>';
 
                  $forCoBorrower = "SELECT co_borrower.co_borrower_id,concat(co_first_name,' ', co_middle_name,' ',co_last_name) as name from co_borrower 
                  join co_loan on co_borrower.co_borrower_id = co_loan.co_borrower_id
                  where co_loan.loan_id ='".$row['loan']."'";
                  $coBorrower = mysqli_query($conn, $forCoBorrower);
                  $number_of_results = mysqli_num_rows($coBorrower);
                  while($index = mysqli_fetch_array($coBorrower)){
                    if($number_of_results == 1){

              $output.='
                    <td>'.$index['name'].'</td>
                    <td></td>';

                  }else{

                $output.='
                    <td>'.$index['name'].'</td>';

                  }
            }

            if (empty($number_of_results)) {

            $output.='<td></td>
                      <td></td>';
          }
              $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $loanID && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$loanID) && (maturity_date > (select curdate()) AND loan.loan_id=$loanID) ORDER by payment_info.payment_id ASC;";
              $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));  

          $output.='<td>'.$remaining.'</td> 
                    <td>'.$row["maturity_date"].'</td>
             </tr>';
        }
      }
    }
  return $output;
}

function page_delinquent(){
  $output='';
  include './Include/Delinquents.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="ListOfDelinquents.php?Page=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

?>