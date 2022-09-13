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


//Restrict User or Moderator to Access Admin.php page
if($_SESSION['user']['em_position']=='Operations Manager'){
    header('location:AdminDashboard.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<link rel="stylesheet" type="text/css" href="css/notification.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/navigation2.css">
	<link rel="stylesheet" type="text/css" href="css/registration.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<title>Add Client</title>
</head>

<body>
	<div class="container-fluid no-padding">
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
		  <div class="container">
			<div class="login-page" action="ClientAddAction.php" method="POST">
			  <div class="form">
			  	
				<h2><i class="fa fa-angle-right"></i> CLIENT PERSONAL INFORMATION </h2>
				<!-- WALANG present_address,  -->
			    <form action="ClientAddAction.php" method="POST">
				    <div class="row">
			    		<div class="col-lg-4">
			    			<input type="text" placeholder="Firstname" name="first_name" id="first_name" pattern="{1,}" required>
			    		</div>
			    		<div class="col-lg-4">
			    			<input type="text" placeholder="Middlename" name="middle_name" id="middle_name" pattern="{1,}" required>
			    		</div>	
			    		<div class="col-lg-4">
			    			<input type="text" placeholder="Lastname" name="last_name" id="last_name" pattern="{1,}" required>
			    		</div>							
			    	</div>
			    	<div class="row">
			    		<div class="col-lg-4">
			    			<input placeholder="Name of spouse" type="text" name="name_of_spouse" id="name_of_spouse" required>

			    		</div>
			    		<div class="col-lg-4">
			    			<input placeholder="Present address" type="text" name="present_address" id="present_address" required>
			    		</div>
			    		<div class="col-lg-4">
			    			<input placeholder="Contact number" type="text" name="contact_no" id="contact_no" required>
			    		</div>
			    	</div>
			    	<div class="row">
			    		<div class="col-lg-12">
			    			<input  placeholder="Requested amount" min="1000" name="requested_amount" id="requested_amount" type="number" min="1" step="1" required>
			    		</div>
			    	</div>
					
			    	<h2><i class="fa fa-angle-right"></i> CLIENT WORK INFORMATION </h2>
					
				    	<div class="row">
				    		<div class="col-lg-6">
				    			<input type="text" placeholder="Business address" name="business_address" id="business_address" pattern="{1,}" required>
				    		</div>
				    		<div class="col-lg-6">
				    			<input type="text" placeholder="Firm name" name="name_of_firm" id="name_of_firm" pattern="{1,}" required>
				    		</div>	
				    	</div>
						
				    	<div class="row">
		                    <div class="col-lg-6">
				    			<input type="text"  placeholder="Position" name="position" id="position" pattern="{1,}" required>
				    		</div>
					    	<div class="col-lg-6">
                                <select name="employment">
                                	<option selected disabled>---- Is he Employed or Owner of a Business? ----</option>
                                    <option>Employed</option>
                                    <option>Own Business</option>
                                </select>
	                        </div>
				    	</div>

			      	 <button class="btn btn-primary btn-lg btn-block" type="submit" name="create">CREATE</button>

			    </form>
				
			  </div>
			</div>
		</div>
	</div>
</body>
<footer>
	<div class="footer-bottom">
	    <div class="container">
    		<div class="text-center ">
    			<div class="copyright-text">
    				<p>CopyRight © 2019 Sigma All Rights Reserved</p>
    			</div>
    		</div> <!-- End Col -->
	    </div>
	</div>
</footer>
</html>

