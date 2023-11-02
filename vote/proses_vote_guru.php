<?php
include '../config/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_vote']) && $_POST['btn_vote'] == 'YES') {
    // Sanitize and retrieve form data
    $nis = $_POST['nis'];
    $id_kandidat = $_POST['id_kandidat'];
    $device = $_POST['name_device'];

    // var_dump($_POST);
    // die;

    // Check if the nis has already voted
    $query = "SELECT COUNT(*) FROM tb_vote WHERE nis = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nis);
    $stmt->execute();
    $stmt->bind_result($voteCount);
    $stmt->fetch();
    $stmt->close();

    // if ($voteCount > 0) {
    //     echo "The voter with nis $nis has already voted.";
    //     header("location: succes_vote.php?id=$nis&flag=1");
    // } else {
        // Prepare and execute the SQL INSERT query
        $insertQuery = "INSERT INTO tb_vote (nis, id_kandidat, status, access) VALUES (?, ?, '1', ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sis", $nis, $id_kandidat, $device);

        if ($insertStmt->execute()) {
            // The data was inserted successfully
            echo "Vote recorded successfully.";
            header("location: succes_vote_guru.php?id=$nis&flag=0");

        } else {
            // An error occurred while inserting the data
            echo "Error recording vote: " . $insertStmt->error;
            // header("location: vote.php");
        }

        $insertStmt->close();
    // }

    $conn->close();
}
?>
