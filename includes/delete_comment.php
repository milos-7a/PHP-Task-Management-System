<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = (int)$_POST['comment_id'];
    $user_id = (int)$_POST['user_id'];
    $query = "DELETE FROM komentari_zadatka WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        http_response_code(500);
        echo "Greška: " . $stmt->error;
    }
}
?>
