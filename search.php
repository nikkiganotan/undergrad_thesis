<?php
  include 'notification_fetch.php'; 
  include 'navigation.php';
?>

<?php
session_start();
//Checking User Logged or Not
if(empty($_SESSION['user'])){
    header('location:index.php');
}


//timeout after 5 sec
if(isset($_SESSION['user'])) {
    if((time() - $_SESSION['last_time']) > 1800) {
      header("location:logout.php");  
    }
}


?>
<?php

    header('Cache-Control: no cache');

    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }
    
    $search = $_POST['search'];
    
    $sql = "SELECT * FROM client WHERE last_name LIKE '%$search%' || first_name LIKE '%$search%' || middle_name LIKE '%$search%' || concat(first_name,' ',middle_name,' ',last_name) LIKE '%$search%' && registered_status = 'Approved' ORDER BY last_name";
    $result1 = mysqli_query($con,$sql);
    $resultCheck = mysqli_num_rows($result1);
    $ctr = 0;
$second = 4;
    $third = 8;
    $fourth = 12;
    $five = 0;

      
?>        
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/search.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <script src="js/ajax.js"></script>
    <script src="js/bootstrap.min.js"></script>

  <title></title>
</head>
<body>

 <nav id="myNavbar" class="navbar nav-color" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="dashboard.php">SIGMA</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php
                echo navigate_it()
            ?>
            <ul class="nav navbar-nav navbar-right">
                <?php
                  echo navigate_right();

                ?>
            </ul>
        </div>
    </div>
