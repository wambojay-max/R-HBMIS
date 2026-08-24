<?php

require_once "../../../auth/check_role.php";

requireRole(["admin", "warden", "staff"]);

require_once "../../../config/database.php";

$stmt = $pdo->query("
    SELECT *
    FROM students
    ORDER BY id DESC
");

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Report - RAO Hostel Booking and Management Information System</title>

    <link rel="stylesheet" href="../../../assets/css/style.css">

</head>

<body>

<div class="sidebar">

    <h2>RAO Hostel Booking and Management Information System</h2>

    <a href="../../../dashboard.php">
        Dashboard
    </a>

    <div class="section-title">
        Hostel Management
    </div>

    <a href="../../students/index.php">
        Students
    </a>

    <a href="../../rooms/index.php">
        Rooms
    </a>

    <a href="../../bookings/index.php">
        Bookings
    </a>

    <a href="../../allocations/index.php">
        Allocations
    </a>

    <a href="../../payments/index.php">
        Payments
    </a>

    <div class="section-title">
        Reports
    </div>

    <a href="../index.php">
        Reports
    </a>

    <div class="section-title">
        System
    </div>

    <a href="../../users/index.php">
        Users
    </a>

    <div class="logout">

        <a href="../../../auth/logout.php">
            Logout
        </a>

    </div>

</div>


<div class="main-content">

    <div class="header">

        <h1>Student Report</h1>

        <p>
            List of students registered in RAO Hostel Booking and Management Information System.
        </p>

    </div>


    <div style="margin-bottom: 20px;">

        <button onclick="window.print()">
            🖨️ Print Report
        </button>

        <a href="../index.php">
            ← Back to Reports
        </a>

    </div>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Admission No</th>

                    <th>Student Name</th>

                    <th>Gender</th>

                    <th>Phone</th>

                    <th>Course</th>

                    <th>Status</th>

                </tr>

            </thead>


            <tbody>

            <?php if (count($students) > 0): ?>

                <?php foreach ($students as $index => $student): ?>

                    <tr>

                        <td>
                            <?php echo $index + 1; ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $student['admission_no'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $student['full_name'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $student['gender'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $student['phone'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $student['course'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $student['status'] ?? ''
                            );
                            ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="7">
                        No students found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>


    <br>

    <a href="../index.php">
        ← Back to Reports
    </a>

</div>

</body>

</html>