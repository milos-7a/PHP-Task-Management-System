<?php
class Zadatak {
    private $db; 
    private $user_id; 
    private $error; 

    // Konstruktor
    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = (int)$user_id;
    }

    // Kreiranje zadatka
    public function kreirajZadatak() {
        $title = $this->db->real_escape_string($_POST['title']);
        $description = $this->db->real_escape_string($_POST['description']);
        $group_id = (int)$_POST['group_id'];
        $deadline = $_POST['deadline'];
        $priority = (int)$_POST['priority'];
        $executors = isset($_POST['executors']) ? $_POST['executors'] : [];

        $query = "INSERT INTO zadaci (title, description, group_id, manager_id, deadline, priority, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, 'open', NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssiisi", $title, $description, $group_id, $this->user_id, $deadline, $priority);
        if ($stmt->execute()) {
            $task_id = $this->db->insert_id;
            foreach ($executors as $executor_id) {
                $query = "INSERT INTO veza_izvrsilaczadatak (task_id, user_id, completed) VALUES (?, ?, 0)";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("ii", $task_id, $executor_id);
                $stmt->execute();
            }
            // Obrada priloga
            if (!empty($_FILES['prilozi']['name'][0])) {
                foreach ($_FILES['prilozi']['name'] as $i => $name) {
                    if ($_FILES['prilozi']['error'][$i] == 0) {
                        $file_path = 'uploads/' . basename($name);
                        move_uploaded_file($_FILES['prilozi']['tmp_name'][$i], $file_path);
                        $query = "INSERT INTO prilozi_zadataka (task_id, file_path, uploaded_by, created_at) VALUES (?, ?, ?, NOW())";
                        $stmt = $this->db->prepare($query);
                        $stmt->bind_param("isi", $task_id, $file_path, $this->user_id);
                        $stmt->execute();
                    }
                }
            }
        } else {
            $error = "Greška pri kreiranju zadatka: " . $this->db->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        return true;
    }
    // Zavrsi zadatak
    public function zavrsiZadatak(){
        $task_id = (int)$_POST['task_id'];
        $query = "UPDATE zadaci SET status = 'completed' WHERE id = ? AND manager_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $task_id, $this->user_id);

        if (!$stmt->execute()) {
            $error = "Greška pri otkazivanju zadatka: " . $this->db->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        return true;
    }

    // Oznaci kao zavrsen 
    public function oznaciKaoZavrsen(){
        $task_id = (int)$_POST['task_id'];
        $query = "UPDATE veza_izvrsilaczadatak SET completed = 1 WHERE user_id = ? AND task_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->user_id, $task_id);
        if (!$stmt->execute()) {
            $error = "Greška pri otkazivanju zadatka: " . $this->db->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        return true;
    }

    // Otkazi zadatak
    public function otkaziZadatak(){
        $task_id = (int)$_POST['task_id'];
        $query = "UPDATE zadaci SET status = 'canceled' WHERE id = ? AND manager_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $task_id, $this->user_id);

        if (!$stmt->execute()) {
            $error = "Greška pri otkazivanju zadatka: " . $this->db->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        return true;
    }

    // Obrisi zadatak
        public function obrisiZadatak(){
        $task_id = (int)$_POST['task_id'];
        $query = "DELETE FROM zadaci WHERE id = ? AND manager_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $task_id, $this->user_id);

        if (!$stmt->execute()) {
            $error = "Greška pri otkazivanju zadatka: " . $this->db->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        return true;
    }

    // Dohvatanje greške
    public function getError() {
        return $this->error;
    }
}
?>