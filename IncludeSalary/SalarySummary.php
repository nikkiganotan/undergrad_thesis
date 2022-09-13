<?php
    include './Include/connection.php';
    $results_per_page = 10;

    $query = "SELECT *  from client 
        INNER JOIN loan on client.client_id = loan.client_id
        INNER JOIN payment on payment.loan_id = loan.loan_id WHERE loan_type = 'Salary'
        group by loan.loan_id";

    $result = mysqli_query($conn, $query);
    $number_of_results = mysqli_num_rows($result);
    $number_of_pages = ceil($number_of_results/$results_per_page);

    if (!isset($_GET['SalaryPage'])) {
		  $page = 1;
		} else {
		  $page = $_GET['SalaryPage'];
		}
	$this_page_first_result = ($page-1)*$results_per_page;

	$query = "SELECT *  from client 
        INNER JOIN loan on client.client_id = loan.client_id
        INNER JOIN payment on payment.loan_id = loan.loan_id WHERE loan_type = 'Salary'
        group by loan.loan_id
        LIMIT " . $this_page_first_result . "," .  $results_per_page;
    $result = mysqli_query($conn, $query);

?>