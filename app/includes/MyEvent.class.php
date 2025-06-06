<?php
include_once 'functions.php';

class MyEvent
{

    private mysqli|false $mysqli;

    public function __construct() {
        $this->mysqli = dbLink();
    }

    /**
     * @throws Exception
     */
    function getById($id): false|array
    {
        $query = $this->mysqli->prepare("SELECT * FROM events WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $row['category'] = getEventCategoryById($row['category_id']);
            unset($row['category_id']);
            return $row;
        }
        return false;
    }

    function create(string $event_date, int $category_id, string $location, int $tickets, string $description, string $title): bool
    {
        $query = $this->mysqli->prepare("INSERT INTO events (event_date, category_id, location, tickets, description, title) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("sisiss", $event_date, $category_id, $location, $tickets, $description, $title);
        return $query->execute();
    }
}