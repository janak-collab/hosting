<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class Procedure {
    private $db;
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    public function getAll($activeOnly = true) {
        $sql = "SELECT * FROM procedures";
        if ($activeOnly) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY category, name";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT p.*, 
                GROUP_CONCAT(DISTINCT bc.cpt_code) as cpt_codes
                FROM procedures p
                LEFT JOIN billing_codes bc ON p.id = bc.procedure_id
                WHERE p.id = :id
                GROUP BY p.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getBillingCodes($procedureId) {
        $sql = "SELECT * FROM billing_codes WHERE procedure_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $procedureId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getComponents($procedureId) {
        $sql = "SELECT c.*, pcl.display_order 
                FROM procedure_components c
                JOIN procedure_component_links pcl ON c.id = pcl.component_id
                WHERE pcl.procedure_id = :id
                ORDER BY pcl.display_order";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $procedureId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $sql = "INSERT INTO procedures (name, code, template_content, category) 
                VALUES (:name, :code, :template_content, :category)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE procedures SET 
                name = :name,
                template_content = :template_content,
                category = :category,
                active = :active
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function searchByName($searchTerm) {
        $sql = "SELECT * FROM procedures 
                WHERE active = 1 
                AND name LIKE :search 
                ORDER BY category, name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['search' => '%' . $searchTerm . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM procedures 
                WHERE active = 1 
                ORDER BY category";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
