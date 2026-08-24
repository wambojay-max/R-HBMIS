<?php

require_once "../../../auth/check_role.php";

requireRole(["admin", "warden", "staff"]);

require_once "../../../config/database.php";

$stmt = $pdo->query("
    SELECT *
    FROM payments
    ORDER BY id DESC
");

$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Payment Report - RAO Hostel Booking and Management Information System</title>

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

        <h1>Payment Report</h1>

        <p>
            List of payments recorded in RAO Hostel Booking and Management Information System.
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
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Payment Date</th>

                </tr>

            </thead>


            <tbody>

            <?php if (count($payments) > 0): ?>

                <?php foreach ($payments as $index => $payment): ?>

                    <tr>

                        <td>
                            <?php echo $index + 1; ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $payment['student_id'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            KSh
                            <?php
                            echo number_format(
                                (float)($payment['amount'] ?? 0),
                                2
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $payment['payment_method'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $payment['status'] ?? ''
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $payment['payment_date'] ?? ''
                            );
                            ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6">
                        No payments found.
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