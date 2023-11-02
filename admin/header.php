<?php
session_start();
if (!empty($_SESSION['s_id'])) {
    $s_id   = $_SESSION['s_id'];
    $s_username   = $_SESSION['s_username'];
    $s_password   = $_SESSION['s_password'];
    $s_role   = $_SESSION['s_role'];
} else {
    header("location: index.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-voting | SMPN 1 Karanganyar</title>
    <link rel="shortcut icon" type="image/png" href="../src/img/logo_smp1.png" />

    <link rel="stylesheet" href="../src/assets/css/styles.min.css"> <!-- CSS Anda -->
    <link rel="stylesheet" href="plugins/datatables.min.css"> <!-- Misalnya, CSS lainnya -->
    <link href="plugins/summernote/summernote-bs4.min.css" rel="stylesheet"> <!-- CSS Summernote -->


</head>

<body>