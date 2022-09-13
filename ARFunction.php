<?php

function movingAccount(){

    $output='';
    $count1=0;
    $count2=0;
    $count3=0;
    $count4=0;
    include 'IncludeAging/MovingAccount.php';
     while($row = mysqli_fetch_array($result))
      {   
          $id = $row['loan_id'];
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id='$id'";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];

          if ($remaining>'0'){

                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) && (maturity_date < (select curdate()) AND loan.loan_id=$id) AND loan.delinquent_status = 'Inactive' ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));
                            $date_paid=$datePaidRemarks['date_paid'];
                            $remarks=$row['maturity_date'];
            
                $query2="SELECT DATE_ADD('$remarks', INTERVAL 30 DAY) as maturity_date FROM sigma.payment_info";
                $interval1 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
                $query3="SELECT DATE_ADD('$remarks', INTERVAL 60 DAY) as maturity_date FROM sigma.payment_info";
                $interval2 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
                $query4="SELECT DATE_ADD('$remarks', INTERVAL 90 DAY) as maturity_date FROM sigma.payment_info";
                $interval3 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
                $query5="SELECT DATE_ADD('$remarks', INTERVAL 120 DAY) as maturity_date FROM sigma.payment_info";
                $interval4 = mysqli_fetch_assoc(mysqli_query($conn,$query5));

              if ($interval1['maturity_date'] <= date("Y-m-d")) {

            $output .='   <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td></td>
                          <td></td>
                          <td></td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';
              
              }elseif ($interval2['maturity_date'] <= date("Y-m-d")){
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td>'.$remaining.'</td>
                          <td></td>
                          <td></td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';

              }elseif($interval3['maturity_date'] <= date("Y-m-d")){
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td></td>
                          <td>'.$remaining.'</td>
                          <td></td>';

                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';           

              }else{
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>'.$remaining.'</td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';            

              }

          }
        }

      return $output;

}

function TotalMovingAccount(){

    $output='';
    $count1=0;
    $count2=0;
    $count3=0;
    $count4=0;
    include ('./Include/connection.php');

    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    inner join payment on loan.loan_id = payment.loan_id
    WHERE (maturity_date < (select curdate())
    AND loan.delinquent_status = 'Active')
    AND registered_status='Approved' group by loan.loan_id";
     $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result))
      {   
          $id = $row['loan_id'];
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id='$id'";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];

          if ($remaining>'0'){

                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) && (maturity_date < (select curdate()) AND loan.loan_id=$id) AND loan.delinquent_status = 'Active' ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));
                            $date_paid=$datePaidRemarks['date_paid'];
                            $remarks=$row['maturity_date'];

                $query2="SELECT DATE_ADD('$remarks', INTERVAL 30 DAY) as maturity_date FROM sigma.payment_info";
                $interval1 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
                $query3="SELECT DATE_ADD('$remarks', INTERVAL 60 DAY) as maturity_date FROM sigma.payment_info";
                $interval2 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
                $query4="SELECT DATE_ADD('$remarks', INTERVAL 90 DAY) as maturity_date FROM sigma.payment_info";
                $interval3 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
                $query5="SELECT DATE_ADD('$remarks', INTERVAL 120 DAY) as maturity_date FROM sigma.payment_info";
                $interval4 = mysqli_fetch_assoc(mysqli_query($conn,$query5));

              if ($interval1['maturity_date'] <= date("Y-m-d")) {

              $count1+=$remaining;
              
              }elseif ($interval2['maturity_date'] <= date("Y-m-d")){

              $count2+=$remaining;

              }elseif($interval3['maturity_date'] <= date("Y-m-d")){
              
              $count3+=$remaining;

              }else{

              $count4+=$remaining;
           

              }

          }
        }
              $output .='<tr>
                            <td>TOTAL</td>
                            <td>'.$count1.'</td>
                            <td>'.$count2.'</td>
                            <td>'.$count3.'</td>
                            <td>'.$count4.'</td>
                            <td><td>
                            <td><td>
                        </tr>';

      return $output;

}

function moving_page(){
  $output='';
  include 'IncludeAging/MovingAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="ARMoving.php?MovingAccountPage=' . $page . '">' . $page . '</a></li>';
    }
   return $output;
}



//----------------------------------------------------------Not Moving

