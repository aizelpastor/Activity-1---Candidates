<?php
// Include your database connection file
include('connector.php');

// Initialize variables
$candidate_id = '';
$first_name = '';
$last_name = '';
$email = '';
$phone_number = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data from the form
    $candidate_id = $_POST['candidate_id'] ?? ''; // Default empty string if candidate_id doesn't exist
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? ''; // Handle phone_number

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo "First name, last name, and email are required!";
        exit; // Exit if the required fields are not set
    }

    // Update candidate information query
    $updateQuery = "UPDATE candidates
                    SET first_name = :first_name, last_name = :last_name, email = :email
                    WHERE candidate_id = :candidate_id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':candidate_id', $candidate_id);

    // Execute the update query
    if ($stmt->execute()) {
        echo "Candidate information updated successfully!<br>";
    } else {
        echo "Error updating candidate information.<br>";
    }

    // If phone number is provided, update it
    if (!empty($phone_number)) {
        // Check if the phone number has changed before updating
        $phoneUpdateQuery = "SELECT phone_number FROM phone_numbers WHERE candidate_id = :candidate_id";
        $phoneStmt = $conn->prepare($phoneUpdateQuery);
        $phoneStmt->bindParam(':candidate_id', $candidate_id);
        $phoneStmt->execute();
        $existingPhone = $phoneStmt->fetch(PDO::FETCH_ASSOC);

        // Only update if the phone number has changed
        if ($existingPhone['phone_number'] !== $phone_number) {
            $phoneUpdateQuery = "UPDATE phone_numbers SET phone_number = :phone_number WHERE candidate_id = :candidate_id";
            $phoneStmt = $conn->prepare($phoneUpdateQuery);
            $phoneStmt->bindParam(':phone_number', $phone_number);
            $phoneStmt->bindParam(':candidate_id', $candidate_id);

            // Execute the phone number update query
            if ($phoneStmt->execute()) {
                echo "Phone number updated successfully!<br>";
            } else {
                echo "Error updating phone number.<br>";
            }
        }
    }
}

// Fetch the candidate data to pre-fill the form (if editing an existing candidate)
if (isset($_GET['candidate_id'])) {
    $candidate_id = $_GET['candidate_id'];

    $selectQuery = "SELECT * FROM candidates WHERE candidate_id = :candidate_id";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bindParam(':candidate_id', $candidate_id);
    $stmt->execute();
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($candidate) {
        // Fetch the phone number for this candidate
        $phoneQuery = "SELECT phone_number FROM phone_numbers WHERE candidate_id = :candidate_id";
        $phoneStmt = $conn->prepare($phoneQuery);
        $phoneStmt->bindParam(':candidate_id', $candidate_id);
        $phoneStmt->execute();
        $phone = $phoneStmt->fetch(PDO::FETCH_ASSOC);

        // Pre-fill the form with existing data
        $first_name = $candidate['first_name'];
        $last_name = $candidate['last_name'];
        $email = $candidate['email'];
        $phone_number = $phone['phone_number'] ?? ''; // Default to empty string if no phone number
    }
}
?>

<!-- HTML Form to Update Candidate Information -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Candidate Information</title>
</head>
<body>
    <h2>Update Candidate Information</h2>
    <form action="update.php" method="POST">
        <!-- Hidden input to pass candidate_id to update query -->
        <input type="hidden" name="candidate_id" value="<?php echo htmlspecialchars($candidate_id); ?>">

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>"><br><br>

        <button type="submit">Update</button>
    </form>

    <!-- Back to Registration page button -->
    <form action="registration.php">
        <button type="submit">Back to Registration</button>
    </form>
</body>
</html>
