<?php
include '../config/conn.php';


function list_kandidat()
{
    global $conn;

    $query = "SELECT * FROM tb_kandidat";
    $result = mysqli_query($conn, $query);

    $candidates = array(); // Initialize an array to store candidates

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $candidates[] = $row; // Add the candidate to the array
        }
    } else {
        // You can return an empty array or handle this differently
        // For now, I'll return an empty array
    }

    return $candidates; // Return the array of candidates
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_addKandidat']) && $_POST['btn_addKandidat'] == 'Add') {
    // Sanitize and retrieve form data
    $nama = $_POST['nama'];
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];
    $createdBy = $_POST['username'];

    // Untuk mengelola file yang diunggah
    $foto = $_FILES['foto'];

    // Lokasi penyimpanan file yang diunggah
    $uploadDir = '../src/upload/';

    // Generate nama unik untuk file yang diunggah
    $fotoName = uniqid() . '_' . $foto['name'];

    // Tentukan lokasi penuh untuk penyimpanan
    $fotoPath = $uploadDir . $fotoName;

    // Pindahkan file yang diunggah ke lokasi penyimpanan
    if (move_uploaded_file($foto['tmp_name'], $fotoPath)) {
        // File berhasil diunggah, sekarang masukkan informasinya ke database
        $insertQuery = "INSERT INTO tb_kandidat (nama_kandidat, visi, misi, foto, createdBy) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssss", $nama, $visi, $misi, $fotoName, $createdBy);

        if ($insertStmt->execute()) {
            header("Location: user_candidat.php?init=add");
            echo "Data kandidat berhasil ditambahkan.";
        } else {
            echo "Error: " . $insertStmt->error;
        }

        $insertStmt->close();
    } else {
        echo "Error: Gagal mengunggah file.";
    }
}

if (isset($_GET['delete_id'])) {
    // Ambil NIS dari parameter URL
    $id_kandidat = $_GET['delete_id'];

    // Buat query SQL DELETE untuk menghapus data berdasarkan NIS
    $query = "DELETE FROM tb_kandidat WHERE id_kandidat = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_kandidat);

    if ($stmt->execute()) {
        header("Location: user_candidat.php?init=delete"); // Redirect jika boleh diakses
        echo "Data Kandidat berhasil dihapus.";
    } else {
        // Terjadi kesalahan saat menghapus data
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
