<?php
// require '../config/conn.php';

// Cek akses public or privat vote:
function cek_access()
{
    global $conn; // Anda perlu mengakses $conn dari luar fungsi

    $query = "SELECT * FROM tb_config WHERE nama_config ='config_public' ";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // echo "ID: " . $row["id_config"] . " - Nama: " . $row["nama_config"] . "<br>";
            return $row;
        }
    } else {
        echo "Tidak ada data ditemukan.";
    }
}

// Cek akses user login e-vote:
function cek_login()
{
    global $conn; 

    $query = "SELECT * FROM tb_user WHERE nis ='nis' ";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row;
        }
    } else {
        echo "Tidak ada data ditemukan.";
    }
}

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

