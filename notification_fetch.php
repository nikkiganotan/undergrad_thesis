<?php
 function notification_data()
 {  
      $output = '';  
      include './Include/connection.php';  
      $sql = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    WHERE (maturity_date = (select curdate())) 
    AND registered_status='Approved'";

      $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result))  
      {   


      $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) 
      as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id 
      JOIN loan ON payment.loan_id=loan.loan_id 
      WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
      $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
      $remaining = $rowRemain['rb'];

      if ($remaining!='0') {
              $output .= 
                     '<form class="navy-hov" role="search" action="search.php" method="post">
                      <input type="hidden" id="myInput" type="text" name="search" value="'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'">
                      <button  type="submit" value="Search" style="width: 100%;background: transparent; border: none; outline: none;">
                         <ul class="mq-comment">
                          <li class="left clearfix"><span class="mq-comment-img pull-left">
                              <div class="mq-comment-body clearfix">
                                  <div class="header">
                                      <strong class="primary-font">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</strong>
                                      <small class="pull-right text-muted">
                                      </small>
                                  </div>
                                  <p>
                                  </p>
                              </div>
                          </li>
                      </ul>
                      </button>
                    </form> 
                     ';
              }

     }  
      return $output;  
 }

 function count_data()  
 {  
      $output = '';
      $count = 0;
      include './Include/connection.php';  
      $sql = "SELECT * from client 
        inner join loan on client.client_id = loan.client_id 
        WHERE (maturity_date = (select curdate())) ORDER BY maturity_date DESC";  
      $result = mysqli_query($conn, $sql);  
      
      while($row = mysqli_fetch_array($result))  
      {
          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining > '0'){
            $count++;
            $output .= '<span class="number">'.$count.'</span>';
        }
      }  
      return $output;  
 }

  function count_delinquent()  
 {  
      $count = 0;
      include './Include/connection.php';  
      $sql = "SELECT * from client 
      inner join loan on client.client_id = loan.client_id 
      inner join payment on loan.loan_id = payment.loan_id 
      WHERE (maturity_date < (SELECT curdate())) AND registered_status = 'Approved'
      group by loan.loan_id";  
      $result = mysqli_query($conn, $sql);  

      

      while($row = mysqli_fetch_array($result))  
      {
        $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];
          if ($remaining!=0) {
             $count++;
            } 

      }  
      return $count;  
 }

  function count_ActiveClient()  
 {  

      $count = 0;
      include './Include/connection.php';  
      $sql = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    WHERE (maturity_date >= (select curdate())
    AND loan.delinquent_status = 'Active') 
    AND registered_status='Approved'";  
      $result = mysqli_query($conn, $sql);  
      
      while($row = mysqli_fetch_array($result))  
      {

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining!='0') {
            $count++;
          }
		      

      }  
      return $count;  
 }

   function count_DelinquentClient()  
 {  

      $count = 0;
      include './Include/connection.php';  
      $sql = "SELECT * FROM loan
      inner join client on client.client_id = loan.client_id
      inner join payment on loan.loan_id = payment.loan_id
      WHERE (maturity_date < (SELECT curdate())
      AND registered_status = 'Approved') AND loan.delinquent_status = 'Inactive'
      group by loan.loan_id";  
      $result = mysqli_query($conn, $sql);  
      
      while($row = mysqli_fetch_array($result))  
      {

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining!='0') {
            $count++;
          }
          

      }  
      return $count;  
 }

