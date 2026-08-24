<?php

require_once "../auth/check_role.php";
requireRole(["admin", "warden", "staff"]);
require_once "../config/database.php";
require_once "../config/openai.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "POST is required."]);
    exit;
}

$question = trim($_POST["question"] ?? "");
if ($question === "" || strlen($question) > 500) {
    http_response_code(422);
    echo json_encode(["error" => "Enter a question up to 500 characters."]);
    exit;
}

$summary = [
    "students" => (int) $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    "rooms" => $pdo->query("SELECT status, COUNT(*) AS total FROM rooms GROUP BY status")->fetchAll(),
    "bookings" => $pdo->query("SELECT status, COUNT(*) AS total FROM bookings GROUP BY status")->fetchAll(),
    "active_allocations" => (int) $pdo->query("SELECT COUNT(*) FROM allocations WHERE status = 'Active'")->fetchColumn(),
    "completed_payments" => (float) $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'Completed'")->fetchColumn()
];

try {
    $answer = askOpenAI(
        "You are a concise hostel management assistant. Answer only from the supplied RAO HBMIS data. "
        . "If the data is insufficient, say so. Never invent records, expose passwords, or perform actions. "
        . "Use plain text and keep the answer under 180 words.",
        "Hostel summary:\n" . json_encode($summary) . "\n\nStaff question:\n" . $question
    );
    echo json_encode(["answer" => $answer]);
} catch (Throwable $error) {
    http_response_code(503);
    echo json_encode(["error" => $error->getMessage()]);
}

?>