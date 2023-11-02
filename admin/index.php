<?php
include '../config/conn.php';
session_start();
session_unset();
$pesanKesalahan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    $username = trim($_POST["username"]);
    $password = md5(trim($_POST["password"]));
    if (!empty($username)) {
        // Lakukan koneksi ke database (menggunakan MySQLi)
        // require 'config/conn.php';

        // Sanitize the input using mysqli_real_escape_string
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // Buat query SQL dengan user input
        $query = "SELECT * FROM tb_users WHERE username = '$username' AND password = '$password'";

        // Execute the query
        $result = mysqli_query($conn, $query);

        if ($result) { // Check if the query was executed successfully
            if (mysqli_num_rows($result) > 0) {
                // Login code ditemukan di database, lakukan tindakan yang sesuai
                $row = mysqli_fetch_assoc($result);
                $_SESSION['s_id']       = $row['id'];
                $_SESSION['s_username'] = $row['username'];
                $_SESSION['s_password'] = $row['password'];
                $_SESSION['s_role']     = $row['role'];

                // Alihkan pengguna ke halaman selanjutnya
                header("Location: dashboard.php"); // Redirect jika boleh diakses
                exit;
            } else {
                // Login code tidak ditemukan, berikan pesan kesalahan kepada pengguna
                $pesanKesalahan = "Username atau Password salah. Silakan coba lagi.";
            }

            // Free the result set
            mysqli_free_result($result);
        } else {
            // Handle the query execution error
            $pesanKesalahan = "Error in executing the SQL query.";
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modernize Free</title>
    <link rel="shortcut icon" type="image/png" href="../src/assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../src/assets/css/styles.min.css" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <form action="#" method="post">
                                <div class="card-body">
                                    <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                        <img src="../src/assets/images/logos/dark-logo.svg" width="180" alt="">
                                    </a>

                                    <?php
                                    if (!empty($pesanKesalahan)) {
                                        echo '<div class="alert alert-warning">' . $pesanKesalahan . '</div>';
                                    }
                                    ?>

                                    <form>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" id="username" aria-describedby="emailHelp">
                                        </div>
                                        <div class="mb-4">
                                            <label for="exampleInputPassword1" class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                                        </div>
                                        <button type="submit" name="btn_login" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Log In</button>
                                        <div class="d-flex align-items-center justify-content-center">
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>