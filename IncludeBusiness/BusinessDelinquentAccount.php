<?php
    include './Include/connection.php';
    $results_per_page = 10;

    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    inner join payment on loan.loan_id = payment.loan_id
    WHERE (maturity_date < (select curdate())) 
    AND (loan.loan_type = 'Business' 
    AND loan.delinquent_status = 'Inactive') group by loan.loan_id";

    $result = mysqli_query($conn, $query);
    $number_of_results = mysqli_num_rows($result);
    $number_of_pages = ceil($number_of_results/$results_per_page);

    if (!isset($_GET['BusinessPage'])) {
          $page = 1;
        } else {
          $page = $_GET['BusinessPage'];
        }
    $this_page_first_result = ($page-1)*$results_per_page;

    $query = "SELECT * from client 
    inner join loan on client.client_id = loan.client_id 
    inner join payment on loan.loan_id = payment.loan_id
    WHERE (maturity_date < (select curdate())) 
    AND (loan.loan_type = 'Business' AND loan.delinquent_status = 'Inactive') 
    group by loan.loan_id
    LIMIT " . $this_page_first_result . "," .  $results_per_page;
    $result = mysqli_query($conn, $query);

?>