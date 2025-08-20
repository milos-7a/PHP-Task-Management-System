<?php
include 'includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'executor') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

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

// Obrada komentara
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_komentar'])) {
    $task_id = (int)$_POST['task_id'];
    $comment = $db->real_escape_string($_POST['comment']);
    $query = "INSERT INTO komentari_zadatka (task_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iis", $task_id, $user_id, $comment);
    if (!$stmt->execute()) {
        $error = "Greška pri dodavanju komentara: " . $db->error;
    }
}

// Označavanje završetka zadatka
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zavrsi_zadatak'])) {
    $task_id = (int)$_POST['task_id'];
    $query = "UPDATE veza_izvrsilaczadatak SET completed = 1 WHERE task_id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $task_id, $user_id);
    if (!$stmt->execute()) {
        $error = "Greška pri označavanju završetka: " . $db->error;
    }
}

// Filtriranje i sortiranje
$filter_deadline = isset($_GET['deadline']) ? $_GET['deadline'] : '';
$filter_manager = isset($_GET['manager_id']) ? (int)$_GET['manager_id'] : 0;
$filter_user = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'deadline';
$sort = in_array($sort, ['deadline', 'title', 'priority']) ? $sort : 'deadline';

$query = "SELECT t.id, t.title, t.description, t.deadline, t.status, t.priority, k.name AS manager_name, vz.completed 
          FROM zadaci t 
          JOIN veza_izvrsilaczadatak vz ON t.id = vz.task_id 
          LEFT JOIN korisnici k ON t.manager_id = k.id 
          WHERE vz.user_id = ?";
$params = [$user_id];
if ($filter_deadline) {
    $query .= " AND t.deadline = ?";
    $params[] = $filter_deadline;
}
if ($filter_manager) {
    $query .= " AND t.manager_id = ?";
    $params[] = $filter_manager;
}
if ($filter_user) {
    $query .= " AND t.id IN (SELECT task_id FROM veza_izvrsilaczadatak WHERE user_id = ?)";
    $params[] = $filter_user;
}
$query .= " ORDER BY t.$sort";

$stmt = $db->prepare($query);
$types = str_repeat("i", count($params)-1) . ($filter_deadline ? "s" : "i");
$stmt->bind_param($types, ...$params);
$stmt->execute();
$tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Dohvati rukovodioce i članove za filtere
$managers_query = "SELECT id, name FROM korisnici WHERE role = 'manager'";
$managers = $db->query($managers_query)->fetch_all(MYSQLI_ASSOC);
$users_query = "SELECT id, name FROM korisnici WHERE role = 'executor'";
$users = $db->query($users_query)->fetch_all(MYSQLI_ASSOC);

// Dohvati komentare
$comments = [];
foreach ($tasks as $task) {
    $query = "SELECT k.comment, k.created_at, u.name 
              FROM komentari_zadatka k 
              JOIN korisnici u ON k.user_id = u.id 
              WHERE k.task_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $task['id']);
    $stmt->execute();
    $comments[$task['id']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
include 'dashboard_view.php';
?>
