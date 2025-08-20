<?php
class Grupa {
    private $db; 
    private $user_id; 
    private $error; 

    // Konstruktor
    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = (int)$user_id;
    }

    // Kreiranje grupe
    public function kreirajGrupu($group_name) {
        $group_name = $this->db->real_escape_string($group_name);
        $query = "INSERT INTO grupe_zadataka (name, created_by) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $group_name, $this->user_id);

        if (!$stmt->execute()) {
            $this->error = "Greška pri izmeni grupe: " . $this->db->error;
            return false;
        } 
        return true;
    }

    // Brisanje grupe
    public function obrisiGrupu($group_id) {
        $group_id = (int)$group_id;
        $query = "DELETE FROM grupe_zadataka WHERE id = ? AND created_by = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $group_id, $this->user_id);

        if (!$stmt->execute()) {
            $this->error = "Greška pri izmeni grupe: " . $this->db->error;
            return false;
        } 
        return true;
    }

    // Izmena grupe
    public function izmeniGrupu($group_id, $group_name) {
        $group_name = $this->db->real_escape_string($group_name);
        $group_id = (int)$group_id;
        $query = "UPDATE grupe_zadataka SET name = ? WHERE id = ? AND created_by = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sii", $group_name, $group_id, $this->user_id);

        if (!$stmt->execute()) {
            $this->error = "Greška pri izmeni grupe: " . $this->db->error;
            return false;
        } 
        return true;
    }

    // Dohvatanje greške
    public function getError() {
        return $this->error;
    }
}
?>