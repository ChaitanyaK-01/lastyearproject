<?php
// Retrieve form data
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$venue = $_POST['venue'];
$datetime = $_POST['datetime'];
$number = $_POST['number'];
$email = $_POST['email'];
$photography_type = $_POST['photography_type'];

// Database connection
$dbconn = pg_connect("host=localhost dbname=abc user=postgres password=sunny");

// Check for double entry
$query = "SELECT * FROM bookings WHERE email = $1 AND datetime = $2";
$result = pg_prepare($dbconn, "check_entry", $query);
$result = pg_execute($dbconn, "check_entry", array($email, $datetime));

if (pg_num_rows($result) > 0) {
    echo "Booking already exists for this email and date/time.";
} else {
    // Insert data into database
    $insert_query = "INSERT INTO bookings (firstname, lastname, venue, datetime, number, email, photography_type) VALUES ($1, $2, $3, $4, $5, $6, $7)";
    $result = pg_prepare($dbconn, "insert_booking", $insert_query);
    $result = pg_execute($dbconn, "insert_booking", array($firstname, $lastname, $venue, $datetime, $number, $email, $photography_type));

    if ($result) {
        echo "Booking successful!";
    } else {
        echo "Error in booking. Please try again.";
    }
}

// Close database connection
pg_close($dbconn);
?>
