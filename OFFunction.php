<?php

function Salary_Registered(){

    $output='';
    include './IncludeSalary/SalaryRegistered.php';
     while($row = mysqli_fetch_array($result))
      {   
        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
                $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                $remaining = $rowRemain['rb'];    
                $output .=  '
                      <tr>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$row["contact_no"].'</td>
                          <td>'.$row["payment_type"].'</td>
                          <td>'.$row["loan_balance"].'</td>
                          <td>'.$remaining.'</td>
                          <td>'.$row["date_booked"].'</td>
                          <td>'.$row["maturity_date"].'</td>
                     </tr>';

      }  
      return $output;  



}

function page_SalaryClientRegistered(){
  $output='';
  include './IncludeSalary/SalaryRegistered.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="OFListOfRegisteredClient.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

function Business_Registered(){

    $output='';
    include './IncludeBusiness/BusinessRegistered.php';
     while($row = mysqli_fetch_array($result))
      {       

                $output .=  '
                      <tr>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$row["contact_no"].'</td>
                          <td>'.$row["payment_type"].'</td>
                          <td>'.$row["loan_balance"].'</td>
                          <td>'.$row["remaining_balance"].'</td>
                          <td>'.$row["date_booked"].'</td>
                          <td>'.$row["maturity_date"].'</td>
                     </tr>';

      }  
      return $output;  



}

function page_BusinessClientRegistered(){
  $output='';
  include 'IncludeBusiness/BusinessRegistered.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="OFListOfRegisteredClient.php?BusinessPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

//---------------------------------------------------------Active Account--------------------------------------------------
function Salary_ActiveAccount(){

    $output='';
    include './IncludeSalary/SalaryActiveAccount.php';
    while($row = mysqli_fetch_array($result))
      {       
        $id = $row['loan_id'];

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
              $output .=  '
                    <tr id='. $row['loan_id'] .'>
                        <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                        <td>'.$remaining.'</td>
                        <td data-target="remarks">'.$row['loan_remarks'].'</td>
                        <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                    </tr>';
              }

          }
      return $output;

}

function Total_ActiveAccount(){
    
    $count = 0;
    $output='';
    include './Include/connection.php';
    
    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    WHERE (maturity_date > (select curdate())) 
    AND loan.delinquent_status = 'Active'
    AND registered_status='Approved' group by loan.loan_id";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_array($result))
      {       
        
        $id = $row['loan_id'];
        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
                
                $count+=$remaining;
                
            }
        
        }
    
    $output .= '<td>Total: </td>
            <td>'.$count.'</td>';
    return $output;
    
}


function page_Salary(){
  $output='';
  include './IncludeSalary/SalaryActiveAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORActiveAccount.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
   return $output;
}

function Business_ActiveAccount(){

    $output='';
    include './IncludeBusiness/BusinessActiveAccount.php';

     while($row = mysqli_fetch_array($result))
      {       
      $id = $row['loan_id'];

      $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];
          if ($remaining != '0' && $remaining > '0') {
            $output .=  '
                  <tr id='. $row['loan_id'] .'>  
                      <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                      <td>'.$remaining.'</td>
                      <td data-target="remarks">'.$row['loan_remarks'].'</td>
                      <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                      <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                  </tr>';
            }

        }  
      return $output;  
}

function page_Business(){
  $output='';
  include './IncludeBusiness/BusinessActiveAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORActiveAccount.php?BusinessPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}


//-----------------------------ActiveDelinquentAccount


function Salary_ActiveDelinquentAccount(){

    $output='';
    include './IncludeSalary/SalaryActiveDelinquentAccount.php';
    while($row = mysqli_fetch_array($result))
      {       
        
        if($_SESSION['user']['em_position']=='Operations Manager'){
        $id = $row['loan_id'];

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
              $output .=  '
                    <tr id='. $row['loan_id'] .'>  
                        <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                        <td>'.$remaining.'</td>
                        <td data-target="remarks">'.$row['loan_remarks'].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                        <td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                        <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                    </tr>';
              }
          }
          elseif ($_SESSION['user']['em_position']=='Office Staff') {

          $id = $row['loan_id'];

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];
              if ($remaining != '0' && $remaining > '0') {
                $output .=  '
                      <tr id='. $row['loan_id'] .'>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td data-target="remarks">'.$row['loan_remarks'].'</td>
                          <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                          <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                      </tr>';
                }
          }      

        }  
      return $output;

}

