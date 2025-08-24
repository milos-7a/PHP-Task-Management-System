<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['file_path'])) {
    echo json_encode(['success' => false, 'message' => 'Neispravan zahtev']);
    exit;
}

$file_path = $_POST['file_path'];

$stmt = $db->prepare("SELECT id FROM prilozi_zadataka WHERE file_path = ? LIMIT 1");
$stmt->bind_param("s", $file_path);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Fajl ne postoji u bazi']);
    exit;
}
$attachment = $result->fetch_assoc();

$stmtDel = $db->prepare("DELETE FROM prilozi_zadataka WHERE id = ?");
$stmtDel->bind_param("i", $attachment['id']);
if (!$stmtDel->execute()) {
    echo json_encode(['success' => false, 'message' => 'Greška pri brisanju iz baze']);
    exit;
}

if (file_exists($file_path)) {
    unlink($file_path);
}

echo json_encode(['success' => true, 'message' => 'Fajl je obrisan']);
