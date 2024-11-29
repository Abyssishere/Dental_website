<?php
// Database connection details
$servername = "localhost:3309";   
$username = "root";               
$password = "";                   
$dbname = "db_mike";      

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $patient_name = trim($_POST['patient_name']);
    $patient_email = trim($_POST['patient_email']);
    $appointment_date = trim($_POST['appointment_date']);
    $appointment_time = trim($_POST['appointment_time']);
    $reason_for_visit = trim($_POST['reason_for_visit']);
    $doctor_name = trim($_POST['doctor_name']);

    // Validate form data
    if (empty($patient_name) || empty($patient_email) || empty($appointment_date) || empty($appointment_time) || empty($reason_for_visit) || empty($doctor_name)) {
        echo "<p style='color:red;'>All fields are required. Please fill in all the information.</p>";
    } else {
        // Insert appointment into the database
        $sql = "INSERT INTO Appointments (PatientName, PatientEmail, AppointmentDate, AppointmentTime, ReasonForVisit, DoctorName) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        
        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die("Error preparing SQL statement: " . $conn->error);
        }

        // Bind parameters to the SQL query and execute
        $stmt->bind_param("ssssss", $patient_name, $patient_email, $appointment_date, $appointment_time, $reason_for_visit, $doctor_name);

        // Execute and check if insertion was successful
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Appointment successfully booked!</p>";
        } else {
            echo "<p style='color:red;'>Error booking appointment. Please try again later.</p>";
        }

        // Close the prepared statement
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>