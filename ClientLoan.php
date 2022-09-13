<?php
    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }

    $id = mysqli_real_escape_string($con,$_GET['client_id']);
    $sqlForRates = "SELECT * from rates";
    $rowRates = mysqli_fetch_assoc(mysqli_query($con,$sqlForRates));


?>


<html>
	<head>
		<title>Add Loan</title>
		<link rel="stylesheet" type="text/css" href="css/custom.css">
		<link rel="stylesheet" type="text/css" href="css/notification.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
		<link rel="stylesheet" type="text/css" href="css/navigation.css">
		<link rel="stylesheet" type="text/css" href="css/navigation2.css">
		<link rel="stylesheet" type="text/css" href="css/registration.css">
		<link rel="stylesheet" type="text/css" href="css/table.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<script src="js/bootstrap.min.js"></script>
		<script src="js/ajax.js"></script>
        
	</head>
	<body>
		<div class="container2">
			<br />
			<br />
			<div class="form-group">
				<form name="add_due" id="add_due">
                    <input type=hidden name="client_id" value='<?php echo $id?>'>
					<div class="table-responsive">
						<table class="table" id="dynamic_field">
							<tr>
								<td colspan="2"><h2 align="center">Add Loan</h2><br /><td>
							</tr>
                            <tr>
				                <td colspan="2">
				                	<label>Loan Balance</label>
				                	<input type="number" name="amount" min="1000" class="i-3 name_list" required/>
				                </td>
							</tr>
                            <tr>
				                <td colspan="2">
				                	<label>Insurance</label>
				                	<input type="text" name="insurance" class="i-3 name_list" value="0"/>
				                </td>
							</tr>
				            <tr>
				                <td colspan="2">
				                	<label>Interest(%)</label>
				                	<input type="text" name="interest" class="i-3 name_list" value='<?php echo $rowRates['interest']?>'/></td>
							</tr>
                            <tr>
				                <td colspan="2">
				                <label>Service Handling Fee(%)</label>
				                <input type="text" name="shf" class="i-3 name_list" value='<?php echo $rowRates['service_handling_fee']?>'/></td>
							</tr>
                            <tr>
                                <td colspan="2">
                                <label>Loan Choice</label>
                                <select class="i-3" name="loan_class">
                                    <option value="Add">Add-On</option>
                                    <option value="Deducted">Deducted</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                	<label>Loan Type</label>
                                <select class="i-3" name="loan_type">
                                    <option value="Salary">Salary</option>
                                    <option value="Business">Business</option>
                                </select>
                                </td>
                            </tr>
							<tr>
                                <td><input type="date" name="due[]" id="forDate" value="<?php echo date('Y-m-d'); ?>" class="i-3 name_list" required/></td>                                

								<td><button type="button" name="add" id="add" class="btn mybtn2" style="background-color:#000000; color:white;">Add More</button></td>
							</tr>
                            
						</table>
						<table class="table" id="dynamic_field">
							<tr>
								<td colspan="2"><h2 align="center">CO BORROWER 1</h2><br /><td>
							</tr>
							<tr>
				                <td colspan="2">
								<h3 class="text-center">CO BORROWER PERSONAL 1 INFORMATION</h3>
							    	<div class="row">
							    		<div class="col-sm-4">
							    			<input type="text" placeholder="First Name" class="i-3" name="co_first_name_one" id="co_first_name_one" pattern="{1,}" required>
							    		</div>
							    		<div class="col-sm-4">
							    			<input type="text" placeholder="Middle Name" class="i-3" name="co_middle_name_one" id="co_middle_name_one" pattern="{1,}" required>
							    		</div>
							    		<div class="col-sm-4">
							    			<input type="text" placeholder="Last Name" class="i-3" name="co_last_name_one" id="co_last_name_one" pattern="{1,}" required>
							    		</div>	
							    	</div>
									
							    	<div class="row">
				                       <div class="col-sm-5">
			                                <input type="text" placeholder="Present Address" class="i-3" name="co_address_one" id="co_address_one" pattern="{1,}" required>
			                           </div>
										<div class="col-sm-3">
										    <input type="text" placeholder="Contact Number" class="i-3" name="co_contact_no_one" id="co_contact_no" required>
										</div>
							    	</div>
				                </td>
							</tr>
							<tr>
								<td>
									<h3 class="text-center"><i class="fa fa-angle-right"></i> CO BORROWER 1 WORK INFORMATION </h3>
									<div class="row">
							    		<div class="col-lg-6">
							    			<input type="text" class="i-3" placeholder="Business Address" name="co_business_address_one" id="co_business_address_one" pattern="{1,}" required>
							    		</div>
							    		<div class="col-lg-6">
							    			<input type="text" class="i-3" placeholder="Name of Firm" name="co_name_of_firm_one" id="co_name_of_firm_one" pattern="{1,}" required>
							    		</div>	
							    	</div>
									
							    	<div class="row">
					                    <div class="col-lg-6">
							    			<input type="text" class="i-3" placeholder="Position" name="co_position_one" id="co_position_one" pattern="{1,}" required>
							    		</div>
								    	<div class="col-lg-6">
			                                <select name="co_employment_one">
			                                    <option>Employed</option>
			                                    <option>Own Business</option>
			                                </select>
				                        </div>
							    	</div>
								</td>
							</tr>
						</table>
						<table class="table" id="dynamic_field">
							<tr>
								<td colspan="2"><h2 align="center">CO BORROWER 2</h2><br /><td>
							</tr>
							<tr>
				                <td colspan="2">
								<h3 class="text-center">CO BORROWER PERSONAL 2 INFORMATION</h3>
							    	<div class="row">
							    		<div class="col-sm-4">
							    			<input type="text" class="i-3" placeholder="First Name" name="co_first_name_two" id="co_first_name_two" pattern="{1,}" required>
							    		</div>
							    		<div class="col-sm-4">
							    			<input type="text" class="i-3" placeholder="Middle Name" name="co_middle_name_two" id="co_middle_name_two" pattern="{1,}" required>
							    		</div>
							    		<div class="col-sm-4">
							    			<input type="text" class="i-3" placeholder="Last Name" name="co_last_name_two" id="co_last_name_two" pattern="{1,}" required>
							    		</div>	
							    	</div>
									
							    	<div class="row">
				                       <div class="col-sm-5">
			                                <input type="text" class="i-3" placeholder="Present Address" name="co_address_two" id="co_address_two" pattern="{1,}" required>
			                           </div>
										<div class="col-sm-3">
										    <input type="text" class="i-3" placeholder="Contact Number" name="co_contact_no_two" id="co_contact_no" required>
										</div>
							    	</div>
				                </td>
							</tr>
							<tr>
								<td>
									<h3 class="text-center"><i class="fa fa-angle-right"></i> CO BORROWER 2 WORK INFORMATION</h3>
									<div class="row">
							    		<div class="col-lg-6">
							    			<input type="text" class="i-3" placeholder="Business Address" name="co_business_address_two" id="co_business_address_two" pattern="{1,}" required>
							    		</div>
							    		<div class="col-lg-6">
							    			<input type="text" class="i-3" placeholder="Name of Firm" name="co_name_of_firm_two" id="co_name_of_firm_two" pattern="{1,}" required>
							    		</div>	
							    	</div>
							    	<div class="row">
					                    <div class="col-lg-6">
							    			<input type="text" class="i-3" placeholder="Position" name="co_position_two" id="co_position_two" pattern="{1,}" required>
							    		</div>
								    	<div class="col-lg-6">
			                                <select placeholder="Employment" name="co_employment_two">
			                                    <option>Employed</option>
			                                    <option>Own Business</option>
			                                </select>
				                        </div>
							    	</div>
								</td>
							</tr>
						</table>

						<input type="button" name="submit" id="submit" class="btn mybtn2 i-3" style="background-color:#2a6752; color:white;" value="Submit" />
					</div>
				</form>
			</div>
		</div>
<form name='forSearch' action='SORActiveAccount.php' method='POST'>
</form>
<script>
$(document).ready(function(){
	var i=1;
	$('#add').click(function(){
        function padNumber(number) {
                var string  = '' + number;
                string      = string.length < 2 ? '0' + string : string;
                return string;
            }
            
            date      = new Date(document.getElementById("forDate").value);
            next_date = new Date(date.setDate(date.getDate() + 15*i));
            formatted = next_date.getUTCFullYear() + '-' + padNumber(next_date.getUTCMonth() + 1) + '-' + padNumber(next_date.getUTCDate())
		i++;
		$('#dynamic_field').append('<tr id="row'+i+'"><td><input type="date" name="due[]" class="i-3 name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" required>X</button></td></tr>');
        document.getElementsByName("due[]")[i-1].value = formatted;

	});
	
	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id"); 
		$('#row'+button_id+'').remove();
        i--;
	});
	
	$('#submit').click(function(){		
		$.ajax({
			url:"ClientLoanAction.php",
			method:"POST",
			data:$('#add_due').serialize(),
			success:function(data)
			{
				alert(data);
				$('#add_due')[0].reset();
                document.forSearch.submit();
			}
		});
	});
});
    
</script>
</body>
</html>







































