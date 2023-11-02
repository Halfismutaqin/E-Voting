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
            background-image: url('../src/img/smp1.jpg');
            /* Ganti dengan path gambar latar belakang Anda */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            /* Set tinggi elemen hingga full height */
            opacity: 1;
            /* Atur tingkat transparansi */
        }

        .alert-button {
            width: 40px;
            height: 40px;
            background-color: yellow;
            border-radius: 50%;
            position: fixed;
            top: 600px;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 1;
        }

        .modal {
            display: none;
        }

        .modal.show {
            /* Tambahkan gaya untuk menampilkan modal */
            display: block;
            /* Set visibility to visible */
        }
    </style>
</head>

<body>
    <?php
    session_start();
    require '../config/conn.php';
    require '../models/e_vote.php';


    if (isset($_SESSION['nis'])) {
        $nis            = $_SESSION['nis'];
        $nama_pemilih   = $_SESSION['nama_pemilih'];
        if (isset($_SESSION['name_device'])) {
            $name_device      = $_SESSION['name_device'];
        } else {
            header("location: ../");
        }
    } else {
        header("location: index.php");
        // echo 'name_device not found in the session.';
    }
    // echo $nis;
    // echo '<br>';
    // echo $nama_pemilih;
    // echo '<br>';
    // echo $name_device;

    ?>
    <div class="container">
        <br>
        <div class="card mt-4">
            <div class="card-header text-center bg-primary">
                <h4 class="text-white"><strong>E-Voting Online | SMP N 1 Karanganyar</strong></h4>
            </div>
            <div class="card-body bg-light overflow-auto rounded" style="min-height: 640px; max-height: 670px;">
                <div class="row d-flex justify-content-center mt-1">
                    <div class="alert alert-warning text-center"><strong>Selamat Datang <?= $nama_pemilih ?></strong></div>
                    <?php
                    $candidates = list_kandidat();
                    foreach ($candidates as $key => $value) {
                        // var_dump($value);
                    ?>
                        <!-- Your HTML code for displaying candidates -->
                        <div class="col-4">
                            <div class="card shadow-lg p-3 mb-5 bg-body rounded m-2">
                                <div class="row justify-content-center">
                                    <?php if (empty($value['foto'])) { ?>
                                        <img src="../src/upload/users.png" class="card-img-top mt-2" style="max-height: 200px; width: auto;">
                                    <?php } else { ?>
                                        <img src="../src/upload/<?= $value['foto'] ?>" class="card-img-top mt-2" style="max-height: 200px; width: auto;">
                                    <?php } ?>
                                </div>
                                <form action="proses_vote.php" method="POST">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"><strong><?= $value['nama_kandidat'] ?></strong></h5>
                                        <hr>
                                        <p class="card-text text-md"><strong>Visi : </strong> <?= $value['visi'] ?></p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <input type="hidden" name="id_kandidat" value="<?= $value['id_kandidat'] ?>">
                                        <input type="hidden" name="nis" value="<?= $nis ?>">
                                        <input type="hidden" name="name_device" value="<?= $name_device ?>">
                                        <button type="button" class="btn btn-primary" onclick="showModal(<?= $value['id_kandidat'] ?>)"><i class="ti ti-info-circle"></i> Detail </button>
                                        &emsp;
                                        <button type="submit" name="btn_vote" value="YES" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin memilih <?= $value['nama_kandidat'] ?>?')"> VOTE <i class="ti ti-archive"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Modal Detail : -->
                        <div class="modal fade modal-xl" id="detailModal<?= $value['id_kandidat'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-light rounded">
                                        <h4 class="modal-title" id="exampleModalLabel"><strong><?= $value['nama_kandidat'] ?></strong></h4>
                                    </div>
                                    <form action="proses_vote.php" method="POST">
                                        <div class="modal-body overflow-auto" style="min-height: 380px; max-height: 400px;">
                                            <div class="row">
                                                <div class="col-xl-5 col-sm-12 text-center">
                                                    <?php if (empty($value['foto'])) { ?>
                                                        <img src="../src/upload/users.png" class="card-img-top mt-2" style="max-width: 400px;">
                                                    <?php } else { ?>
                                                        <img src="../src/upload/<?= $value['foto'] ?>" class="card-img-top mt-2" style="max-width: 400px;">
                                                    <?php } ?>
                                                </div>
                                                <div class="col-xl-7 col-sm-12">
                                                    <h5 class="mt-4">
                                                        <!-- Isi dengan konten detail kandidat, misalnya: -->
                                                        <label for="visi"><strong>Visi : </strong></label><br>
                                                        <p id="visi"><?= $value['visi'] ?></p>
                                                        <br>
                                                        <label for="misi"><strong>Misi : </strong></label><br>
                                                        <p id="misi"><?= $value['misi'] ?></p>
                                                        <!-- <p>Program Unggulan: <?= $value['program_unggulan'] ?></p> -->
                                                        <!-- Sesuaikan dengan data kandidat yang ingin Anda tampilkan -->
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="justify-content: center;">

                                            <input type="hidden" name="id_kandidat" value="<?= $value['id_kandidat'] ?>">
                                            <input type="hidden" name="nis" value="<?= $nis ?>">
                                            <input type="hidden" name="name_device" value="<?= $name_device ?>">
                                            <button type="submit" name="btn_vote" value="YES" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin memilih <?= $value['nama_kandidat'] ?>?')"> VOTE <i class="ti ti-archive"></i></button>
                                            <!-- <button type="button" class="btn btn-secondary" id="closeModal" data-dismiss="modal">Close</button> -->
                                        </div>
                                    </form>
                                </div>
                                <div class="alert-button" onclick="closeModal()"><label class="mt-0 pt-0"><strong> x </strong></label></div>
                            </div>
                        </div>
                        <!-- End Modal -->
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="card-footer text-center text-primary">
                &copy; SMP N 1 KARANGANYAR - <?= date("Y") ?> | All rights reserved <br>
                <i class="text-sm">Powered By: Half Tech Digital Solutions | Software House Karanganyar</i>
            </div>
        </div>
    </div>


    <!-- SCRIPT: -->
    <script src="../src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showModal(id) {
            // Panggil modal berdasarkan ID modalnya
            $("#detailModal" + id).modal("show");
        }
    </script>
    <!-- <script>
        function closeModal() {
            // Sembunyikan modal dengan menghilangkan kelas 'show'
            document.querySelector(".modal").classList.remove("show");
        }
    </script> -->

</body>

</html>