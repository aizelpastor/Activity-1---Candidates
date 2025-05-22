<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
</head>
<body>
    <h1>Candidate Registration Form</h1>
    <form action="useform.php" method="POST"> <!-- Changed the action to useform.php -->
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br>

        <label for="gender">Gender:</label>
        <input type="checkbox" name="gender[]" value="Male"> Male
        <input type="checkbox" name="gender[]" value="Female"> Female
        <input type="checkbox" name="gender[]" value="Other"> Other<br>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth[]" multiple><br>

        <button type="submit">Submit</button>
    </form>
    <form action="displayNormalizationTB.php">
        <br>

    <button type="submit">Show All</button>
</form>
</body>
</html>
