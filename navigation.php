<?php  

function navigate_it() 
 {
    $output="";

    if($_SESSION['user']['em_position']=='Operations Manager'){

    $output.='<ul class="nav navbar-nav">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li class="dropdown mega-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account Receivable<span class="caret"></span></a>                
                        <ul class="dropdown-menu mega-dropdown-menu">
                            <li class="col-lg-6">
                                <ul>
                                    <li class="dropdown-header">Summary Of Receivable</li>
                                    <li><a href="SORActiveAccount.php">Active Account</a></li>
                                    <li><a href="SORActiveDelinquentAccount.php">Active Delinquent Account</a></li>
                                    <li><a href="SORActiveLegalAccount.php">Active Legal Account</a></li>
                                    <li><a href="SORDelinquentAccount.php">Delinquent Account</a></li>
                                    
                                </ul>
                            </li>
                            <li class="col-lg-4">
                                <ul>
                                    <li class="dropdown-header">Aging Of Receivable</li>
                                    <li><a href="ARMoving.php">Moving Account</a></li>
                                    <li><a href="ARNotMoving.php">Not Moving Account</a></li>
                                    <li><a href="ARLegal.php">Legal Account</a></li>                       
                                </ul>
                            </li>
                        </ul>               
                    </li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Others
                        <span class="caret"></span></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="OPListOfPendingClient.php">List Of Pending</a></li>
                            <li><a href="ListOfDelinquents.php">List Of Delinquents</a></li>
                            <li><a href="completedLoans.php">Completed Loans</a></li> 
                            <li><a href="Bookings.php">Summary of Bookings</a></li>
                        </ul>
                    </li>
                </ul>
            <div class="col-sm-3 col-md-3">
                <form class="navbar-form" role="search" action="search.php" method="post">
                <div class="input-group" style="width: 40rem;">
                    <input type="text" id="myInput" type="text" 
                    placeholder="Search Client..." name="search" 
                    style="height:32px;padding: 0px 10px;width:100%;cursor:auto;">
                    <div class="input-group-btn">
                        <button class="btn mybtn2" style="background: #2a6752;"  type="submit" value="Search"><img src="img/search2.png" width="15px"></button>
                    </div>
                </div>
                </form>
            </div>';
        
    
    }elseif ($_SESSION['user']['em_position']=='Office Staff') {

        $output.=' <ul class="nav navbar-nav">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li class="dropdown mega-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account Receivable<span class="caret"></span></a>                
                        <ul class="dropdown-menu mega-dropdown-menu">
                            <li class="col-lg-6">
                                <ul>
                                    <li class="dropdown-header">Summary Of Receivable</li>
                                    <li><a href="SORActiveAccount.php">Active Account</a></li>
                                    <li><a href="SORActiveDelinquentAccount.php">Active Delinquent Account</a></li>
                                    <li><a href="SORActiveLegalAccount.php">Active Legal Account</a></li>
                                    <li><a href="SORDelinquentAccount.php">Deliquent Account</a></li>
                                    
                                </ul>
                            </li>
                            <li class="col-lg-4">
                                <ul>
                                    <li class="dropdown-header">Aging Of Receivable</li>
                                    <li><a href="ARMoving.php">Moving Account</a></li>
                                    <li><a href="ARNotMoving.php">Not Moving Account</a></li>
                                    <li><a href="ARLegal.php">Legal Account</a></li>              
                                </ul>
                            </li>
                        </ul>               
                    </li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Others
                        <span class="caret"></span></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="OPListOfPendingClient.php">List Of Pending</a></li>
                            <li><a href="ListOfDelinquents.php">List Of Delinquents</a></li> 
                            <li><a href="completedLoans.php">Completed Loans</a></li>
                            <li><a href="Bookings.php">Summary of Bookings</a></li>

                        </ul>
                    </li>
                </ul>
            <div class="col-sm-3 col-md-3">
                <form class="navbar-form" role="search" action="search.php" method="post">
                <div class="input-group" style="width: 40rem;">
                    <input type="text" id="myInput" type="text" 
                    placeholder="Search Client..." name="search" 
                    style="height:32px;padding: 0px 10px;width:100%;cursor:auto;">
                    <div class="input-group-btn">
                        <button class="btn mybtn2" style="background: #2a6752;"  type="submit" value="Search"><img src="img/search2.png" width="15px"></button>
                    </div>
                </div>
                </form>
            </div>';

    }

    return $output;



 }



 function navigate_right() 
 {
    $output="";

    if($_SESSION['user']['em_position']=='Operations Manager'){

    $output.='     
                    <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="img/setting.png" width="15px"><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="Usermanagement.php">Manage User</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>';
        
    
    }elseif ($_SESSION['user']['em_position']=='Office Staff') {

        $output.=' 
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="img/setting.png" width="15px"><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="ClientAdd.php">Add a Client</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>';

    }

    return $output;



 }


?>

