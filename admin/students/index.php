<?php

require_once "../../auth/check_role.php";

requireRole(["admin", "warden", "staff"]);

require_once "../../config/database.php";

$sql = "SELECT * FROM students ORDER BY id DESC";
$stmt = $pdo->query($sql);

$students = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students - RAO Hostel Booking and Management Information System</title>
</head>

<body>

    <h1>RAO Hostel Booking and Management Information System</h1>

    <h2>Students</h2>

    <p>
        Welcome,
        <strong><?php echo htmlspecialchars($_SESSION["full_name"]); ?></strong>
    </p>

    <a href="add.php">+ Add Student</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">

        <tr>
            <th>#</th>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Course</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>

        <?php if (count($students) > 0): ?>

            <?php foreach ($students as $student): ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($student["id"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["student_id"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["full_name"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["gender"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["phone"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["email"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["course"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($student["year_of_study"]); ?>
                    </td>

                    <td>

    <a href="edit.php?id=<?php echo $student['id']; ?>">
        Edit
    </a>

    &nbsp; | &nbsp;

    <form method="POST" action="delete.php" style="display:inline;"
          onsubmit="return confirm('Are you sure you want to delete this student?');">

        <input type="hidden"
               name="id"
               value="<?php echo $student['id']; ?>">

        <button type="submit">
            Delete
        </button>

    </form>

</td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="9">
                    No students found.
                </td>
            </tr>

        <?php endif; ?>

    </table>

    <br>

    <a href="../../dashboard.php">← Back to Dashboard</a>

</body>
</html>