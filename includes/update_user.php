<?php
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Niste ovlašćeni da izvršite ovu akciju.";
    exit;
}

$id = (int)$_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$phone = !empty($_POST['phone']) ? $_POST['phone'] : null;
$birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
$role = $_POST['role'];

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE korisnici SET username=?, email=?, password=?, name=?, phone=?, birth_date=?, role=? WHERE id=?");
    $stmt->bind_param("sssssssi", $username, $email, $hashed_password, $name, $phone, $birth_date, $role, $id);
} else {
    $stmt = $db->prepare("UPDATE korisnici SET username=?, email=?, name=?, phone=?, birth_date=?, role=? WHERE id=?");
    $stmt->bind_param("ssssssi", $username, $email, $name, $phone, $birth_date, $role, $id);
}

if ($stmt->execute()) {
    echo "Korisnik je uspešno izmenjen.";
} else {
    http_response_code(500);
    echo "Greška pri izmeni korisnika: " . $stmt->error;
}
