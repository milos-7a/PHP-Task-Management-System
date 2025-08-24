<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = (int)$_POST['comment_id'];
    $comment = $_POST['comment'];
    $query = "UPDATE komentari_zadatka SET comment = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $comment, $comment_id);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        http_response_code(500);
        echo "Greška: " . $stmt->error;
    }
}
?>
