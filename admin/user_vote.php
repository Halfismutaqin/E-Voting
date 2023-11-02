<?php
$message = '';
if (isset($_GET['init'])) {
    $init = $_GET['init'];

    if ($init == 'update') {
        $message = 'Data pemilih berhasil diubah';
    } else if ($init == 'add') {
        $message = 'Berhasil menambah data pemilih';
    } else if ($init == 'import') {
        $message = 'Berhasil import data users';
    } else if ($init == 'delete') {
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
            <h5 class="text-white"><strong>List User Data</strong></h5>
        </div>
        <div class="card-body">
            <div class="row m-2">
                <button class="btn btn-primary m-1" style="width: 140px;" data-toggle="modal" onclick="showModalAdd()"><i class="ti ti-plus"></i> Add User</button>
                <button class="btn btn-success m-1" style="width: 140px;" data-toggle="modal" onclick="showModalImport()"><i class="ti ti-upload"></i> Import User</button>
            </div>
            <br>
            <table id="myDataTable" class="table table-responsive table-bordered table-striped hover no-warp w-100">
                <thead class="bg-light mt-4">
                    <tr class="text-center">
                        <th>No</th>
                        <th class="w-25">ID/ NIS</th>
                        <th class="w-25">Nama</th>
                        <th class="w-25">Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'proses_user_vote.php';

                    $no = 1;
                    $users = list_users();
                    foreach ($users as $key => $data) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center"><?= $data['nis'] ?></td>
                            <td class="text-start"><?= $data['nama'] ?></td>
                            <td class="text-center"><?= $data['kelas'] ?></td>
                            <td class="text-center"><a href="proses_user_vote.php?delete_id=<?= $data['nis'] ?>" class="btn btn-danger"><i class="ti ti-trash"></i> Delete </button></a>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal "Tambah User" -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
            </div>
            <form action="proses_user_vote.php" method="post">
                <div class="modal-body">
                    <div class="form-group mt-1">
                        <label for="userId">ID/NIS</label>
                        <input type="text" class="form-control" id="userId" name="userId" required>
                    </div>
                    <div class="form-group mt-1">
                        <label for="userName">Nama</label>
                        <input type="text" class="form-control" id="userName" name="userName" required>
                    </div>
                    <div class="form-group mt-1">
                        <label for="userClass">Kelas</label>
                        <input type="text" class="form-control" id="userClass" name="userClass" required>
                    </div>
                    <div class="form-group mt-1">
                        <label for="jenkel">Jenis Kelamin</label>
                        <select name="jenkel" id="jenkel" class="form-control" required>
                            <option value="" disabled selected>-</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="username" value="<?= $s_username ?>">
                    <button type="submit" name="btn_addUser" value="Add" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal "Import User" -->
<div class="modal fade" id="importUserModal" tabindex="-1" role="dialog" aria-labelledby="importUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Isi modal "Import User" sesuai kebutuhan -->
                <form method="post" enctype="multipart/form-data" action="proses_user_vote.php">
                    <label class="mt-2 mb-2"><strong>Pilih File:</strong></label> <span class="text-danger text-sm">(Format .xls)</span> <br>
                    <input class="form-control" name="dataUsers" type="file" required="required">
                    <br>
                    <input type="hidden" name="s_user" value="<?= $s_username ?>">
                    <button class="btn btn-primary" name="btn_upload" type="submit" value="Import"> Submit </button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- End Content -->
<!-- script tambahan: -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script> -->

<script>
    function showModalAdd() {
        // Panggil modal berdasarkan ID modalnya
        $("#addUserModal").modal("show");
    }

    function showModalImport() {
        // Panggil modal berdasarkan ID modalnya
        $("#importUserModal").modal("show");
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

<script>
    var $ = jQuery;

        $(document).ready(function() {
            $('#myDataTable').DataTable();
        });

</script>
<!-- Footer: -->
<?php include 'footer.php'; ?>