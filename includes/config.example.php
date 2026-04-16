<?php
// Pokretanje sesije
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Postavke za konekciju na MySQL bazu podataka
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'ppp2');

// Konekcija na bazu
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die("Konekcija na bazu nije uspela: " . $db->connect_error);
}

$db->set_charset('utf8mb4');

// Postavke za PHPMailer
define('SMTP_HOST', 'your_smtp_host');
define('SMTP_USER', 'your_smtp_username');
define('SMTP_PASS', 'your_smtp_password');
define('SMTP_PORT', 587); // or 25, 465, etc.
define('SMTP_SECURE', 'tls'); // or 'ssl'
define('FROM_EMAIL', 'noreply@yourdomain.com');
define('FROM_NAME', 'Your App Name');

// Uključi PHPMailer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Osnovne postavke aplikacije
define('BASE_URL', 'http://localhost/ppp2/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Provera direktorijuma za upload
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

?>