function Total_ActiveDelinquentAccount(){
    
    $count = 0;
    $output='';
    include './Include/connection.php';
    
    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    WHERE (maturity_date < (select curdate())) 
    AND loan.delinquent_status = 'Active'
    AND registered_status='Approved' group by loan.loan_id";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_array($result))
      {       
        
        $id = $row['loan_id'];
        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
                
                $count+=$remaining;
                
            }
        
        }
    
    $output .= '<td>Total: </td>
            <td>'.$count.'</td>';
    return $output;
    
}

function page_ActiveDelinquentSalary(){
  $output='';
  include './IncludeSalary/SalaryActiveDelinquentAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORActiveDelinquentAccount.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

function Business_ActiveDelinquentAccount(){

    $output='';
    include './IncludeBusiness/BusinessActiveDelinquentAccount.php';
    while($row = mysqli_fetch_array($result))
      {       
        
        if($_SESSION['user']['em_position']=='Operations Manager'){
        $id = $row['loan_id'];

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
              $output .=  '
                    <tr id='. $row['loan_id'] .'>  
                        <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                        <td>'.$remaining.'</td>
                        <td data-target="remarks">'.$row['loan_remarks'].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                        <td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                        <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                    </tr>';
              }
          }
          elseif ($_SESSION['user']['em_position']=='Office Staff') {

          $id = $row['loan_id'];

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];
              if ($remaining != '0' && $remaining > '0') {
                $output .=  '
                      <tr id='. $row['loan_id'] .'>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td data-target="remarks">'.$row['loan_remarks'].'</td>
                          <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                          <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                      </tr>';
                }
          }      

        } 
      return $output;
}

function page_ActiveDelinquentBusiness(){
  $output='';
  include './IncludeBusiness/BusinessActiveDelinquentAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORActiveDelinquentAccount.php?BusinessPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

//---------------------------------------------- Active Legal Account--------------------------------------------------

function Salary_ActiveLegalAccount(){

    $output='';
    include './IncludeSalary/SalaryActiveLegalAccount.php';
    while($row = mysqli_fetch_array($result))
      {       
        
        if($_SESSION['user']['em_position']=='Operations Manager'){
        $id = $row['loan_id'];

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
              $output .=  '
                    <tr id='. $row['loan_id'] .'>  
                        <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                        <td>'.$remaining.'</td>
                        <td data-target="remarks">'.$row['loan_remarks'].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                        <td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                        <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                    </tr>';
              }
          }
          elseif ($_SESSION['user']['em_position']=='Office Staff') {

          $id = $row['loan_id'];

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];
              if ($remaining != '0' && $remaining > '0') {
                $output .=  '
                      <tr id='. $row['loan_id'] .'>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td data-target="remarks">'.$row['loan_remarks'].'</td>
                          <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                          <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                      </tr>';
                }
          }      

        }  
      return $output;
}

function Total_Legal(){
    
    $count = 0;
    $output='';
    include './Include/connection.php';
    
    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    WHERE (maturity_date < (select curdate())) 
    AND loan.delinquent_status = 'Legal'
    AND registered_status='Approved' group by loan.loan_id";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_array($result))
      {       
        
        $id = $row['loan_id'];
        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
                
                $count+=$remaining;
                
            }
        
        }
    
    $output .= '<td>Total: </td>
            <td>'.$count.'</td>';
    return $output;
    
}

function page_SalaryLegal(){
  $output='';
  include './IncludeSalary/SalaryActiveLegalAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORActiveLegalAccount.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

function Business_ActiveLegalAccount(){

    $output='';
    include './IncludeBusiness/BusinessActiveLegalAccount.php';
    while($row = mysqli_fetch_array($result))
      {       
        
        if($_SESSION['user']['em_position']=='Operations Manager'){
        $id = $row['loan_id'];

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
              $output .=  '
                    <tr id='. $row['loan_id'] .'>  
                        <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                        <td>'.$remaining.'</td>
                        <td data-target="remarks">'.$row['loan_remarks'].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                        <td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                        <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                    </tr>';
              }
          }
          elseif ($_SESSION['user']['em_position']=='Office Staff') {

          $id = $row['loan_id'];

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];
              if ($remaining != '0' && $remaining > '0') {
                $output .=  '
                      <tr id='. $row['loan_id'] .'>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td data-target="remarks">'.$row['loan_remarks'].'</td>
                          <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                          <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                      </tr>';
                }
          }      

        }  
      return $output;
}

