<?php   
require_once "config.php";
function require_role($role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
        header("Location: login.php");
        exit;
    }
}

function getUserById($db, $user_id) {
    $query = "SELECT id, username, name, email, role FROM korisnici WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}

function getComments($db, $tasks){
    $comments = [];

    foreach ($tasks as $task) {
        $query = "SELECT k.id, k.comment, k.created_at, u.name 
                  FROM komentari_zadatka k 
                  JOIN korisnici u ON k.user_id = u.id 
                  WHERE k.task_id = ?
                  ORDER BY k.created_at ASC";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $task['id']);
        $stmt->execute();

        $result = $stmt->get_result();
        $comments[$task['id']] = $result->fetch_all(MYSQLI_ASSOC);
    }
    return $comments;
}

function getAttachments($db, $tasks){
    $attachments = [];
    foreach ($tasks as $task) {
        $query = "SELECT id, file_path FROM prilozi_zadataka WHERE task_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $task['id']);
        $stmt->execute();
        $attachments[$task['id']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    return $attachments;
}

function hasUserCompletedTask($db, $task_id, $user_id) {
    $query = "SELECT completed FROM veza_izvrsilaczadatak WHERE task_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Greška pri pripremi upita: " . $db->error);
        return false;
    }
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row && $row['completed'] == 1;
}

function getExecutorsList($db, $task_id){
    $query = "SELECT u.name, vz.user_id, vz.completed 
                FROM veza_izvrsilaczadatak vz 
                JOIN korisnici u ON vz.user_id = u.id 
                WHERE vz.task_id = ?";
    $stmt = $db->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $db->error);
            echo "Greška pri dohvatanju izvršilaca";
            return;
            }
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $executors_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    return $executors_list;
}

function isTaskExecutor($db, $user_id, $task_id) {
    $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM veza_izvrsilaczadatak WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['cnt'] > 0; 
}





?>