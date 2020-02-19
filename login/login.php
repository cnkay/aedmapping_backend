<?php
session_start();
$url = "../admin/index/index.php";
if (isset($_SESSION)) {
    if (isset($_SESSION['logged_in']) == "Active") {
        redirect($url);
    }
}

function redirect($url) {
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit;
    }
}

include ('../db/rb-db-config.php');

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function console_log($data) {
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

$result = "";
if (!empty($_POST)) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = clean($_POST['email']);
        $password = ($_POST['password']);
        $errors = [];
        if ($_POST['email'] != $email) {
            array_push($errors, "Email is not valid");
        }
        if (empty($email)) {
            array_push($errors, "Email is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
        if (count($errors) == 0) {
            $res = R::findOne('admin', 'mail=?', [$email]);
            if ($res == null) {
                $result = '<div class="alert alert-danger">Wrong username or password</div>';
            } else {
                $pass = hash('sha256', $password);
                if ($res['password'] == $pass) {
                    $_SESSION['email'] = $res['mail'];
                    $_SESSION['role'] = $res['role'];
                    $_SESSION['logged_in'] = "Active";
                    header("location: ../admin/index/index.php");
                    exit;
                } else {
                    $result = '<div class="alert alert-danger">Wrong password</div>';
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>AEDMapping Admin Dashboard</title>

        <link href="https://fonts.googleapis.com/css?family=Kulim+Park&display=swap" rel="stylesheet"> 
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="login.css" rel="stylesheet">
        <!-- FontAwesomeLink -->
        <script src="https://kit.fontawesome.com/9ff6e74205.js" crossorigin="anonymous"></script>
    </head>

    <body class="text-center">
        <form class="form-signin" action="login.php" method="post">
            <span class="mb-4" style="color: red">
                <i class="fas fa-heartbeat fa-5x"></i>
            </span>
            <div class="form-group">

                <?php echo $result; ?>    

            </div>
            
          <!--  <div class="form-group alert alert-primary" role="alert"> EKAB Admin => mail password </div> -->
        <!--    <div class="form-group alert alert-primary" role="alert"> Municip => mail1 password1 </div> -->
            
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="text" id="inputEmail" class="form-control" placeholder="Email address" name="email" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="login_user">Sign in</button>
            <p class="mt-5 mb-3 text-muted">AEDMapping Admin Dashboard</p>
        </form>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>

