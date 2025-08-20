<?php
include 'includes/config.php';
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $db->real_escape_string($_POST['username']);
    $email = $db->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $name = $db->real_escape_string($_POST['name']);
    $phone = !empty($_POST['phone']) ? $db->real_escape_string($_POST['phone']) : null;
    $birth_date = !empty($_POST['birth_date']) ? $db->real_escape_string($_POST['birth_date']) : null;

    // Validacija
    if ($password !== $password_confirm) {
        $error = "Lozinke se ne podudaraju!";
    } else {
        // Proveri jedinstvenost korisničkog imena i emaila
        $query = "SELECT id FROM korisnici WHERE username = '$username' OR email = '$email'";
        $result = $db->query($query);
        if ($result->num_rows > 0) {
            $error = "Korisničko ime ili email već postoje!";
        } else {
            // Hash lozinke
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Unos korisnika 
            $query = "INSERT INTO korisnici (username, email, password, name, phone, birth_date, role, is_active)
                      VALUES ('$username', '$email', '$password_hash', '$name', '$phone', '$birth_date', 'executor', 0)";
            if ($db->query($query)) {
                $user_id = $db->insert_id;

                // Generiši aktivacioni token
                $token = bin2hex(random_bytes(32));
                $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                $query = "INSERT INTO token_aktivacije (user_id, token, expires_at) VALUES ($user_id, '$token', '$expires_at')";
                if ($db->query($query)) {
                    // Pošalji mejl
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = SMTP_HOST;
                        $mail->SMTPAuth = true;
                        $mail->Username = SMTP_USER;
                        $mail->Password = SMTP_PASS;
                        $mail->SMTPSecure = SMTP_SECURE;
                        $mail->Port = SMTP_PORT;
                        $mail->setFrom(FROM_EMAIL, FROM_NAME);
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = 'Aktivacija naloga';
                        $mail->Body = "Kliknite na link za aktivaciju: <a href='" . BASE_URL . "activate.php?token=$token'>Aktiviraj</a>";
                        $mail->CharSet = 'UTF-8';

                        $mail->send();
                        $success = "Registracija uspešna! Proverite email za aktivacioni link.";
                    } catch (Exception $e) {
                        $error = "Greška pri slanju mejla: " . $mail->ErrorInfo;
                    }
                } else {
                    $error = "Greška pri kreiranju tokena: " . $db->error;
                }
            } else {
                $error = "Greška pri registraciji: " . $db->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Registracija</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Korisničko ime</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Lozinka</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Ponovite lozinku</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Ime i prezime</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Broj telefona (opciono)</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="mb-3">
            <label for="birth_date" class="form-label">Datum rođenja (opciono)</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date">
        </div>
        <button type="submit" class="btn btn-primary">Registruj se</button>
        <a href="login.php" class="btn btn-link">Već imate nalog? Prijavite se</a>
    </form>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>