<?php
$message = '';
if (isset($_GET['init'])) {
    $init = $_GET['init'];

    if ($init == 'update') {
        $message = 'Data pemilih berhasil diubah';
    } else if ($init == 'add') {
        $message = 'Berhasil menambah data pemilih';
    }
    if ($init == 'delete') {
        $message = 'Berhasil menghapus data pemilih';
    }
}

?>

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
        <div class="card-header bg-primary">
            <h5 class="text-white"><strong>List Kandidat</strong></h5>
        </div>
        <div class="card-body">
            <div class="row m-2">
                <button class="btn btn-primary m-1" style="width: 140px;" data-toggle="modal" onclick="showModalAdd()"><i class="ti ti-plus"></i> Kandidat</button>
                <!-- <button class="btn btn-success m-1" style="width: 140px;" data-toggle="modal" data-target="#importUserModal"><i class="ti ti-upload"></i> Import User</button> -->
            </div>
            <br>
            <table class="table table-responsive table-striped table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Visi</th>
                    <th class="w-50">Misi</th>
                    <th>Aksi</th>
                </tr>
                <?php
                include 'proses_user_candidat.php';

                $no = 1;
                $candidates = list_kandidat();
                foreach ($candidates as $key => $data) {
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <!-- <td class="text-center"><?= $data['id_kandidat'] ?></td> -->
                        <td class="text-start"><?= $data['nama_kandidat'] ?></td>
                        <td class="text-center"><?= $data['visi'] ?></td>
                        <td class="text-start"><?= $data['misi'] ?></td>
                        <td class="text-center"><a href="proses_user_candidat.php?delete_id=<?= $data['id_kandidat'] ?>" class="btn btn-danger"><i class="ti ti-trash"></i> Delete </button></a>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<!-- Modal "Tambah User" -->
<div class="modal  modal-xl fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
            </div>
            <form action="proses_user_candidat.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mt-1">
                        <label for="nama">Nama Kandidat</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group mt-1">
                        <label for="visi">Visi</label>
                        <input type="text" class="form-control" id="visi" name="visi" required>
                    </div>
                    
                    <div class="form-group mt-1">
                        <label for="foto">foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>

                    <div class="form-group mt-1">
                        <label>Misi</label>
                        <textarea id="summernote" type="text" class="form-control" name="misi" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="username" value="<?= $s_username ?>">
                    <button type="submit" name="btn_addKandidat" value="Add" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal "Import User" -->
<div class="modal fade" id="importUserModal" tabindex="-1" role="dialog" aria-labelledby="importUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Isi modal "Import User" sesuai kebutuhan -->
        </div>
    </div>
</div>


<!-- End Content -->
<!-- script tambahan: -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>


<script>
    $(document).ready(function() {
        $('#summernote').summernote({
        });
    });
</script>

<script>
    function showModalAdd() {
        // Panggil modal berdasarkan ID modalnya
        $("#addUserModal").modal("show");
    }
</script>
<!-- JavaScript code to display the message as an alert -->
<script>
    var message = "<?php echo $message; ?>";
    if (message !== '') {
        alert(message);
        // Hapus parameter 'init' dari URL
        var urlWithoutInit = window.location.href.split('?')[0];
        window.history.replaceState({}, document.title, urlWithoutInit);
        // Muat ulang halaman
        location.reload();
    }
</script>


<!-- Footer: -->
<?php include 'footer.php'; ?>