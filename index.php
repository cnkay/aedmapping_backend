<?php
session_start();
$url = "/login/login.php";
$url1= "/admin/index/index.php";
if (!isset($_SESSION)) {
    redirect($url);
}
else {
    redirect($url1);
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

