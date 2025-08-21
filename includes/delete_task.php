<?php
require "config.php"; 

if (isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    $stmt = $db->prepare("DELETE FROM zadaci WHERE id = ?");
    $stmt->bind_param('i', $task_id);
    $stmt->execute();

    echo "success";
}

?>