</nav> 
  <div class="no-padding">
    <div class="container pad-1">
      <h2 class="p-5 text-center">List Of Clients</h2>
        <?php
            if($resultCheck > 0){
                While ($row2 = mysqli_fetch_assoc($result1)){
                       $sql1 = "SELECT * FROM loan where client_id='".$row2['client_id']."';";
                        $result2 = mysqli_query($con,$sql1);
                        $resultCheck2 = mysqli_num_rows($result2);
                        if($resultCheck2 > 0){
                        While ($row1 = mysqli_fetch_assoc($result2)){
                            
                            $sqlRemain = "SELECT (loan_balance+COALESCE(SUM(fines),0)+COALESCE(SUM(interest),0)-COALESCE((SUM(amount_paid)),0)) as rb FROM payment JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE status='updated' && payment.loan_id=".$row1['loan_id']."";
                                    
                                    $rowRemain = mysqli_fetch_assoc(mysqli_query($con,$sqlRemain));  
                                    $remaining = $rowRemain['rb']; 
                                    
                                    if($remaining > 0){
                                     
                                        $sql2 = "SELECT due_date,remaining_balance FROM payment WHERE loan_id='".$row1['loan_id']."' ORDER by due_date ASC";
                            
                            
                            $sqlForRates = "SELECT * from rates";
                            $rowRates = mysqli_fetch_assoc(mysqli_query($con,$sqlForRates));
                            $sqlForDate = "SELECT DATE_ADD(MAX(due_date), INTERVAL 1 month) as addDate from payment where loan_id = '".$row1['loan_id']."'";
                            $rowDate = mysqli_fetch_assoc(mysqli_query($con,$sqlForDate));   
                            
                            
                         //for Late Payment
                        $sqlForLate = "SELECT payment_id,due_date,bi_monthly FROM payment JOIN loan ON payment.loan_id=loan.loan_id WHERE payment.loan_id='".$row1['loan_id']."' && due_date = (SELECT MIN(due_date) from payment WHERE payment.loan_id='".$row1['loan_id']."' && payment_id NOT IN(SELECT payment_id FROM payment_info WHERE amount_paid >0 || fines>0))";
                        $rowForLate = mysqli_fetch_assoc(mysqli_query($con,$sqlForLate));
                        $payment_id = $rowForLate['payment_id'];
                        $fine = $rowForLate['bi_monthly'] * ($rowRates['penalty_not_maturity']/100);
                        $bi_monthly = $rowForLate['bi_monthly'];
                        if(date("Y-m-d") > $rowForLate['due_date']){
                            $sqlLateUpdate = "INSERT INTO payment_info (date_paid,fines,payment_id,remarks) VALUES ((SELECT CURRENT_DATE),CEILING($fine),$payment_id,'lateBySystem')"; 
                            mysqli_query($con,$sqlLateUpdate);
                            
                           
                            if(($remaining > 0 || is_null($remaining)) && date("Y-m-d") >= $rowDate['addDate']){
                                $interest = $rowRemain['rb'] * $rowRates['maturity_interest']/100;
                                $penalty = ($interest+$rowRemain['rb']) * $rowRates['maturity_penalty']/100;
                                
                                $sqlNewDue = "INSERT INTO payment (due_date,loan_id,date_modified) VALUES ('".$rowDate['addDate']."','".$row1['loan_id']."',CURDATE())"; 
                                mysqli_query($con,$sqlNewDue);
                                
                                $sqlLateMaturityFine = "INSERT INTO payment_info (date_paid,fines,payment_id,remarks) VALUES ((SELECT CURRENT_DATE),$penalty,(SELECT payment_id FROM (SELECT * FROM payment) AS `payment` WHERE due_date = '".$rowDate['addDate']."'),'Penalty after maturity date(System)')"; 
                                mysqli_query($con,$sqlLateMaturityFine);
                                
                                $sqlLateMaturityInterest = "INSERT INTO payment_info (date_paid,interest,payment_id,remarks) VALUES ((SELECT CURRENT_DATE),$interest,(SELECT payment_id FROM (SELECT * FROM payment) AS `payment` WHERE due_date = '".$rowDate['addDate']."'),'Interest after maturity date')"; 
                                mysqli_query($con,$sqlLateMaturityInterest);
                            
                            }
                           
                            $sqlUpdateBalance = "SELECT due_date FROM payment WHERE loan_id='".$row1['loan_id']."' && payment_id IN (SELECT payment_id FROM payment_info WHERE status='Updated');";
                            $resultUpdateBalance = mysqli_query($con,$sqlUpdateBalance);
                            $resultCheckBalance = mysqli_num_rows($resultUpdateBalance);
                            if($resultCheckBalance > 0){
                                While ($rowUpdateBalance = mysqli_fetch_assoc($resultUpdateBalance)){

                                    $sqlUpdate = "UPDATE payment SET remaining_balance=(SELECT CEILING((loan_balance+SUM(fines)+SUM(interest)+SUM(other_income)-(SUM(amount_paid)))) as remaining_balance FROM (SELECT * FROM payment) AS `payment` JOIN payment_info ON payment_info.payment_id = payment.payment_id JOIN loan ON payment.loan_id=loan.loan_id WHERE due_date <= '".$rowUpdateBalance['due_date']."' && payment.loan_id='".$row1['loan_id']."' && status='updated') WHERE loan_id='".$row1['loan_id']."' && due_date='".$rowUpdateBalance['due_date']."'";

                                     if (!mysqli_query($con,$sqlUpdate)) {
                                    echo "Error: " . mysqli_error($con);

                                    }
                                }
                            }
                            
                        }
                                        //For Next Payment
                        
                        $sqlForFines = "SELECT SUM(fines) as fines,SUM(amount_paid) as amount_paid,SUM(other_income) as other_income FROM payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE (status ='updated' || status is NULL) && loan_id='".$row1['loan_id']."'; ";
                        $rowForFines = mysqli_fetch_assoc(mysqli_query($con,$sqlForFines));
                        $fine = $rowForFines['fines'];
                        $amt = $rowForFines['amount_paid'];
                        
                        $countFine = "SELECT count(DISTINCT(payment.payment_id)) as cpid from payment_info join payment on payment.payment_id=payment_info.payment_id where fines != 0.00 && loan_id='".$row1['loan_id']."'";
                        $rowForcountFine = mysqli_fetch_assoc(mysqli_query($con,$countFine));
                        $howMany = $rowForcountFine['cpid'];
                        
                        if(date("Y-m-d")>$row1['maturity_date']){
                        $lack = ($row1['bi_monthly']*($howMany)) - $amt;
                        }else{
                            $lack = ($row1['bi_monthly']*($howMany+1)) - $amt;
                        }
                        
                        $nextPayment = $lack + $fine+$rowForFines['other_income'];
                            
                        
                    
            
            ?>
  <div id="modal<?php echo $ctr;?>" class="modal" style="overflow: scroll;">
    <div class="modal-content text-center" >
      <span class="pull-right close">&times;</span>
      <form class="text-center" action="payment.php" method="post">
          <h1> Add Payment</h1>
          <input type='hidden' name='loan_id' value='<?php echo $row1['loan_id']; ?>'/>
          <input type='hidden' name='search' value='<?php echo $search; ?>'/>

         <h2 class="p-3">Due Date</h2>
         <select class="i-2" name="choice">

            <?php 
             
             $result4 = mysqli_query($con,$sql2);
             while($row4 = mysqli_fetch_array($result4)):;?>

            <option value="<?php echo $row4[0];?>"><?php echo $row4[0];?></option>

            <?php endwhile;?>

        </select>
        <h2 class="p-3">Date paid</h2>
        <input class="i-2" type="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly><br>
        <h2 class="p-3">Amount paid</h2>
        <input class="i-2" type="input" name="payment" value="<?php echo $row1['bi_monthly']; ?>"><br>
          <h2 class="p-3">Type of Payment</h2>
        <select class="i-2" name="payment_type">
            <option value="Cash">Cash</option>
            <option value="Cheque">Cheque</option>
            <option value="Bank Deposit">Bank Deposit</option>
        </select>
          <h2 class="p-3">Account Number</h2>
        <input class="i-2" type="input" name="accNum"><br>
          <h2 class="p-3">Check Number</h2>
        <input class="i-2" type="input" name="checkNum"><br>
          <h2 class="p-3">Reference Number</h2>
        <input class="i-2" type="input" name="refNum"><br>
          <h2 class="p-3">Remarks</h2>
        <input class="i-2" type="input" name="remarks"><br>
        <div class="py-3 ">
          <input class="b-2" type="submit" value="Submit">
        </div>
      </form>
    </div>

  </div>     
        
    

        <table class="table">
            <br>
            <p>                           
            <div class="row">
                <div class="col-md-6" style="font-size: 25px;">
                    <p><strong> Name:</strong> <a href="Profile.php?loan_id=<?php echo $row1["loan_id"]?> "> 
                      <?php echo $row2['first_name'] ,' ',$row2['middle_name'], ' ', $row2['last_name']; ?></a></p>
                </div>
                <div class="col-md-6" style="font-size: 25px;">
                    <p><strong> Next Month Payment:</strong> 
                      <?php echo $nextPayment ?></p>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-3">
                  <form action="transactionsExcel.php" method="post">
                      <div id="custom-search-input">
                              <input type="hidden" name="loan" value='<?php echo $row1["loan_id"]?>'>
                              <input class="pull-right i-3"  name="exportTransaction"  type="submit" value="Export Transaction">
                      </div>
                  </form>
                </div>
                <div class="col-md-3">
                    <form method="post" action="restructure.php">
                       <input type="hidden" name="loan_idforR" value='<?php echo $row1['loan_id']; ?>' />
                       <input type='hidden' name='search' value='<?php echo $search; ?>'/>
                       <input class="i-3" type="submit" value="Restructure" />
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="post" action="extend.php">
                       <input type="hidden" name="loan_idforR" value='<?php echo $row1['loan_id']; ?>' />
                       <input type='hidden' name='search' value='<?php echo $search; ?>'/>
                       <input class="i-3 float-right" type="submit" value="Extend term" />
                    </form>
                </div>
                <?php if($_SESSION['user']['em_position']=='Operations Manager'){ ?>
                <div class="col-md-3">
                    <form method="post" action="EditPayment.php">
                       <input type="hidden" name="loan_idforR" value='<?php echo $row1['loan_id']; ?>' />
                       <input type='hidden' name='search' value='<?php echo $search; ?>'/>
                       <input class="i-3 float-right" type="submit" value="History of Transactions" />
                    </form>
                
                </div>
              <?php }?>
          </div>
          <div class="row">
            <div class="col-sm-3"><strong>Date Booked: </strong><?php echo $row1['date_booked']; ?></div>
            <div class="col-sm-3"><strong>Interest: </strong><?php echo $row1['interest_rate'],'%'; ?></div>
            <div class="col-sm-3"><span><strong>Bi-Monthly Payment:</strong> <?php echo $row1['bi_monthly']; ?></span></div>
            <div class="col-sm-3">
              <span>
                <strong>Maturity Date:</strong>  <?php echo $row1['maturity_date']; ?>
              </span>
            </div>
          </div>

           <thead class="text-white">
            <tr>
              <th class="my-bg">Date</th>
              <th class="my-bg">Check # EW</th>
              <th class="my-bg">Ref/OR#</th>
              <th class="my-bg">Payment</th>
              <th class="my-bg">Interest</th>
              <th class="my-bg">Fines</th>
              <th class="my-bg">Balance</th>
              <th class="my-bg">Other Income</th>
              <th class="my-bg">Action</th>

            </tr>
          </thead>
            <tbody id="myTable">
                <tr>
                    <td></td> 
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $row1['loan_balance']; ?></td>
                </tr>
            <?php
                $result3 = mysqli_query($con,$sql2);
                $resultCheck1 = mysqli_num_rows($result3);
                if($resultCheck1 > 0){
                    While ($row3 = mysqli_fetch_assoc($result3)){
                        $sqlForPayInfo = "SELECT payment.payment_id as pid,GROUP_CONCAT(payment_type) as payment_type,GROUP_CONCAT(check_no) as check_no,GROUP_CONCAT(ref_no) as ref_no,SUM(amount_paid) as amount_paid,SUM(interest) as interest,SUM(fines) as fines,GROUP_CONCAT(remarks) as remarks,SUM(other_income) as other_income FROM payment_info JOIN payment ON payment.payment_id = payment_info.payment_id WHERE (status ='updated' || status is NULL) && due_date='".$row3['due_date']."' && loan_id='".$row1['loan_id']."'; ";
                        $rowForPayInfo = mysqli_fetch_assoc(mysqli_query($con,$sqlForPayInfo));
                        
                        $sqlForCheck = "SELECT check_no from payment_info JOIN payment on payment.payment_id = payment_info.payment_id WHERE loan_id='".$row1['loan_id']."' && check_no IS NOT NULL && status= 'Updated'";
                        
                        
                        
                        
                        

            ?>
            <script>

                    $(document).ready(function(){
                  //hides dropdown content
                  $(".size_chart<?php echo $ctr?>").hide();

                  //unhides first option content


                  //listen to dropdown for change
                  $("#size_select<?php echo $ctr?>").change(function(){
                    //rehide content on change
                    $('.size_chart<?php echo $ctr?>').hide();
                    //unhides current item
                    $('#'+$(this).val()).show();
                  });    
                });
            </script>
            <div id="modalAll<?php echo $ctr;?>" class="modal">
            <div class="modal-content">
              <span class="pull-right close">&times;</span>
              <select id="size_select<?php echo $ctr?>" class="i-2">
                  <option>---------</option>
                  <option value="option<?php echo $ctr;?>">Add Legal Fees</option>
                  <option value="option<?php echo $second;?>">Waive Penalty</option>
                  <option value="option<?php echo $third;?>">Bouncing Check</option>
                  <option value="option<?php echo $fourth;?>">Waive Legal Fees</option>
                </select>

                <!--Size dropdown content-->
                <div id="option<?php echo $ctr;?>" class="size_chart<?php echo $ctr?>">
                            <form class="text-center" action="legalFees.php" method="post">
                               <h1>Add Legal Fees</h1>
                              <input type='hidden' name='loan_id' value='<?php echo $row1['loan_id']; ?>'/>
                              <input type='hidden' name='search' value='<?php echo $search; ?>'/>

                             <h2 class="p-3">Fee</h2>
                            <input class="i-2" type="input" name="fee" placeholder="Amount"><br>
                            <div class="py-3 ">
                              <input class="b-2" type="submit" value="Submit">
                            </div>
                          </form>
                </div>
                <div id="option<?php echo $second;?>" class="size_chart<?php echo $ctr?>">
                        <form class="text-center" action="sick.php" method="post">
                           <h1> Waive Penalty</h1>
                          <input type='hidden' name='loan_id' value='<?php echo $row1['loan_id']; ?>'/>
                          <input type='hidden' name='search' value='<?php echo $search; ?>'/>

                         <h2 class="p-3">Start</h2>
                         <select class="i-2" name="start">

                            <?php 

                             $result4 = mysqli_query($con,$sql2);
                             while($row4 = mysqli_fetch_array($result4)):;?>

                            <option value="<?php echo $row4[0];?>"><?php echo $row4[0];?></option>

                            <?php endwhile;?>

                        </select>
                        <h2 class="p-3">End</h2>
                         <select class="i-2" name="end">

                            <?php 

                             $result4 = mysqli_query($con,$sql2);
                             while($row4 = mysqli_fetch_array($result4)):;?>

                            <option value="<?php echo $row4[0];?>"><?php echo $row4[0];?></option>

                            <?php endwhile;?>

                        </select>

                        <div class="py-3 ">
                          <input class="b-2" type="submit" value="Submit">
                        </div>
                      </form>
                </div>
                
                <div id="option<?php echo $third;?>" class="size_chart<?php echo $ctr?>">
                        <form class="text-center" action="bouncingCheck.php" method="post">
                           <h1> Bouncing Check</h1>
                          <input type='hidden' name='loan_id' value='<?php echo $row1['loan_id']; ?>'/>
                          <input type='hidden' name='search' value='<?php echo $search; ?>'/>

                         <h2 class="p-3">Check #</h2>
                         <select class="i-2" name="check">

                            <?php 

                             $result4 = mysqli_query($con,$sqlForCheck);
                             while($row4 = mysqli_fetch_array($result4)):;?>

                            <option value="<?php echo $row4[0];?>"><?php echo $row4[0];?></option>

                            <?php endwhile;?>

                        </select>
                        <div class="py-3 ">
                          <input class="b-2" type="submit" value="Submit">
                        </div>
                      </form>
                </div>
                <div id="option<?php echo $fourth;?>" class="size_chart<?php echo $ctr?>">
                  <form class="text-center" action="legalFeesWaive.php" method="post">
                               <h1>Waive Legal Fee</h1>
                              <input type='hidden' name='loan_id' value='<?php echo $row1['loan_id']; ?>'/>
                              <input type='hidden' name='search' value='<?php echo $search; ?>'/>
                            <div class="py-3 ">
                              <input class="b-2" type="submit" value="Submit">
                            </div>
                          </form>
                </div>


            </div>

  </div>

  
            
            <tr>
              <td><?php echo $row3['due_date']; ?></td> 
              <td><?php echo $rowForPayInfo['check_no']; ?></td>
              <td><?php echo $rowForPayInfo['ref_no'] ?></td>
              <td><?php echo $rowForPayInfo['amount_paid']; ?></td>
              <td><?php echo $rowForPayInfo['interest']; ?></td>
              <td><?php echo $rowForPayInfo['fines']; ?></td>
              <td><?php echo $row3['remaining_balance'];?></td>
              <td><?php echo $rowForPayInfo['other_income'];?></td>

             
            <?php
                    }
                    echo ' <td>
                <button data-modal="modal'.$ctr.'" class="button" style="font-size:24px; border-radius: 10px;">
                  <img src="img/pay.png" width="40px" style="padding:5px;">  
                </button>';?>

              <?php
                if($_SESSION['user']['em_position']=='Operations Manager'){
                    echo '<button data-modal="modalAll'.$ctr.'" class="button" style="font-size:24px; border-radius: 10px;">
                      <img src="img/edit2.png" width="40px" style="padding:5px;">
                    </button>';
                      }
                ?>
          <?php

             echo  '</td>    
            </tr>
            
            </tbody>

            ';
                    $ctr++;
                    $second++;
                    $third++;
                    $fourth++;
                    $five+4;


                    
                    }
                        }
                        }
                        }
              }
            }else{
                
                 
            ?>
            <table>
           <thead class="text-white">
            <tr>
              <th class="my-bg">Date</th>
              <th class="my-bg">Check # EW</th>
              <th class="my-bg">Ref/OR#</th>
              <th class="my-bg">Payment</th>
              <th class="my-bg">Interest</th>
              <th class="my-bg">Fines</th>
              <th class="my-bg">Balance</th>
              <th class="my-bg">Remarks</th>
              <th class="my-bg">Payment</th>

            </tr>
          </thead>
            </table>
            
            <b> NO CLIENT WITH THAT NAME</b>
            
            <?php
            } 
            ?>
            </table>

    </div>   
  </div>

<script>
var modalBtns = [...document.querySelectorAll(".button")];
modalBtns.forEach(function(btn){
  btn.onclick = function() {
    var modal = btn.getAttribute('data-modal');
    document.getElementById(modal).style.display = "block";
  }
});

var closeBtns = [...document.querySelectorAll(".close")];
closeBtns.forEach(function(btn){
  btn.onclick = function() {
    var modal = btn.closest('.modal');
    modal.style.display = "none";
  }
});

window.onclick = function(event) {
  if (event.target.className === "modal") {
    event.target.style.display = "none";
  }
}
</script>
      
</body>
</html>
