<?php
include '../config/conn.php';
// Query untuk mendapatkan hasil perolehan suara kandidat
$query = "SELECT id_kandidat, COUNT(*) AS jumlah_vote FROM tb_vote GROUP BY id_kandidat";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $voteResults = array();

    while ($row = $result->fetch_assoc()) {
        $voteResults[] = $row;
    }

    // Kembalikan hasil dalam format JSON
    echo json_encode($voteResults);
} else {
    echo "Belum ada hasil perolehan suara.";
}

$conn->close();
