<?php
session_start();

$_SESSION['forgot_email'] = $_POST['email'];
$_SESSION['forgot_domain'] = $_POST['domain'];

header("Location: ../index.html");
?>