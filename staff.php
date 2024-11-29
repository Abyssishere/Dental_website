<?php
// Start session to check login status
session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

// Database connection details
$servername = "localhost:3309";  // Database server (adjust port if necessary)
$username = "root";              // Database username
$password = "";                  // Database password (leave blank for default XAMPP)
$dbname = "db_mike";             // Database name

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get future appointments (ignore time part)
$futureAppointmentsQuery = "
    SELECT Patients.FullName, Patients.Email, Appointments.AppointmentDate, Appointments.AppointmentTime, Appointments.ReasonForVisit, Appointments.DoctorName
    FROM Patients
    JOIN Appointments ON Patients.PatientID = Appointments.PatientID
    WHERE DATE(Appointments.AppointmentDate) >= CURDATE()
    ORDER BY Appointments.AppointmentDate, Appointments.AppointmentTime
";

// Get past appointments (ignore time part)
$pastAppointmentsQuery = "
    SELECT Patients.FullName, Patients.Email, Appointments.AppointmentDate, Appointments.AppointmentTime, Appointments.ReasonForVisit, Appointments.DoctorName
    FROM Patients
    JOIN Appointments ON Patients.PatientID = Appointments.PatientID
    WHERE DATE(Appointments.AppointmentDate) < CURDATE()
    ORDER BY Appointments.AppointmentDate DESC
";

// Execute queries
$futureAppointments = $conn->query($futureAppointmentsQuery);
$pastAppointments = $conn->query($pastAppointmentsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
            padding: 20px;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        td {
            color: #555;
        }

        .no-data {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 20px;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #000;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<h2>Staff Reports</h2>

<!-- Future Appointments Table -->
<h3>Future Appointments</h3>
<table>
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>Patient Email</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Reason for Visit</th>
            <th>Doctor's Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if query execution was successful and if there are any rows returned
        if ($futureAppointments && $futureAppointments->num_rows > 0) {
            while ($row = $futureAppointments->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['FullName']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['AppointmentDate']}</td>
                        <td>{$row['AppointmentTime']}</td>
                        <td>{$row['ReasonForVisit']}</td>
                        <td>{$row['DoctorName']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-data'>No future appointments found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Past Appointments Table -->
<h3>Past Appointments</h3>
<table>
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>Patient Email</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Reason for Visit</th>
            <th>Doctor's Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if query execution was successful and if there are any rows returned
        if ($pastAppointments && $pastAppointments->num_rows > 0) {
            while ($row = $pastAppointments->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['FullName']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['AppointmentDate']}</td>
                        <td>{$row['AppointmentTime']}</td>
                        <td>{$row['ReasonForVisit']}</td>
                        <td>{$row['DoctorName']}</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-data'>No past appointments found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<footer>
    <p>&copy; 2024 Dental Practice. All rights reserved.</p>
</footer>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
