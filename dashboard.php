<?php
require_once 'includes/config.php';
include 'includes/functions.php';
include 'classes/TaskFilter.php';
include 'classes/Grupa.php';
include 'classes/Zadatak.php';

$role = $_SESSION['role'];
require_role($role);
//error_log($role);
$user_id = $_SESSION['user_id'];
$user = getUserById($db, $user_id);

// Uloge
$roles = ['admin', 'manager', 'executor'];

// Grupe
$grupe = new Grupa($db, $user_id);
$groups = $grupe->getGroups();
// Kreiranje, izmena i brisanje grupe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kreiraj_grupu']) && isset($_POST['group_name'])) {
    $grupe->kreirajGrupu($_POST['group_name']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['izmeni_grupu']) && isset($_POST['group_name']))  {
    $grupe->izmeniGrupu((int)$_POST['group_id'], $_POST['group_name']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['obrisi_grupu']) && isset($_POST['group_id'])) {
    $grupe->obrisiGrupu((int)$_POST['group_id']);
}

// Zadaci
$zadaci = new Zadatak($db, $user_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kreiraj_zadatak'])) {
    $zadaci->kreirajZadatak();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zavrsi_zadatak'])) {
    error_log("User role: $role, metoda pozvana: " . ($role !== 'executor' ? 'zavrsiZadatak' : 'oznaciKaoZavrsen'));
    if ($role !== 'executor'){
      $zadaci->zavrsiZadatak();
    }
    else{
        $zadaci->oznaciKaoZavrsen();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otkazi_zadatak'])) {
    $zadaci->otkaziZadatak();
}

// Ucitavanje zadataka i filtera
$taskFilter = new TaskFilter($db, $user_id);
$tasks = $taskFilter->getTasks();
$managers = $taskFilter->getManagers();
$users = $taskFilter->getExecutors();
$filters = $taskFilter->getFilters();
$sort = $taskFilter->getSort();
$executors = $taskFilter->getExecutors();
$filter_deadline = $filters['deadline'];
$filter_manager  = $filters['manager_id'];
$filter_user     = $filters['user_id'];

// Dohvati komentare i priloge
$comments = getComments($db, $tasks);
$attachments = getAttachments($db, $tasks);

// Korisnici
$korisnici = $taskFilter->getUsers();

include 'dashboard_view.php';
?>
