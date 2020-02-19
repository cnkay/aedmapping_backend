<?php
session_start();
include '../../db/rb-db-config.php';
$url="../../login/login.php";
if (isset($_SESSION)) {
    if (isset($_SESSION['logged_in']) != "Active") {
        redirect($url);
    }
}
else {
    redirect($url);
}
function redirect($url)
{
    if (!headers_sent())
    {
        header('Location: '.$url);
        exit;
    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}



$numOfDefibs = R::count('defibrillator');
$numOfUsers = R::count('user');
$numOfReports = R::count('report');
$allDefibs = R::findAll('defibrillator');
$allUsers = R::findAll('user');
$allReports = R::findAll('report');
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
        <title>AEDMapping Admin Dashboard</title>
        <link href="https://fonts.googleapis.com/css?family=Kulim+Park&display=swap" rel="stylesheet"> 
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="index.css">
        <!-- FontAwesomeLink -->
        <script src="https://kit.fontawesome.com/9ff6e74205.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Nav Start -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="#">AEDMapping</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span cl    ass="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="../index/index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../map/map.php">Map</a>
                    </li>

                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <button class="btn btn-primary my-2 my-sm-0" onclick="location.href = '../logout.php'" type="button">Logout</button>
                </form>
            </div>
        </nav>
        <!-- Nav End -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div id="outer" style="margin:10px">
                        <a href="#">
                            <div class="card bg-success text-white" id="inner" style="padding:5px">
                                <span>
                                    <i class="fas fa-bolt fa-2x"></i>
                                </span>
                                <p>Active Defibrillators</p>
                                <p><?php echo $numOfDefibs; ?></p>
                            </div> 
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="outer" style="margin:10px">
                        <a href="#">
                            <div class="card bg-warning text-white" id="inner" style="padding:5px">
                                <span>
                                    <i class="fas fa-hands-helping fa-2x"></i>
                                </span>
                                <p>Responders</p>
                                <p><?php echo $numOfUsers; ?></p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="outer" style="margin: 10px">
                        <a href="#">
                            <div class="card bg-danger text-white" id="inner" style="padding:5px">
                                <span>
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </span>
                                <p>Reports</p>
                                <p><?php echo $numOfReports; ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" >
            <table class="table table-light table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Address</th>
                        <th scope="col">City</th>
                        <th scope="col">Country</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($allDefibs as $value) {
                        ?>
                        <tr>
                            <td><?php echo $value->id ?></td>
                            <td><?php echo $value->name ?></td>
                            <td><?php echo $value->description ?></td>
                            <td><?php echo $value->address ?></td>
                            <td><?php echo $value->city ?></td>
                            <td><?php echo $value->country ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container-fluid" >
            <table class="table table-light table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($allUsers as $value) {
                        ?>
                        <tr>
                            <td><?php echo $value->id ?></td>
                            <td><?php echo $value->name ?></td>
                            <td><?php echo $value->mail ?></td>
                            <td><?php echo $value->password ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container-fluid" >
            <table class="table table-light table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Mail</th>
                        <th scope="col">Defibrillator_Id</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($allReports as $value) {
                        ?>
                        <tr>
                            <td><?php echo $value->id ?></td>
                            <td><?php echo $value->type ?></td>
                            <td><?php echo $value->comment ?></td>
                            <td><?php echo $value->mail ?></td>
                            <td><?php echo $value->defibrillator_id ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p id="digital-clock"> AEDMapping Admin Dashboard | Role : <?php echo strtoupper($_SESSION['role']) ?> | System Time :</p><div>
                <!-- Footer -->

                <script src="../time.js"></script>
                <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
                </body>
                </html>
