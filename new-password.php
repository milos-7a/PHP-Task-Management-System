<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $db->real_escape_string($_POST['token']);
    $password = $_POST['password'];

    // Proveri token
    $query = "SELECT user_id, expires_at FROM token_resetlozinke WHERE token = '$token'";
    $result = $db->query($query);
    echo $token;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (strtotime($row['expires_at']) > time()) {
            $user_id = $row['user_id'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE korisnici SET password = '$hashed_password' WHERE id = $user_id";
            if ($db->query($query)) {
                $db->query("DELETE FROM token_reset_lozinke WHERE token = '$token'");
                $success = "Lozinka uspešno promenjena! Možete se prijaviti.";
            } else {
                $error = "Greška pri promeni lozinke: " . $db->error;
            }
        } else {
            $error = "Token je istekao! Zatražite novi link.";
        }
    } else {
        $error = "Nevažeći token!";
    }
} elseif (isset($_GET['token'])) {
    $token = $db->real_escape_string($_GET['token']);
} else {
    $error = "Nije prosleđen token!";
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova lozinka</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Nova lozinka</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <a href="login.php" class="btn btn-primary">Prijavi se</a>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Nova lozinka</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Promeni lozinku</button>
        </form>
    <?php endif; ?>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>