<?php
include 'includes/config.php';
if (isset($_GET['token'])) {
    $token = $db->real_escape_string($_GET['token']);
    $query = "SELECT user_id, expires_at FROM token_aktivacije WHERE token = '$token'";
    $result = $db->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (strtotime($row['expires_at']) > time()) {
            $user_id = $row['user_id'];
            $db->query("UPDATE korisnici SET is_active = 1 WHERE id = $user_id");
            $db->query("DELETE FROM token_aktivacije WHERE token = '$token'");
            $message = "Nalog uspešno aktiviran! Možete se prijaviti.";
        } else {
            $error = "Token je istekao! Molimo zatražite novi aktivacioni link.";
        }
    } else {
        $error = "Nevažeći token!";
    }
} else {
    $error = "Nije prosleđen token!";
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Aktivacija naloga</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Aktivacija naloga</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <a href="login.php" class="btn btn-primary">Prijavi se</a>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <a href="register.php" class="btn btn-link">Vratite se na registraciju</a>
    <?php endif; ?>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>