<?php
$conn = mysqli_connect("localhost", "root", "", "db_e-voting");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}else{
    // echo "DB connected";
}

?>
