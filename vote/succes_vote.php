<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home E-Voting | SMPN 1 Karanganyar</title>

    <link rel="stylesheet" href="../src/assets/css/styles.min.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('../src/img/e-vote.jpg');
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
    // Ambil nilai 'nis' dari URL jika ada
    $nis = $_GET['id'];
        
    // Lakukan kueri ke database untuk mendapatkan nama berdasarkan nis
    include '../config/conn.php';
    global $conn; // Anda perlu mengakses $conn dari luar fungsi

    $nama = '';
    $query = "SELECT * FROM tb_pemilih WHERE nis ='$nis' ";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $nama = $row['nama'];
            // echo "ID: " . $row["id_config"] . " - Nama: " . $row["nama_config"] . "<br>";
        }
    } else {
        // echo "Tidak ada data ditemukan.";
    }
    
    if (isset($_GET['flag']) && $_GET['flag'] == 1) {
        $notify = "Anda Sudah Melakukan Voting";
        $visibility = 'hidden';
    } else {
        $notify = "Terima kasih, ".$nama;
        $visibility = '';
    }

    ?>
    <!--  Body Wrapper -->
    <div class="page-wrapper overlay" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="row d-flex align-items-center justify-content-center w-100">
                <div class="col-md-8 col-lg-8 col-xxl-3">
                    <div class="card mb-0">
                        <div class="card-body w-100">
                            <div class="countdown-wrapper text-center">
                                <div class="text-center">
                                    <img src="../src/img/vote_logo.png" height="50px" width="auto">
                                </div>
                                <hr>
                                <h3><strong><?= $notify ?></strong></h3>
                                <h5 <?= $visibility;?>>Telah malakukan voting pemilihan Ketua OSIS</h5>
                                <h5 <?= $visibility;?>>SMP N 1 Karanganyar</h5>

                                <hr>
                                <p class="text-primary">Anda akan dialihkan ke halaman utama dalam <span id="countdown"> 10 </span> detik.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT: -->
    <script src="../src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Hitungan mundur dimulai dari 10
        let countdown = 10;

        const countdownDisplay = document.getElementById('countdown');

        // Fungsi hitungan mundur
        function updateCountdown() {
            countdown--;
            countdownDisplay.textContent = countdown;

            if (countdown <= 0) {
                // Jika hitungan mundur mencapai 0, lakukan pengalihan
                window.location.href = 'index.php'; // Ganti dengan URL halaman tujuan
            }
        }

        // Perbarui hitungan mundur setiap detik
        setInterval(updateCountdown, 1000);
    </script>
</body>

</html>