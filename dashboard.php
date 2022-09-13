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

<!DOCTYPE html>
<html>
<head>
  	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<link rel="stylesheet" type="text/css" href="css/notification.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
	<link rel="stylesheet" type="text/css" href="css/navigation.css">
	<link rel="stylesheet" type="text/css" href="css/navigation2.css">
	<link rel="stylesheet" type="text/css" href="css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<title></title>
</head>
<body>
<div class="no-padding">
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
	<div class="container pad-1">
	  <div class="row">
	    <div class="col-lg-6">
	      <div class="circle-tile ">
	        <div class="circle-tile-content mygreen">
	          <div class="circle-tile-description text-faded">List of Delinquents</div>
	          <div class="circle-tile-number text-faded ">
	          	<?php
	          	echo count_delinquent() 
	          	?>
	          </div>
	          <a class="circle-tile-footer" href="ListOfDelinquents.php">More Info<i class="fa fa-chevron-circle-right"></i></a>
	        </div>
	      </div>
	      <div class="circle-tile ">
	        <div class="circle-tile-content mygreen">
	          <div class="circle-tile-description text-faded">Number of active Client</div>
	          <div class="circle-tile-number text-faded ">
	          	<?php
	          	echo count_ActiveClient() 
	          	?>	
	          </div>
	          <a class="circle-tile-footer" href="SORActiveAccount.php">More Info<i class="fa fa-chevron-circle-right"></i></a>
	        </div>
	      </div>
	      <div class="circle-tile ">
	        <div class="circle-tile-content mygreen">
	          <div class="circle-tile-description text-faded">Number of Active Delinquent Client</div>
	          <div class="circle-tile-number text-faded ">
	          	<?php
	          	echo count_ActiveDelinquentClient() 
	          	?>	
	          </div>
	          <a class="circle-tile-footer" href="SORActiveDelinquentAccount.php">More Info<i class="fa fa-chevron-circle-right"></i></a>
	        </div>
	      </div>
	      <div class="circle-tile ">
	        <div class="circle-tile-content mygreen">
	          <div class="circle-tile-description text-faded">Number of Delinquent Client</div>
	          <div class="circle-tile-number text-faded ">
	          	<?php
	          	echo count_DelinquentClient() 
	          	?>	
	          </div>
	          <a class="circle-tile-footer" href="SORDelinquentAccount.php">More Info<i class="fa fa-chevron-circle-right"></i></a>
	        </div>
	      </div>
	      <div class="circle-tile ">
	        <div class="circle-tile-content mygreen">
	          <div class="circle-tile-description text-faded">Number of Legal Client</div>
	          <div class="circle-tile-number text-faded ">
	          	<?php
	          	echo count_LegalClient() 
	          	?>	
	          </div>
	          <a class="circle-tile-footer" href="SORActiveLegalAccount.php">More Info<i class="fa fa-chevron-circle-right"></i></a>
	        </div>
	      </div>
	    </div>
	   	<div class="col-md-6 pt-3">
	   		<div class="row" style="padding-bottom: 40px;">
	   			<div class="col">
			   		<div class="mq-panel-wrapper">
						<div class="mq-panel-header">
						    <h3>Today's Account Due Payment</h3>
						</div>				
						<div class="mq-panel-body">
				           <?php
			          			echo dashboard_duedate();
			          		?>	
			          	</div>
					</div>
	   			</div>
	   		</div>
	   		<div class="row">
	   			<div class="col">
	   				<div class="mq-panel-wrapper">
						<div class="mq-panel-header">
						    <h3>Today's Account Due Maturity</h3>
						</div>				
						<div class="mq-panel-body">
				           <?php
			          			echo notification_data();
			          		?>	
			          	</div>
					</div>
	   			</div>
	   		</div>
	   		</div>
	    </div>
	  </div>
	</div>
</div>
</body>
</html>