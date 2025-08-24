<?php
class TaskFilter {
    private $db;
    private $user_id;
    private $filters = [];
    private $sort = 'deadline';

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
        $this->loadFromRequest();
    }

    private function loadFromRequest() {
        $this->filters['deadline'] = isset($_GET['deadline']) ? $_GET['deadline'] : '';
        $this->filters['manager_id'] = isset($_GET['manager_id']) ? (int)$_GET['manager_id'] : 0;
        $this->filters['user_id'] = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'deadline';
        $allowedSorts = ['deadline', 'title', 'priority'];
        $this->sort = in_array($sort, $allowedSorts) ? $sort : 'deadline';
    }

    public function getTasks() {
        $query = "SELECT t.id, t.title, t.description, t.group_id, t.deadline, t.status, t.priority,
                        k.name AS manager_name,
                        g.name AS group_name,
                        GROUP_CONCAT(vz.user_id) AS executors,
                        GROUP_CONCAT(vz.completed) AS completed_statuses
                FROM zadaci t
                LEFT JOIN veza_izvrsilaczadatak vz ON t.id = vz.task_id
                LEFT JOIN korisnici k ON t.manager_id = k.id
                LEFT JOIN grupe_zadataka g ON t.group_id = g.id
                WHERE 1=1";

        $params = [];
        $types = "";

        // Filtriranje po deadline
        if (!empty($this->filters['deadline'])) {
            $query .= " AND t.deadline = ?";
            $params[] = $this->filters['deadline'];
            $types .= "s";
        }

        // Filtriranje po menadžeru
        if ($this->filters['manager_id']) {
            $query .= " AND t.manager_id = ?";
            $params[] = $this->filters['manager_id'];
            $types .= "i";
        }

        // Filtriranje po izvršiocu (samo ako je filter izabran u interfejsu)
        if ($this->filters['user_id']) {
            $query .= " AND t.id IN (SELECT task_id FROM veza_izvrsilaczadatak WHERE user_id = ?)";
            $params[] = $this->filters['user_id'];
            $types .= "i";
        }

        $query .= " GROUP BY t.id ORDER BY t." . $this->sort;

        $stmt = $this->db->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getManagers() {
        $query = "SELECT id, name FROM korisnici WHERE role = 'manager'";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getExecutors() {
        $query = "SELECT id, name FROM korisnici WHERE role = 'executor'";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }
    public function getUsers() {
        $query = "SELECT * FROM korisnici ORDER BY role, name";
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }
    public function getFilters() {
    return $this->filters;
    }

    public function getSort() {
        return $this->sort;
    }

}
