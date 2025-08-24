<?php
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $name       = trim($_POST['name']);
    $phone      = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $birth_date = !empty($_POST['birth_date']) ? trim($_POST['birth_date']) : null;
    $role       = $_POST['role'];

    $checkStmt = $db->prepare("SELECT id FROM korisnici WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Korisnik sa ovim username/email već postoji."]);
        exit;
    }
    $checkStmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO korisnici (username, email, password, name, phone, birth_date, role, is_active) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("sssssss", $username, $email, $hashedPassword, $name, $phone, $birth_date, $role);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Korisnik uspešno dodat."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Greška prilikom dodavanja korisnika."]);
    }
    $stmt->close();
}
