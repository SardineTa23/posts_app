<?php
require_once 'model.php';
class Tag extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM tags ORDER BY  id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
