<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['task_id']) || !isset($_POST['comment'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Nedostaje task_id ili komentar']);
        exit;
    }

    $task_id = (int)$_POST['task_id'];
    $comment = $db->real_escape_string($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO komentari_zadatka (task_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iis", $task_id, $user_id, $comment);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Komentar dodat']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Greška pri upisu: ' . $db->error]);
    }
}