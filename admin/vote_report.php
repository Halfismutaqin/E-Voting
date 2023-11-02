<?php include 'proses_user_candidat.php' ?>
<!-- <?php include '../config/conn.php' ?> -->
<!-- Call header:     -->
<?php include 'header.php'; ?>

<!-- Call SideBar:     -->
<?php include 'sidebar.php'; ?>
<!-- End SideBar: -->

<!-- Call Navbar: -->
<?php include 'navbar.php'; ?>

<!-- Edit Content:  -->
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary" style="padding-top: 0; max-height: 0;">
            <h5 class="text-white"><strong></strong></h5>
        </div>
        <div class="card-body">
            <div class="row justify-content-center mt-0 mb-2">
                <div class="col text-center">
                    <h4><strong>Hasil Perolehan Suara</strong></h4>
                </div>
            </div>

            <div class="row justify-content-center" id="candidateResults">
                <?php
                $query = "SELECT kandidat.id_kandidat, kandidat.nama_kandidat, COUNT(vote.id_vote) AS jumlah_vote
                FROM tb_kandidat kandidat
                LEFT JOIN tb_vote vote ON kandidat.id_kandidat = vote.id_kandidat
                GROUP BY kandidat.id_kandidat, kandidat.nama_kandidat";

                try {
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $stmt->bind_result($id_kandidat, $nama_kandidat, $jumlah_vote);

                    while ($stmt->fetch()) {
                        // Sekarang Anda memiliki nilai $id_kandidat, $nama_kandidat, dan $jumlah_vote
                        // yang dapat Anda gunakan untuk menampilkan hasil perolehan suara

                ?>
                        <div class="col-3">
                            <div class="card bg-light-warning" style="box-shadow: 1; border: 1px;">
                                <div class="card-header text-center bg-warning">
                                    <h4 class="text-white">
                                        <strong><?= $nama_kandidat ?></strong>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <h5 class="text-danger"><strong>Jumlah vote: </strong></h5>
                                    <h1 class="text-center mt-2"><strong id="voteCount<?= $id_kandidat ?>"><?= $jumlah_vote ?></strong></h1>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                } ?>
            </div>

            <!-- -------------------------------------------------------------- -->
            <hr>
            <?php
            // Mendapatkan jumlah total data pemilih
            $queryTotalPemilih = "SELECT COUNT(*) AS total_pemilih FROM tb_pemilih";
            $stmtTotalPemilih = $conn->prepare($queryTotalPemilih);
            $stmtTotalPemilih->execute();
            $stmtTotalPemilih->bind_result($totalPemilih);
            $stmtTotalPemilih->fetch();
            $stmtTotalPemilih->close();

            // Mendapatkan jumlah total data suara (vote)
            $queryTotalSuara = "SELECT COUNT(*) AS total_suara FROM tb_vote";
            $stmtTotalSuara = $conn->prepare($queryTotalSuara);
            $stmtTotalSuara->execute();
            $stmtTotalSuara->bind_result($totalSuara);
            $stmtTotalSuara->fetch();
            $stmtTotalSuara->close();
            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="text-white"><strong>Total Data Pemilih</strong></h5>
                        </div>
                        <div class="card-body">
                            <h1 class="text-center"><strong><?= $totalPemilih ?></strong></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h5 class="text-white"><strong>Total Suara Masuk</strong></h5>
                        </div>
                        <div class="card-body">
                            <h1 class="text-center"><strong><?= $totalSuara ?></strong></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



<!-- End Content -->
<!-- script tambahan: -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script> -->

<script>
    setInterval(function() {
        location.reload();
    }, 3000); // 3000 milidetik = 3 detik
</script>

<!-- <script>
    // Fungsi untuk memperbarui hasil perolehan suara secara berkala
    function updateVoteResults() {
        $.ajax({
            url: 'vote_result_ajax.php', // Ganti dengan URL yang sesuai
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (Array.isArray(response)) {
                    response.forEach(function(obj, index) {
                        var voteCount = obj.jumlah_vote;
                        var id_kandidat = obj.id_kandidat;
                        $("#voteCount" + id_kandidat).text(voteCount);
                    });
                } else {
                    console.log("Invalid JSON response format.");
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    // Memperbarui hasil perolehan suara setiap 1 detik
    setInterval(updateVoteResults, 1000); // Ganti waktu interval sesuai kebutuhan
</script> -->
<!-- Footer: -->
<?php include 'footer.php'; ?>