function NotMovingAccount(){

    $output='';
    include 'IncludeAging/NotMovingAccount.php';
     while($row = mysqli_fetch_array($result))
      {   
          $id = $row['loan_id'];
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id='$id'";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];

          if ($remaining>'0'){

                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) && (maturity_date < (select curdate()) AND loan.loan_id=$id) AND loan.delinquent_status = 'Inactive' ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));
                            $date_paid=$datePaidRemarks['date_paid'];
                            $remarks=$row['maturity_date'];
            
                $query2="SELECT DATE_ADD('$remarks', INTERVAL 30 DAY) as maturity_date FROM sigma.payment_info";
                $interval1 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
                $query3="SELECT DATE_ADD('$remarks', INTERVAL 60 DAY) as maturity_date FROM sigma.payment_info";
                $interval2 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
                $query4="SELECT DATE_ADD('$remarks', INTERVAL 90 DAY) as maturity_date FROM sigma.payment_info";
                $interval3 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
                $query5="SELECT DATE_ADD('$remarks', INTERVAL 120 DAY) as maturity_date FROM sigma.payment_info";
                $interval4 = mysqli_fetch_assoc(mysqli_query($conn,$query5));

              if ($interval1['maturity_date'] <= date("Y-m-d")) {

            $output .='   <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td></td>
                          <td></td>
                          <td></td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';
              
              }elseif ($interval2['maturity_date'] <= date("Y-m-d")){
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td>'.$remaining.'</td>
                          <td></td>
                          <td></td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';

              }elseif($interval3['maturity_date'] <= date("Y-m-d")){
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td></td>
                          <td>'.$remaining.'</td>
                          <td></td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';             

              }else{
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>'.$remaining.'</td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';            

              }

          }
        }
      return $output;

}

function TotalNotMovingAccount(){

    $output='';
    $count1=0;
    $count2=0;
    $count3=0;
    $count4=0;
    include ('./Include/connection.php');
    
    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    inner join payment on loan.loan_id = payment.loan_id
    WHERE (maturity_date < (select curdate())
    AND loan.delinquent_status = 'Inactive')
    AND registered_status='Approved' group by loan.loan_id";
     $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result))
      {   
          $id = $row['loan_id'];
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id='$id'";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];

          if ($remaining>'0'){

                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) && (maturity_date < (select curdate()) AND loan.loan_id=$id) AND loan.delinquent_status = 'Inactive' ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));
                            $date_paid=$datePaidRemarks['date_paid'];
                            $remarks=$row['maturity_date'];

            
                $query2="SELECT DATE_ADD('$remarks', INTERVAL 30 DAY) as maturity_date FROM sigma.payment_info";
                $interval1 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
                $query3="SELECT DATE_ADD('$remarks', INTERVAL 60 DAY) as maturity_date FROM sigma.payment_info";
                $interval2 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
                $query4="SELECT DATE_ADD('$remarks', INTERVAL 90 DAY) as maturity_date FROM sigma.payment_info";
                $interval3 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
                $query5="SELECT DATE_ADD('$remarks', INTERVAL 120 DAY) as maturity_date FROM sigma.payment_info";
                $interval4 = mysqli_fetch_assoc(mysqli_query($conn,$query5));

              if ($interval1['maturity_date'] <= date("Y-m-d")) {

              $count1+=$remaining;
              
              }elseif ($interval2['maturity_date'] <= date("Y-m-d")){

              $count2+=$remaining;

              }elseif($interval3['maturity_date'] <= date("Y-m-d")){
              
              $count3+=$remaining;

              }else{

              $count4+=$remaining;
           

              }

          }
        }
              $output .='<tr>
                            <td>TOTAL</td>
                            <td>'.$count1.'</td>
                            <td>'.$count2.'</td>
                            <td>'.$count3.'</td>
                            <td>'.$count4.'</td>
                            <td><td>
                            <td><td>
                        </tr>';

      return $output;

}

function NotMoving_page(){
  $output='';
  include 'IncludeAging/NotMovingAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="ARNotMovingAccount.php?NotMovingAccountPage=' . $page . '">' . $page . '</a></li>';
    }
   return $output;
}

