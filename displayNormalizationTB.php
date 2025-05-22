<?php
// displayNormalizationTB.php

include('connector.php');  // Include database connection

// Fetch raw data (before normalization)
$rawDataQuery = "SELECT candidates.candidate_id, candidates.first_name, candidates.last_name, candidates.date_of_birth, candidates.gender, candidates.email, phone_numbers.phone_number
                 FROM candidates
                 LEFT JOIN phone_numbers ON candidates.candidate_id = phone_numbers.candidate_id";
$rawDataStmt = $conn->prepare($rawDataQuery);
$rawDataStmt->execute();
$rawData = $rawDataStmt->fetchAll(PDO::FETCH_ASSOC);

// 1NF: Query to fetch data in First Normal Form (includes separate rows for multiple phone numbers)
$nfQuery = "SELECT candidates.candidate_id, candidates.first_name, candidates.last_name, candidates.date_of_birth, candidates.gender, candidates.email, phone_numbers.phone_number
            FROM candidates
            INNER JOIN phone_numbers ON candidates.candidate_id = phone_numbers.candidate_id";
$nfStmt = $conn->prepare($nfQuery);
$nfStmt->execute();
$nfData = $nfStmt->fetchAll(PDO::FETCH_ASSOC);

// 2NF: Query to fetch normalized data into individual tables
$nf2Query = "SELECT candidates.candidate_id, candidates.first_name, candidates.last_name, candidates.date_of_birth, candidates.gender, candidates.email, phone_numbers.phone_number
             FROM candidates
             INNER JOIN phone_numbers ON candidates.candidate_id = phone_numbers.candidate_id";
$nf2Stmt = $conn->prepare($nf2Query);
$nf2Stmt->execute();
$nf2Data = $nf2Stmt->fetchAll(PDO::FETCH_ASSOC);

// 3NF: Further normalization based on phone numbers and candidate data
$nf3Query = "SELECT candidates.candidate_id, candidates.first_name, candidates.last_name, candidates.date_of_birth, candidates.gender, phone_numbers.phone_id, phone_numbers.phone_number
             FROM candidates
             INNER JOIN phone_numbers ON candidates.candidate_id = phone_numbers.candidate_id";
$nf3Stmt = $conn->prepare($nf3Query);
$nf3Stmt->execute();
$nf3Data = $nf3Stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normalized Data</title>
    <style>
        table {
            width: 60%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<h1>Raw Data (Before Normalization)</h1>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rawData as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
            <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
            <td>
                <form action="update.php" method="POST" style="display:inline;">
                    <button type="submit" name="update_id" value="<?php echo $row['candidate_id']; ?>">Update</button>
                </form>
                <form action="delete.php" method="POST" style="display:inline;">
                    <button type="submit" name="delete_id" value="<?php echo $row['candidate_id']; ?>">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="registration.php">
    <button type="submit">Add New</button>
</form>

<h1>First Normal Form (1NF)</h1>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nfData as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
            <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h1>Second Normal Form (2NF)</h1>

<h2>Candidate Information</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>First Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf2Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Last Name</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Last Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf2Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Date of Birth</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Date of Birth</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf2Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Gender</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Gender</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf2Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Email</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf2Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Phone Number</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf2Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h1>Third Normal Form (3NF)</h1>

<h2>Candidate Information with Phone ID</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Phone ID</th>
            <th>First Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf3Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['phone_id']); ?></td>
            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Last Name with Phone ID</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Last Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf3Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Date of Birth with Phone ID</h2>
<table>
    <thead>
        <tr>
            <th>Candidate_ID</th>
            <th>Date of Birth</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($nf3Data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
            <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
