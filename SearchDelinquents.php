<?php  
    include 'delinquentFunction.php';
    include 'navigation.php';
    include 'notification_fetch.php';
    include 'DelinquentUpdate.php';
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
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/search.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/notification.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
    <link rel="stylesheet" type="text/css" href="css/navigation.css">
    <link rel="stylesheet" type="text/css" href="css/navigation2.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <script src="js/ajax.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <title>List of Registered Clients</title>

</head>

<body>
    <div class=" no-padding">
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
          <h3>Search Accounts</h3>
            <br><br>
            <form action="SearchDelinquents.php" method="post">
                <div class="pad-2" id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input type="text" name="summaryDelinquents" class="search-query" placeholder="Search" id="myInput">
                        <div class="input-group-btn">
                            <button class="btn mybt mybtn2"  name="submit-summary"  type="submit" value="Search">
                              <img src="img/search2.png" width="15px">
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <br><br>
            <table class="table">
                <thead class="text-white">
                    <tr>
                        <th class="my-bg text-white">Account Name</th>
                        <th class="my-bg text-white" >Remaining Balance</th>
                        <th class="my-bg text-white" >Bi Monthly</th>
                        <th class="my-bg text-white" >Date Paid</th>
                        <th class="my-bg text-white" >Date Booked</th>
                        <th class="my-bg text-white" >Maturity Date</th>
                        <th class="my-bg text-white" >Remarks</th>
                        <th class="my-bg text-white">Status</th>
                        <th colspan="2" class="my-bg text-white">Action</th>
                    </tr>

                </thead>

                <?php
                    echo searchDelinquent();
                    ?>
            </table>
        </div>
    </div>
    <!-- Modal Update-->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update Delinquent Status</h4>
          </div>
          <div class="modal-body">
              <select id="status" class="form-control">
                <option selected disabled>---- status ----</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Legal</option>
              </select>
              <input type="hidden" id="ClientId" class="form-control">
          </div>
          <div class="modal-footer">
            <a href="#" id="save" class="btn btn-primary pull-right">Update</a>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- Modal Update Remarks-->
    
    <div id="myEdit" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Update Remarks</h4>
          </div>
          <div class="modal-body">
            <input type="text" id="remarks" class="form-control">
          </div>
          <div class="modal-footer">
            <a href="#" id="saveRemarks" class="btn btn-primary pull-right">Update</a>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <script type="text/javascript" src="js/Table.js"></script>
    <script type="text/javascript" src="js/modal.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
    <script>
      $(document).ready(function(){

        //  append values in input fields
          $(document).on('click','a[data-role=update]',function(){
                var id  = $(this).data('id');
                var status  = $('#'+id).children('td[data-target=status]').text();

                $('#status').val(status);
                $('#ClientId').val(id);
                $('#myModal').modal('toggle');
          });
      //  append values in input fields
          $(document).on('click','a[data-role=edit]',function(){
                var id  = $(this).data('id');
                var remarks  = $('#'+id).children('td[data-target=remarks]').text();

                $('#remarks').val(remarks);
                $('#ClientId').val(id);
                $('#myEdit').modal('toggle');
          });

          // now create event to get data from fields and update in database 

           $('#save').click(function(){
              var id  = $('#ClientId').val(); 
              var status =  $('#status').val();

              $.ajax({
                  url      : 'DelinquentUpdate.php',
                  method   : 'post',  
                  data     : {id: id, status:status},

                  success  : function(response){
                                // now update user record in table 
                                 $('#'+id).children('td[data-target=status]').text(status);
                                 $('#myModal').modal('toggle');


                             }
              });
           });
           $('#saveRemarks').click(function(){
              var id  = $('#ClientId').val(); 
              var remarks =  $('#remarks').val();

              $.ajax({
                  url      : 'UpdateRemarks.php',
                  method   : 'post',  
                  data     : {id: id, remarks:remarks},

                  success  : function(response){
                                // now update user record in table 
                                 $('#'+id).children('td[data-target=remarks]').text(remarks);
                                 $('#myEdit').modal('toggle');
                                 location.reload(); 

                             }
              });
           });
      });
    </script>
</body>

</html>
