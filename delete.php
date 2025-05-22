<?php
// delete.php

include('connector.php');

// Check if delete button was clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $candidate_id = $_POST['delete_id'];

    // Delete the candidate from the phone_numbers table first (foreign key)
    $stmt = $conn->prepare("DELETE FROM phone_numbers WHERE candidate_id = ?");
    $stmt->execute([$candidate_id]);

    // Delete the candidate from the candidates table
    $stmt = $conn->prepare("DELETE FROM candidates WHERE candidate_id = ?");
    $stmt->execute([$candidate_id]);

    echo "Candidate deleted successfully!";
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<form action="displayNormalizationTB.php">
    <button type="submit">Show</button>
</form>
</body>
</html>