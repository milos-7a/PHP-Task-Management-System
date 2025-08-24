<?php
// Pokretanje sesije
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Postavke za konekciju na MySQL bazu podataka
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); 
define('DB_PASS', ''); 
define('DB_NAME', 'ppp2'); 

// Konekcija na bazu
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_error) {
    die("Konekcija na bazu nije uspela: " . $db->connect_error);
}

$db->set_charset('utf8mb4');

// Postavke za PHPMailer 
define('SMTP_HOST', 'sandbox.smtp.mailtrap.io');
define('SMTP_USER', '58b16d1f493061');
define('SMTP_PASS', '6838930a72dee5');
define('SMTP_PORT', 25);
define('SMTP_SECURE', 'tls');
define('FROM_EMAIL', 'noreply@ppp2app.com'); 
define('FROM_NAME', 'PPP2 Aplikacija');

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