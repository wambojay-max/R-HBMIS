<?php

require_once "../../../auth/check_role.php";

requireRole(["admin", "warden", "staff"]);

require_once "../../../config/database.php";

$stmt = $pdo->query("
    SELECT *
    FROM allocations
    ORDER BY id DESC
");

$allocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Allocation Report - RAO Hostel Booking and Management Information System</title>

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

        <h1>Allocation Report</h1>

        <p>
            List of student room allocations.
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
                    <th>Student ID</th>
                    <th>Room ID</th>
                    <th>Status</th>
                    <th>Allocated At</th>

                </tr>

            </thead>


            <tbody>

            <?php if (count($allocations) > 0): ?>

                <?php foreach ($allocations as $index => $allocation): ?>

                    <tr>

                        <td>
                            <?php echo $index + 1; ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $allocation['student_id'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $allocation['room_id'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $allocation['status'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $allocation['allocated_at'] ?? ''
                            );
                            ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="5">
                        No allocations found.
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