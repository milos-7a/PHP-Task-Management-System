<?php
require_once "config.php";

if (isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];
    $stmt = $db->prepare("DELETE FROM korisnici WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    echo "success";
}

?>