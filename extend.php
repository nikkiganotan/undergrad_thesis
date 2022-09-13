<?php
    $con = mysqli_connect('127.0.0.1','root','');
    
    if(!$con){
        echo 'Not Connected To Server';
    }
    
    if(!mysqli_select_db($con,'sigma')){
        echo 'Not Selected';
    }
   

    $loan_idforR = mysqli_real_escape_string($con,$_POST['loan_idforR']);
    $search = mysqli_real_escape_string($con,$_POST['search']);


?>


<html>
	<head>
		<title>Extend Term</title>
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
		<div class="container">
			<br />
			<br />
			<h2 align="center">EXTEND TERM OF CLIENT</h2><br />
			<div class="form-group">
				<form name="add_due" id="add_due">
                    <input type=hidden name="loan_id" value='<?php echo $loan_idforR; ?>'>
					<div class="table-responsive">
						<table class="table table-bordered" id="dynamic_field">
							<tr>
								<td><input type="date" name="due[]" id="forDate" value="<?php echo date('Y-m-d'); ?>" class="i-3 name_list" required/></td>                                

								<td><button type="button" name="add" id="add" class="btn mybtn2" style="background-color:#000000; color:white;">Add More</button></td>
							</tr>
						</table>
						<input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />
					</div>
				</form>
			</div>
		</div>

<form name='forSearch' action='search.php' method='POST'>
<input type=hidden name='search' value="<?php echo $search; ?>">  
</form>
</body>    


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







