function count_ActiveDelinquentClient()  
 {  

      $count = 0;
      include './Include/connection.php';  
      $sql = "SELECT * FROM loan
      inner join client on client.client_id = loan.client_id
      inner join payment on loan.loan_id = payment.loan_id
      WHERE (maturity_date < (SELECT curdate())
      AND registered_status = 'Approved') AND loan.delinquent_status = 'Active'
      group by loan.loan_id"; 
      $result = mysqli_query($conn, $sql);  
      
      while($row = mysqli_fetch_array($result))  
      {

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining!='0') {
            $count++;
          }
          

      }  
      return $count;  
 }

    function count_LegalClient()  
 {  

      $count = 0;
      include './Include/connection.php';  
      $sql = "SELECT * FROM loan
      inner join client on client.client_id = loan.client_id
      inner join payment on loan.loan_id = payment.loan_id
      WHERE (maturity_date < (SELECT curdate())
      AND registered_status = 'Approved') AND loan.delinquent_status = 'Legal'
      group by loan.loan_id";  
      $result = mysqli_query($conn, $sql);  
      
      while($row = mysqli_fetch_array($result))  
      {

          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining!='0') {
            $count++;
          }
          

      }  
      return $count;  
 }


  function dashboard_maturity()  
 {  
      $output = '';  
      include './Include/connection.php';  
      $sql = "  SELECT * from client 
      inner join loan on client.client_id = loan.client_id 
      inner join payment on loan.client_id = payment.loan_id 
      WHERE (maturity_date < (SELECT curdate())) AND registered_status = 'Approved'
      group by loan.loan_id"; 
      $result = mysqli_query($conn, $sql);  
      

            while($row = mysqli_fetch_array($result))  
              {   


          $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) 
          as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id 
          JOIN loan ON payment.loan_id=loan.loan_id 
          WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
          $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
          $remaining = $rowRemain['rb'];

          if ($remaining!='0') {
                  $output .= 
                         '<form class="navy-hov" role="search" action="search.php" method="post">
                          <input type="hidden" id="myInput" type="text" name="search" value="'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'">
                          <button  type="submit" value="Search" style="    width: 100%;background: transparent; border: none; outline: none;">
                             <ul class="mq-comment">
                              <li class="left clearfix"><span class="mq-comment-img pull-left">
                                  <div class="mq-comment-body clearfix">
                                      <div class="header">
                                          <strong class="primary-font">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</strong>
                                          <small class="pull-right text-muted">
                                          </small>
                                      </div>
                                      <p>
                                      </p>
                                  </div>
                              </li>
                          </ul>
                          </button>
                        </form> 
                         ';
                  }

             }

      return $output;  
 }

   function dashboard_duedate()  
 {  
      $output = '';  
      include './Include/connection.php';  
      $sql = "SELECT * from client inner join loan on client.client_id = loan.client_id 
              inner join payment on loan.loan_id = payment.loan_id WHERE due_date=(SELECT curdate())
              AND registered_status = 'Approved'
             ";
      $result = mysqli_query($conn, $sql);  
      

            while($row = mysqli_fetch_array($result))  
              {   


              $sqlForRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) 
              as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id 
              JOIN loan ON payment.loan_id=loan.loan_id 
              WHERE status='updated' && payment.loan_id=".$row['loan_id']."";
              $rowRemain = mysqli_fetch_assoc(mysqli_query($conn,$sqlForRemain));  
              $remaining = $rowRemain['rb'];

              if ($remaining!='0') {
                      $output .= 
                             '<form class="navy-hov" role="search" action="search.php" method="post">
                              <input type="hidden" id="myInput" type="text" name="search" value="'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'">
                              <button  type="submit" value="Search" style="width: 100%;background: transparent; border: none; outline: none;">
                                 <ul class="mq-comment">
                                  <li class="left clearfix"><span class="mq-comment-img pull-left">
                                      <div class="mq-comment-body clearfix">
                                          <div class="header">
                                              <strong class="primary-font">'.$row["first_name"].' '.$row["middle_name"].' '.$row["last_name"].'</strong>
                                              <small class="pull-right text-muted">
                                              </small>
                                          </div>
                                          <p>
                                          </p>
                                      </div>
                                  </li>
                              </ul>
                              </button>
                            </form> 
                             ';
                      }

             }  
      return $output;  
 }

 ?>
