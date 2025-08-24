<?php
include 'includes/config.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $db->real_escape_string($_POST['email']);

    // Proveri da li email postoji
    $query = "SELECT id FROM korisnici WHERE email = '$email'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Generiši token za resetovanje
        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Sačuvaj token u bazi
        $query = "INSERT INTO token_resetlozinke (user_id, token, expires_at) VALUES ($user_id, '$token', '$expires_at')";
        if ($db->query($query)) {
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0; 
                $mail->Debugoutput = 'html';
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
                $mail->Subject = 'Resetovanje lozinke';
                $mail->Body = "Kliknite na link za resetovanje lozinke: <a href='" . BASE_URL . "new-password.php?token=$token'>Resetuj lozinku</a>";
                $mail->CharSet = 'UTF-8';
                $mail->send();
                $success = "Link za resetovanje lozinke je poslat na vaš email!";
            } catch (Exception $e) {
                $error = "Greška pri slanju mejla: " . $mail->ErrorInfo;
            }
        } else {
            $error = "Greška pri kreiranju tokena: " . $db->error;
        }
    } else {
        $error = "Email adresa nije pronađena!";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetovanje lozinke</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Resetovanje lozinke</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email adresa</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Pošalji link za resetovanje</button>
        <a href="login.php" class="btn btn-link">Nazad na prijavu</a>
    </form>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>