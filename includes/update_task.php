<?php
require "config.php"; 

if (isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE zadaci SET title = ?, description = ?, deadline = ?, priority = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssisi", $title, $description, $deadline, $priority, $status, $task_id);
    $stmt->execute();

    echo "success";
}
