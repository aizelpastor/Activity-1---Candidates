<?php
// useform.php

include('connector.php');  // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and store form inputs
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $registration_date = date("Y-m-d H:i:s");

    // Gender array to string
    $gender = isset($_POST['gender']) ? implode(", ", $_POST['gender']) : '';

    // Date of birth array to string
    $date_of_birth = isset($_POST['date_of_birth']) ? implode(", ", $_POST['date_of_birth']) : '';

    try {
        // Insert candidate data
        $stmt = $conn->prepare("INSERT INTO candidates (first_name, last_name, date_of_birth, gender, email, registration_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $date_of_birth, $gender, $email, $registration_date]);

        $candidate_id = $conn->lastInsertId(); // Get the last inserted ID

        // Insert phone number
        $stmt = $conn->prepare("INSERT INTO phone_numbers (candidate_id, phone_number) VALUES (?, ?)");
        $stmt->execute([$candidate_id, $phone_number]);

        // Redirect to the displayNormalizationTB.php after successful insertion
        header("Location: displayNormalizationTB.php");
        exit(); // Make sure to stop further execution
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
