<?php

include '../config/conn.php';
// Load file autoload.php
require 'vendor/autoload.php';
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


// Fungsi untuk mengimpor data dari Excel ke database
function importDataFromExcel($excelFilePath)
{
    global $conn;
    // Buat objek Spreadsheet
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excelFilePath);

    // Pilih sheet yang ingin dibaca (misalnya, sheet pertama)
    $sheet = $spreadsheet->getActiveSheet();

    // Inisialisasi nomor baris
    $baris = 0;

    // Inisialisasi array untuk melacak NIS yang sudah ada
    $nisSudahAda = array();

    // Koneksi ke database
    // $conn = new mysqli("host", "username", "password", "nama_database");

    // Loop melalui baris-baris dalam sheet
    foreach ($sheet->getRowIterator() as $row) {
        $baris++;

        // Melewati baris pertama (header)
        if ($baris === 1) {
            continue;
        }

        $cellIterator = $row->getCellIterator();
        $cellData = [];

        foreach ($cellIterator as $cell) {
            $cellData[] = $cell->getValue();
        }

        // Sesuaikan dengan kolom yang sesuai dengan data Excel Anda
        $nis = $cellData[0];
        $nama = $cellData[1];
        $kelas = $cellData[2];
        $jenkel = $cellData[3];
        $createdBy = $_POST['s_user'];

        // Periksa jika NIS sudah ada dalam array $nisSudahAda
        if (in_array($nis, $nisSudahAda)) {
            echo "NIS $nis dari baris ke-$baris sudah ada dan akan dilewati.<br>";
            continue;
        }

        // Periksa jika NIS sudah ada dalam database
        $queryCekNIS = "SELECT COUNT(*) FROM tb_pemilih WHERE nis = ?";
        $stmtCekNIS = $conn->prepare($queryCekNIS);
        $stmtCekNIS->bind_param("s", $nis);
        $stmtCekNIS->execute();
        $stmtCekNIS->bind_result($nisCount);
        $stmtCekNIS->fetch();
        $stmtCekNIS->close(); // Menutup pernyataan sebelum eksekusi pernyataan berikutnya

        // Jika NIS sudah ada dalam database, maka lewati
        if ($nisCount > 0) {
            echo "NIS $nis dari baris ke-$baris sudah ada dalam database dan akan dilewati.<br>";
            $nisSudahAda[] = $nis; // Tambahkan NIS ke array $nisSudahAda
            continue;
        }

        // Buat query SQL untuk memasukkan data ke dalam tabel
        $query = "INSERT INTO tb_pemilih (nis, nama, kelas, jenkel, createdBy) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $nis, $nama, $kelas, $jenkel, $createdBy);

        // Eksekusi query SQL untuk menyimpan data ke database
        if ($stmt->execute()) {
            // Data berhasil dimasukkan ke dalam tabel
            echo "Data dari baris ke-$baris berhasil diimpor.<br>";
            $nisSudahAda[] = $nis; // Tambahkan NIS ke array $nisSudahAda
        } else {
            // Terjadi kesalahan saat memasukkan data
            echo "Error pada baris ke-$baris: " . $stmt->error . "<br>";
        }

        // Tutup statement
        $stmt->close();
    }

    // Tutup koneksi database jika diperlukan
    // $conn->close();

    header("Location: user_vote.php?init=import"); // Redirect jika boleh diakses 
}

if (isset($_POST['btn_upload']) && $_POST['btn_upload'] == 'Import') {
    // Pastikan file Excel sudah diunggah
    if ($_FILES['dataUsers']['error'] == UPLOAD_ERR_OK) {
        $tempFile = $_FILES['dataUsers']['tmp_name'];
        $targetFile = '../src/upload/file.xlsx'; // Gantilah dengan path file Excel yang dituju
        if (move_uploaded_file($tempFile, $targetFile)) {
            // Panggil fungsi import data dari Excel
            importDataFromExcel($targetFile);
        } else {
            echo "Gagal mengunggah file Excel.<br>";
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah file Excel.<br>";
    }
}


function list_users()
{
    global $conn;

    $query = "SELECT * FROM tb_pemilih ORDER BY kelas, nis";
    $result = mysqli_query($conn, $query);

    $users = array(); // Initialize an array to store candidates

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row; // Add the candidate to the array
        }
    } else {
        // You can return an empty array or handle this differently
        // For now, I'll return an empty array
    }

    return $users; // Return the array of candidates
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_addUser']) && $_POST['btn_addUser'] == 'Add') {
    // Sanitize and retrieve form data
    $nis = $_POST['userId'];
    $username = $_POST['userName'];
    $kelas = $_POST['userClass'];
    $jenkel = $_POST['jenkel'];
    $createdBy = $_POST['username'];

    // Buat query SQL untuk memeriksa apakah NIS sudah ada dalam tabel
    $checkQuery = "SELECT * FROM tb_pemilih WHERE nis = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $nis);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // NIS sudah ada, lakukan pembaruan
        $updateQuery = "UPDATE tb_pemilih SET nama = ?, kelas = ?, jenkel = ?, createdBy = ? WHERE nis = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssss", $username, $kelas, $jenkel, $createdBy, $nis);

        if ($updateStmt->execute()) {
            header("Location: user_vote.php?init=update"); // Redirect jika boleh diakses
            echo "Data user berhasil diperbarui.";
        } else {
            echo "Error: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        // NIS belum ada, lakukan penambahan
        $insertQuery = "INSERT INTO tb_pemilih (nis, nama, kelas, jenkel, createdBy) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssss", $nis, $username, $kelas, $jenkel, $createdBy);

        if ($insertStmt->execute()) {
            header("Location: user_vote.php?init=add"); // Redirect jika boleh diakses
            echo "Data user berhasil ditambahkan.";
        } else {
            echo "Error: " . $insertStmt->error;
        }

        $insertStmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
if (isset($_GET['delete_id'])) {
    // Ambil NIS dari parameter URL
    $nis = $_GET['delete_id'];

    // Buat query SQL DELETE untuk menghapus data berdasarkan NIS
    $query = "DELETE FROM tb_pemilih WHERE nis = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nis);

    if ($stmt->execute()) {
        header("Location: user_vote.php?init=delete"); // Redirect jika boleh diakses
        echo "Data dengan NIS $nis berhasil dihapus.";
    } else {
        // Terjadi kesalahan saat menghapus data
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