function page_BusinessLegal(){
  $output='';
  include './IncludeBusiness/BusinessActiveLegalAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORActiveLegalAccount.php?BusinessPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

//------------------------ Delinquent Account------------------

function Salary_DelinquentAccount(){

 $output='';
    include './IncludeSalary/SalaryDelinquentAccount.php';
    while($row = mysqli_fetch_array($result))
          {       
            
            if($_SESSION['user']['em_position']=='Operations Manager'){
            $id = $row['loan_id'];

            $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
                $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                $remaining = $rowRemain['rb'];
                if ($remaining != '0' && $remaining > '0') {
                  $output .=  '
                        <tr id='. $row['loan_id'] .'>  
                            <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                            <td>'.$remaining.'</td>
                            <td data-target="remarks">'.$row['loan_remarks'].'</td>
                            <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                            <td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                            <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                        </tr>';
                  }
              }
              elseif ($_SESSION['user']['em_position']=='Office Staff') {

              $id = $row['loan_id'];

              $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];
                  if ($remaining != '0' && $remaining > '0') {
                    $output .=  '
                          <tr id='. $row['loan_id'] .'>  
                              <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                              <td>'.$remaining.'</td>
                              <td data-target="remarks">'.$row['loan_remarks'].'</td>
                              <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                              <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                          </tr>';
                    }
              }      

            }
      return $output;

}

function page_DelinquentSalary(){
  $output='';
  include './IncludeSalary/SalaryDelinquentAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORDelinquentAccount.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

function Business_DelinquentAccount(){

    $output='';
    include './IncludeBusiness/BusinessDelinquentAccount.php';
while($row = mysqli_fetch_array($result))
      {       
        
        if($_SESSION['user']['em_position']=='Operations Manager'){
        $id = $row['loan_id'];

        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
              $output .=  '
                    <tr id='. $row['loan_id'] .'>  
                        <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                        <td>'.$remaining.'</td>
                        <td data-target="remarks">'.$row['loan_remarks'].'</td>
                        <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                        <td><a href="#" data-role="update" data-id='. $row['loan_id'] .'>Update</a></td>
                        <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                    </tr>';
              }
          }
          elseif ($_SESSION['user']['em_position']=='Office Staff') {

          $id = $row['loan_id'];

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];
              if ($remaining != '0' && $remaining > '0') {
                $output .=  '
                      <tr id='. $row['loan_id'] .'>  
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td data-target="remarks">'.$row['loan_remarks'].'</td>
                          <td><a href="#" data-role="edit" data-id='. $row['loan_id'] .'>Edit</a></td>
                          <td class= "hidden-td" data-target="status">'.$row["delinquent_status"].'</td>
                      </tr>';
                }
          }      

        }  
      return $output; 
}

