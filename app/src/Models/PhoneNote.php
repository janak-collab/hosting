<?php
namespace App\Models;

use App\Services\Logger;

use App\Database\Connection;
use PDO;

class PhoneNote {
    private $db;
    private $table = 'phone_notes';
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (
            patient_name, dob, phone, caller_name, location, provider,
            description, last_seen, upcoming_appointment, created_by, status
        ) VALUES (
            :patient_name, :dob, :phone, :caller_name, :location, :provider,
            :description, :last_seen, :upcoming_appointment, :created_by, :status
        )";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            Logger::logError('Error creating phone note: ', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    public function getById($id) {
        $sql = "SELECT pn.*, p.email as provider_email, p.phone as provider_phone
                FROM {$this->table} pn
                LEFT JOIN providers p ON pn.provider = p.name
                WHERE pn.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll($page = 1, $search = '', $filter = 'all') {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT pn.*, p.email as provider_email 
                FROM {$this->table} pn
                LEFT JOIN providers p ON pn.provider = p.name
                WHERE 1=1";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (pn.patient_name LIKE :search OR pn.phone LIKE :search OR pn.description LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        if ($filter !== 'all') {
            $sql .= " AND pn.status = :filter";
            $params['filter'] = $filter;
        }
        
        $sql .= " ORDER BY pn.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($id, $status, $followUpNotes = '') {
        $sql = "UPDATE {$this->table} 
                SET status = :status, 
                    follow_up_notes = :follow_up_notes
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':follow_up_notes' => $followUpNotes
        ]);
    }
    
    public function getActiveProviders() {
        $sql = "SELECT * FROM providers WHERE active = 1 ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLocations() {
        return [
            'Catonsville',
            'Edgewater',
            'Elkridge',
            'Glen Burnie',
            'Leonardtown',
            'Odenton',
            'Prince Frederick'
        ];
    }

    public function getNotes($page = 1, $search = '', $filter = 'all') {
        return $this->getAll($page, $search, $filter);
    }
    
    public function getTotalPages($search = '', $filter = 'all') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        
        $params = [];
        
        if ($search) {
            $sql .= " AND (patient_name LIKE :search OR phone LIKE :search OR description LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        if ($filter !== 'all') {
            $sql .= " AND status = :filter";
            $params['filter'] = $filter;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        return ceil($total / 20);
    }

    /**
     * Get all phone notes created by a specific user
     */
    public function getNotesByUser($username) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE created_by = :username 
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchAll();
    }
}