//--------------------------------------Legal
function LegalAccount(){

    $output='';
    include 'IncludeAging/LegalAccount.php';
      while($row = mysqli_fetch_array($result))
      {   
          $id = $row['loan_id'];
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id='$id'";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];

          if ($remaining>'0'){

                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) && (maturity_date < (select curdate()) AND loan.loan_id=$id) AND loan.delinquent_status = 'Inactive' ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));
                            $date_paid=$datePaidRemarks['date_paid'];
                            $remarks=$row['maturity_date'];
            
                $query2="SELECT DATE_ADD('$remarks', INTERVAL 30 DAY) as maturity_date FROM sigma.payment_info";
                $interval1 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
                $query3="SELECT DATE_ADD('$remarks', INTERVAL 60 DAY) as maturity_date FROM sigma.payment_info";
                $interval2 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
                $query4="SELECT DATE_ADD('$remarks', INTERVAL 90 DAY) as maturity_date FROM sigma.payment_info";
                $interval3 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
                $query5="SELECT DATE_ADD('$remarks', INTERVAL 120 DAY) as maturity_date FROM sigma.payment_info";
                $interval4 = mysqli_fetch_assoc(mysqli_query($conn,$query5));

              if ($interval1['maturity_date'] <= date("Y-m-d")) {

            $output .='   <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td>'.$remaining.'</td>
                          <td></td>
                          <td></td>
                          <td></td>';

                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';
              
              }elseif ($interval2['maturity_date'] <= date("Y-m-d")){
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td>'.$remaining.'</td>
                          <td></td>
                          <td></td>';
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';

              }elseif($interval3['maturity_date'] <= date("Y-m-d")){
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td></td>
                          <td>'.$remaining.'</td>
                          <td></td>';

                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';            

              }else{
              
              $output .=' <tr>
                          <td><a href="Profile.php?loan_id='.$row["loan_id"].'">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</a></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>'.$remaining.'</td>';
                          
                  if(empty($date_paid)||$row['maturity_date'] > $date_paid){

                    $output.='<td>'.$row['maturity_date'].'</td>';

                  }else{

                    $output.='<td>'.$date_paid.'</td>';
                  }
                  
                    $output.='<td>'.$row["loan_remarks"].'</td>
                      </tr>';           

              }

          }
        }
      return $output;

}

function TotalLegal(){

    $output='';
    $count1=0;
    $count2=0;
    $count3=0;
    $count4=0;
    include ('./Include/connection.php');
    
    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    inner join payment on loan.loan_id = payment.loan_id
    WHERE (maturity_date < (select curdate())
    AND loan.delinquent_status = 'Legal')
    AND registered_status='Approved' group by loan.loan_id";
     $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result))
      {   
          $id = $row['loan_id'];
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id='$id'";
                  $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
                  $remaining = $rowRemain['rb'];

          if ($remaining>'0'){

                          $query1="SELECT date_paid,remarks from payment_info JOIN payment ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id = $id && date_paid IS NOT NULL && date_paid=(SELECT MAX(date_paid) from payment_info JOIN payment ON payment_info.payment_id=payment.payment_id WHERE loan_id=$id) && (maturity_date < (select curdate()) AND loan.loan_id=$id) AND loan.delinquent_status = 'Legal' ORDER by payment_info.payment_id ASC;";
                            $datePaidRemarks = mysqli_fetch_assoc(mysqli_query($conn,$query1));
                            $date_paid=$datePaidRemarks['date_paid'];
                            $remarks=$row['maturity_date'];
            
                $query2="SELECT DATE_ADD('$remarks', INTERVAL 30 DAY) as maturity_date FROM sigma.payment_info";
                $interval1 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
                $query3="SELECT DATE_ADD('$remarks', INTERVAL 60 DAY) as maturity_date FROM sigma.payment_info";
                $interval2 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
                $query4="SELECT DATE_ADD('$remarks', INTERVAL 90 DAY) as maturity_date FROM sigma.payment_info";
                $interval3 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
                $query5="SELECT DATE_ADD('$remarks', INTERVAL 120 DAY) as maturity_date FROM sigma.payment_info";
                $interval4 = mysqli_fetch_assoc(mysqli_query($conn,$query5));

              if ($interval1['maturity_date'] <= date("Y-m-d")) {

              $count1+=$remaining;
              
              }elseif ($interval2['maturity_date'] <= date("Y-m-d")){

              $count2+=$remaining;

              }elseif($interval3['maturity_date'] <= date("Y-m-d")){
              
              $count3+=$remaining;

              }else{

              $count4+=$remaining;
           

              }
          }
        }
              $output .='<tr>
                            <td>TOTAL</td>
                            <td>'.$count1.'</td>
                            <td>'.$count2.'</td>
                            <td>'.$count3.'</td>
                            <td>'.$count4.'</td>
                            <td><td>
                            <td><td>
                        </tr>';

      return $output;

}

function Legal_page(){
  $output='';
  include 'IncludeAging/LegalAccount.php';
    for ($page=1;$page<=$number_of_pages;$page++) {
      $output .= '<li><a href="ARLegal.php?LegalPage=' . $page . '">' . $page . '</a></li>';
    }
   return $output;
}


//--------------------------------------------------------
?>