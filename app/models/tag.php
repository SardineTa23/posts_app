<?php
class Tag
{
    function __construct($db)
    {
        $this->db = $db;
    }

    function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM tags ORDER BY  id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
