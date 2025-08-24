<?php
require_once "config.php";

if (isset($_POST['task_id'])) {
    $user_id = (int)$_POST['user_id'];
    $task_id = (int)$_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $group_id = (int)$_POST['group_id'];
    $executors = $_POST['executors'] ?? []; 
    
    $stmt = $db->prepare("UPDATE zadaci SET title = ?, description = ?, deadline = ?, priority = ?, status = ?, group_id = ? WHERE id = ?");
    $stmt->bind_param("sssisii", $title, $description, $deadline, $priority, $status, $group_id, $task_id);
    $stmt->execute();

    $stmtDel = $db->prepare("DELETE FROM veza_izvrsilaczadatak WHERE task_id=?");
    $stmtDel->bind_param("i", $task_id);
    $stmtDel->execute();
    foreach ($executors as $executor_id) {
        $stmtIns = $db->prepare("INSERT INTO veza_izvrsilaczadatak (task_id, user_id, completed) VALUES (?, ?, 0)");
        $stmtIns->bind_param("ii", $task_id, $executor_id);
        $stmtIns->execute();
    }

    if (!empty($_FILES['prilozi']['name'][0])) {
        $upload_dir = __DIR__ . '/../uploads/';
        foreach ($_FILES['prilozi']['name'] as $i => $name) {
            if ($_FILES['prilozi']['error'][$i] == 0) {
                $server_path = $upload_dir . basename($name);
                $db_file_path = 'uploads/' . basename($name);

                move_uploaded_file($_FILES['prilozi']['tmp_name'][$i], $server_path);
                error_log($user_id);
                $stmtAtt = $db->prepare("INSERT INTO prilozi_zadataka (task_id, file_path, uploaded_by, created_at) VALUES (?, ?, ?, NOW())");
                $stmtAtt->bind_param("isi", $task_id, $db_file_path, $user_id);
                $stmtAtt->execute();
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Zadatak je uspešno ažuriran']);
    exit;
}
