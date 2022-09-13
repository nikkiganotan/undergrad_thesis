<?php


function filterTableList($query)
{
    $connect = mysqli_connect("localhost", "root", "", "sigma");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}


/*------------------------------------------------------For List Of Delinquents-----------------------------------------------------*/

function ListOfDelinquents() 
 {

  $output='';

  if(isset($_POST['searchDelinquents']))
  {
  include("SORDelinquentAccount.php");
    $valueToSearchDelinquents = $_POST['valueTosearchDelinquents'];
    // search in all table columns
    // using concat mysql functio

    $query = "SELECT * from loan
              inner join client on client.client_id = loan.client_id 
              inner join occupation on client.client_id = occupation.client_id 
              inner join co_borrower on occupation.co_borrower_id = co_borrower.co_borrower_id
              WHERE 
              concat(last_name, first_name, outstanding_balance, remarks) 
              LIKE '%".$valueToSearchDelinquents."%'";
    
    $search_result_delinquents = filterTableList($query);
    
  }
  else {
    $query = "SELECT * from loan 
              inner join client on client.client_id = loan.client_id 
              inner join occupation on client.client_id = occupation.client_id 
              inner join co_borrower on occupation.co_borrower_id = co_borrower.co_borrower_id";

    $search_result_delinquents = filterTableList($query);
  } 
     while($row = mysqli_fetch_array($search_result_delinquents))
      {       
          if ($row["maturity_date"] < date("Y-m-d") && $row["loan_balance"] != '0'){

                $output .=  '
                      <tr>  
                          <td>'.$row["first_name"].' '.$row["last_name"].'</td>
                          <td>'.$row["co_first_name"].' '.$row["co_last_name"].'</td> 
                          <td>'.$row["loan_balance"].'</td>  
                          <td>'.$row["maturity_date"].'</td>
                     </tr>';
          }


      }  
      return $output;  
 }


?>