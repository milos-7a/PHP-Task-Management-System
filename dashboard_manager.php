<?php
include 'includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

function refresh() {
    header('Location:/ppp2/dashboard_manager.php');
}

// Dohvati podatke o korisniku
$query = "SELECT username, name, email, role FROM korisnici WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $error = "Greška pri dohvatanju podataka. ID: $user_id, SQL greška: " . $db->error;
    session_destroy();
    header("Location: login.php");
    exit;
}

// Obrada grupa zadataka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kreiraj_grupu'])) {
    $group_name = $db->real_escape_string($_POST['group_name']);
    $query = "INSERT INTO grupe_zadataka (name, created_by) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $group_name, $user_id);
    refresh();
    if (!$stmt->execute()) {
        $error = "Greška pri kreiranju grupe: " . $db->error;
    }
}

//Brisanje grupe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['obrisi_grupu'])) {
    $group_id = (int)$_POST['group_id'];
    $query = "DELETE FROM grupe_zadataka WHERE id = ? AND created_by = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $group_id, $user_id);
    refresh();
    if (!$stmt->execute()) {
        $error = "Greška pri brisanju grupe: " . $db->error;
    }
}

//Izmena grupe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['izmeni_grupu'])) {
    $group_name = $db->real_escape_string($_POST['group_name']);
    $group_id = (int)$_POST['group_id'];
    $query = "UPDATE grupe_zadataka SET name = ? WHERE id = ? AND created_by = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sii", $group_name, $group_id, $user_id);
    refresh();
    if (!$stmt->execute()) {
        $error = "Greška pri brisanju grupe: " . $db->error;
    }
}

// Obrada zadataka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kreiraj_zadatak'])) {
    $title = $db->real_escape_string($_POST['title']);
    $description = $db->real_escape_string($_POST['description']);
    $group_id = (int)$_POST['group_id'];
    $deadline = $_POST['deadline'];
    $priority = (int)$_POST['priority'];
    $executors = isset($_POST['executors']) ? $_POST['executors'] : [];

    $query = "INSERT INTO zadaci (title, description, group_id, manager_id, deadline, priority, status, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, 'open', NOW())";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssiisi", $title, $description, $group_id, $user_id, $deadline, $priority);
    if ($stmt->execute()) {
        $task_id = $db->insert_id;
        foreach ($executors as $executor_id) {
            $query = "INSERT INTO veza_izvrsilaczadatak (task_id, user_id, completed) VALUES (?, ?, 0)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ii", $task_id, $executor_id);
            $stmt->execute();
        }
        // Obrada priloga
        if (!empty($_FILES['prilozi']['name'][0])) {
            foreach ($_FILES['prilozi']['name'] as $i => $name) {
                if ($_FILES['prilozi']['error'][$i] == 0) {
                    $file_path = 'uploads/' . basename($name);
                    move_uploaded_file($_FILES['prilozi']['tmp_name'][$i], $file_path);
                    $query = "INSERT INTO prilozi_zadataka (task_id, file_path, uploaded_by, created_at) VALUES (?, ?, ?, NOW())";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("isi", $task_id, $file_path, $user_id);
                    $stmt->execute();
                }
            }
        }
        refresh();
    } else {
        $error = "Greška pri kreiranju zadatka: " . $db->error;
    }
}

// Označavanje zadatka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zavrsi_zadatak'])) {
    $task_id = (int)$_POST['task_id'];
    $query = "UPDATE zadaci SET status = 'completed' WHERE id = ? AND manager_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $task_id, $user_id);
    refresh();
    if (!$stmt->execute()) {
        $error = "Greška pri označavanju završetka: " . $db->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otkazi_zadatak'])) {
    $task_id = (int)$_POST['task_id'];
    $query = "UPDATE zadaci SET status = 'canceled' WHERE id = ? AND manager_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $task_id, $user_id);
    if (!$stmt->execute()) {
        $error = "Greška pri otkazivanju zadatka: " . $db->error;
    }
    refresh();
}

// Filtriranje
$filter_deadline_od = isset($_GET['deadline_od']) ? $_GET['deadline_od'] : '';
$filter_deadline_do = isset($_GET['deadline_do']) ? $_GET['deadline_do'] : '';
$filter_priority = isset($_GET['priority']) ? (int)$_GET['priority'] : 0;
$filter_executor = isset($_GET['executor_id']) ? (int)$_GET['executor_id'] : 0;
$filter_title = isset($_GET['title']) ? $db->real_escape_string($_GET['title']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'deadline';
$sort = in_array($sort, ['deadline', 'title', 'priority']) ? $sort : 'deadline';

$query = "SELECT t.id, t.title, t.description, t.deadline, t.priority, t.status, g.name AS group_name 
          FROM zadaci t 
          LEFT JOIN grupe_zadataka g ON t.group_id = g.id 
          WHERE t.manager_id = ?";
$params = [$user_id];
if ($filter_deadline_od) {
    $query .= " AND t.deadline >= ?";
    $params[] = $filter_deadline_od;
}
if ($filter_deadline_do) {
    $query .= " AND t.deadline <= ?";
    $params[] = $filter_deadline_do;
}
if ($filter_priority) {
    $query .= " AND t.priority = ?";
    $params[] = $filter_priority;
}
if ($filter_executor) {
    $query .= " AND t.id IN (SELECT task_id FROM veza_izvrsilaczadatak WHERE user_id = ?)";
    $params[] = $filter_executor;
}
if ($filter_title) {
    $query .= " AND t.title LIKE ?";
    $params[] = "%$filter_title%";
}
$query .= " ORDER BY t.$sort";

$stmt = $db->prepare($query);
$types = str_repeat("i", count($params)-1) . ($filter_deadline_od || $filter_deadline_do ? "s" : "i");
if ($filter_title) $types .= "s";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Dohvati grupe, izvršioce i komentare
$groups_query = "SELECT id, name FROM grupe_zadataka WHERE created_by = ?";
$stmt = $db->prepare($groups_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$groups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$executors_query = "SELECT id, name FROM korisnici WHERE role = 'executor'";
$executors = $db->query($executors_query)->fetch_all(MYSQLI_ASSOC);

$comments = [];
$attachments = [];
foreach ($tasks as $task) {
    $query = "SELECT k.id, k.comment, k.created_at, u.name 
              FROM komentari_zadatka k 
              JOIN korisnici u ON k.user_id = u.id 
              WHERE k.task_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $task['id']);
    $stmt->execute();
    $comments[$task['id']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $query = "SELECT id, file_path FROM prilozi_zadataka WHERE task_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $task['id']);
    $stmt->execute();
    $attachments[$task['id']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

include 'dashboard_manager_view.php';
?>