function page_DelinquentBusiness(){
  $output='';
  include './IncludeBusiness/BusinessDelinquentAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="SORDelinquentAccount.php?BusinessPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

function Total_DelinquentAccount(){
    
    $count = 0;
    $output='';
    include './Include/connection.php';
    
    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    WHERE (maturity_date < (select curdate())) 
    AND loan.delinquent_status = 'Inactive'
    AND registered_status='Approved' group by loan.loan_id";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_array($result))
      {       
        
        $id = $row['loan_id'];
        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=$id";
            $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
            $remaining = $rowRemain['rb'];
            if ($remaining != '0' && $remaining > '0') {
                
                $count+=$remaining;
                
            }
        
        }
    
    $output .= '<td>Total: </td>
            <td>'.$count.'</td>';
    return $output;
    
}

//---------------------------------------List Of Pending-----------------------------

function PendingList(){

    $output='';
    include './Include/Pending.php';
     while($row = mysqli_fetch_array($result))
      {
             
        $output .=  '
              <tr id='. $row['client_id'] .'>  
                  <td>'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</td>
                  <td>'.$row["contact_no"].'</td>
                  <td>'.$row["business_address"].'</td>
                  <td>'.$row["present_address"].'</td>
                  <td>'.$row["requested_amount"].'</td>
                  <td data-target="registered">'.$row["registered_status"].'</td>
                  <td>'.$row["registered_date"].'</td>';
        if($_SESSION['user']['em_position']=='Operations Manager'){

        $output .=  '<td><a href="ClientLoan.php?client_id='.$row["client_id"].'">Approve</a></td>
                  <td><a href="ClientDenied.php?client_id='.$row["client_id"].'">Deny</a></td>

             </tr>';
          }


      }  
      return $output;

}

function page_pending(){
  $output='';
  include './Include/Pending.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="OPListOfPendingClient.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

//================================================================================Summary 
function Salary_Summary(){

    $output='';
    include './IncludeSalary/SalarySummary.php';
     while($row = mysqli_fetch_array($result))
      {    
         
         $loan_id = $row['loan_id'];
        $sql ="SELECT first_name, last_name, middle_name,date_booked, maturity_date, original_amount, 
        loan_balance as net_proceeds,
        ROUND(original_amount * 0.15 * (COUNT(due_date)/2), 2) as Interest_earned_3,
        ROUND(original_amount * 0.05 * (COUNT(due_date)/2), 2) as Interest_earned_1, 
        original_amount*0.03 as service_handling_fee, insurance +coalesce((SELECT SUM(other_income) from payment_info JOIN payment on payment.payment_id = payment_info.payment_id WHERE payment.loan_id='$loan_id' && status='Updated'),0) as other_income  from client 
        INNER JOIN loan on client.client_id = loan.client_id
        INNER JOIN payment on payment.loan_id = loan.loan_id 
        WHERE loan.loan_id='$loan_id' AND loan_type = 'Salary'";
        $rowSummary = mysqli_fetch_assoc(mysqli_query($conn,$sql));
         
                $output .=  '
                      <tr>
                          <td> <a href="Profile.php?loan_id='.$row["loan_id"].'">'.$rowSummary['first_name'].'  '.$rowSummary['middle_name'].' '.$rowSummary['last_name'].'</a></td>
                          <td>'.$rowSummary["date_booked"].'</td>
                          <td>'.$rowSummary["maturity_date"].'</td>
                          <td>'.$rowSummary["original_amount"].'</td>
                          <td>'.$rowSummary["net_proceeds"].'</td>
                          <td>'.$rowSummary["Interest_earned_3"].'</td>
                          <td>'.$rowSummary["Interest_earned_1"].'</td>
                          <td>'.$rowSummary["service_handling_fee"].'</td>
                          <td>'.$rowSummary["other_income"].'</td>
                     </tr>';

      }  
      return $output;  



}

function page_SalarySummary(){
  $output='';
  include './IncludeSalary/SalarySummary.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="Bookings.php?SalaryPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

function Business_Summary(){

    $output='';
    include './IncludeBusiness/BusinessSummary.php';
     while($row = mysqli_fetch_array($result))
      {    
         
         $loan_id = $row['loan_id'];
        $sql ="SELECT first_name, last_name, middle_name,date_booked, maturity_date, original_amount, 
        loan_balance as net_proceeds, ROUND(original_amount * 0.15 * (COUNT(due_date)/2), 2) as Interest_earned_3,
        ROUND(original_amount * 0.05 * (COUNT(due_date)/2), 2) as Interest_earned_1,
        original_amount*0.03 as service_handling_fee, insurance + coalesce((SELECT SUM(other_income) from payment_info JOIN payment on payment.payment_id = payment_info.payment_id WHERE payment.loan_id='$loan_id' && status='Updated'),0) as other_income  from client 
        INNER JOIN loan on client.client_id = loan.client_id
        INNER JOIN payment on payment.loan_id = loan.loan_id 
        WHERE loan.loan_id='$loan_id' AND loan_type = 'Business'";
        $rowSummary = mysqli_fetch_assoc(mysqli_query($conn,$sql));
         
        $output .=  '
              <tr>
                  <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$rowSummary['first_name'].'  '.$rowSummary['middle_name'].' '.$rowSummary['last_name'].'</a></td>
                  <td>'.$rowSummary["date_booked"].'</td>
                  <td>'.$rowSummary["maturity_date"].'</td>
                  <td>'.$rowSummary["original_amount"].'</td>
                  <td>'.$rowSummary["net_proceeds"].'</td>
                  <td>'.$rowSummary["Interest_earned_3"].'</td>
                  <td>'.$rowSummary["Interest_earned_1"].'</td>
                  <td>'.$rowSummary["service_handling_fee"].'</td>
                  <td>'.$rowSummary["other_income"].'</td>
             </tr>';

      }  
      return $output;  



}

function page_BusinessSummary(){
  $output='';
  include './IncludeBusiness/BusinessSummary.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="Bookings.php?BusinessPage=' . $page . '">' . $page . '</a></li>';
    }
    return $output;
}

?>
