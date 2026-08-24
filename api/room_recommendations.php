<?php

require_once "../auth/check_role.php";
requireRole(["admin", "warden"]);
require_once "../config/database.php";
require_once "../config/openai.php";

header("Content-Type: application/json");

$students = $pdo->query(
    "SELECT s.student_id, s.full_name, s.gender, s.course, s.year_of_study
     FROM students s
     INNER JOIN bookings b ON b.student_id = s.id AND b.status = 'Confirmed'
     WHERE NOT EXISTS (
         SELECT 1 FROM allocations a
         WHERE a.student_id = s.id AND a.status = 'Active'
     )
     ORDER BY s.full_name ASC
     LIMIT 30"
)->fetchAll();

$rooms = $pdo->query(
    "SELECT r.room_number, r.room_type, r.capacity, r.floor, r.gender,
            (SELECT COUNT(*) FROM allocations a
             WHERE a.room_id = r.id AND a.status = 'Active') AS occupants
     FROM rooms r
     WHERE r.status = 'Available'
     ORDER BY r.room_number ASC
     LIMIT 50"
)->fetchAll();

if (!$students || !$rooms) {
    echo json_encode(["recommendations" => [], "message" => "There are no confirmed unallocated students or available rooms."]);
    exit;
}

try {
    $answer = askOpenAI(
        "You recommend hostel rooms. Return valid JSON only as an array of objects with keys "
        . "student_id, room_number, reason. Match gender exactly and never exceed room capacity. "
        . "Recommend at most one room per student and only use supplied records. Do not make database changes.",
        "Students needing allocation:\n" . json_encode($students)
        . "\n\nAvailable rooms:\n" . json_encode($rooms)
    );
    $recommendations = json_decode($answer, true);

    if (!is_array($recommendations)) {
        throw new RuntimeException("AI returned invalid recommendations.");
    }

    echo json_encode(["recommendations" => array_slice($recommendations, 0, 30)]);
} catch (Throwable $error) {
    http_response_code(503);
    echo json_encode(["error" => $error->getMessage()]);
}

?>