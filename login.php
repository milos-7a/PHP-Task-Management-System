<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $db->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT id, username, email, password, role, is_active FROM korisnici 
              WHERE username = '$username' OR email = '$username'";
    $result = $db->query($query);

    if ($result === false) {
        $error = "Greška u SQL upitu: " . $db->error;
    } elseif ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['is_active'] == 0) {
            $error = "Nalog nije aktiviran! Proverite email za aktivacioni link.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Pogrešna lozinka!";
        }
    } else {
        $error = "Korisnik ne postoji!";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Prijava</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Korisničko ime ili email</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Lozinka</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Prijavi se</button>
        <a href="reset-password.php" class="btn btn-link">Zaboravili ste lozinku?</a>
        <a href="register.php" class="btn btn-link">Nemate nalog? Registrujte se</a>
    </form>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>