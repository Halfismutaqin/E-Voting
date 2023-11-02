<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-voting | SMPN 1 Karanganyar</title>
    <link rel="shortcut icon" type="image/png" href="src/img/logo_smp1.png" />

    <link rel="stylesheet" href="src/assets/css/styles.min.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('src/img/e-vote.jpg');
            /* Ganti dengan path gambar latar belakang Anda */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            /* Set tinggi elemen hingga full height */
            opacity: 0.9;
            /* Atur tingkat transparansi */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(50, 100, 225, 0.5), rgba(0, 10, 80, 0.2));
            /* Ubah tingkat transparansi di sini */
            z-index: 1;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    session_unset();
    require 'config/conn.php';
    require 'models/e_vote.php';

    $pesanKesalahan = ""; // Pesan kesalahan
    $cek_result = cek_access();

    // Cek jika hanya device tertentu yang boleh akses:
    if ($cek_result && $cek_result["value"] == 'YES') {
        $_SESSION['name_device'] = 'public';
        header("Location: vote/"); // Redirect jika boleh diakses
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validasi input
        $login_code = trim($_POST["login_code"]); // Hapus spasi dari input
        if (!empty($login_code)) {
            // Lakukan koneksi ke database (menggunakan MySQLi)
            // require 'config/conn.php';

            // Sanitize the input using mysqli_real_escape_string
            $login_code = mysqli_real_escape_string($conn, $login_code);

            // Buat query SQL dengan user input
            $query = "SELECT * FROM tb_device WHERE login_code = '$login_code'";

            // Execute the query
            $result = mysqli_query($conn, $query);

            if ($result) { // Check if the query was executed successfully
                if (mysqli_num_rows($result) > 0) {
                    // Login code ditemukan di database, lakukan tindakan yang sesuai
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['name_device'] = $row['name_device'];

                    // Alihkan pengguna ke halaman selanjutnya
                    header("Location: vote/"); // Redirect jika boleh diakses
                    exit;
                } else {
                    // Login code tidak ditemukan, berikan pesan kesalahan kepada pengguna
                    $pesanKesalahan = "Login code tidak valid. Silakan coba lagi.";
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

    <!--  Body Wrapper -->
    <div class="page-wrapper overlay" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <!-- <img src="src/img/logo_smp1.png" style="max-width: 80px;" alt="logo"> -->
                                </div>
                                <h3 class="text-center font-weight-bold text-primary mb-0"><strong>SELAMAT DATANG DI SISTEM</strong></h3>
                                <h3 class="text-center text-primary mt-0"><strong>E-Voting OSIS</strong></h3>
                                <h5 class="text-center mt-0">SMP N 1 KARANGANYAR</h5>
                                <hr>
                                <form action="#" method="POST">
                                    <div class="mb-3">
                                        <label for="device_login" class="form-label">Login Code Device</label>
                                        <input type="text" class="form-control" id="device_login" name="login_code" autocomplete="off">
                                    </div>
                                    <hr>
                                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
                                </form>
                                <?php
                                if (!empty($pesanKesalahan)) {
                                    echo '<div class="alert alert-danger">' . $pesanKesalahan . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT: -->
    <script src="src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>