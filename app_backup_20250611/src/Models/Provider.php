<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class Provider {
    private $db;
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    public function getActive() {
        $sql = "SELECT p.*, u.username,
                GROUP_CONCAT(DISTINCT pl.location) as locations
                FROM providers p
                JOIN users u ON p.user_id = u.id
                LEFT JOIN provider_locations pl ON p.id = pl.provider_id
                WHERE p.active = 1 AND u.active = 1
                GROUP BY p.id
                ORDER BY p.full_name";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM providers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByUserId($userId) {
        $sql = "SELECT * FROM providers WHERE user_id = :user_id AND active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getProcedures($providerId) {
        $sql = "SELECT p.*, pp.custom_intro, pp.custom_closing, pp.is_favorite,
                GROUP_CONCAT(DISTINCT bc.cpt_code) as cpt_codes
                FROM procedures p
                LEFT JOIN provider_procedures pp 
                    ON p.id = pp.procedure_id AND pp.provider_id = :provider_id
                LEFT JOIN billing_codes bc ON p.id = bc.procedure_id
                WHERE p.active = 1 
                AND (pp.can_perform = 1 OR pp.can_perform IS NULL)
                GROUP BY p.id
                ORDER BY pp.is_favorite DESC, p.category, p.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['provider_id' => $providerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $this->db->beginTransaction();
        
        try {
            // Create user first if username provided
            if (isset($data['username']) && isset($data['password'])) {
                $userSql = "INSERT INTO users (username, password_hash, role, active) 
                            VALUES (:username, :password_hash, 'provider', 1)";
                $stmt = $this->db->prepare($userSql);
                $stmt->execute([
                    'username' => $data['username'],
                    'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT)
                ]);
                $userId = $this->db->lastInsertId();
            } else {
                $userId = $data['user_id'];
            }
            
            // Create provider
            $providerSql = "INSERT INTO providers 
                           (user_id, full_name, title, npi_number, license_number) 
                           VALUES (:user_id, :full_name, :title, :npi_number, :license_number)";
            $stmt = $this->db->prepare($providerSql);
            $stmt->execute([
                'user_id' => $userId,
                'full_name' => $data['full_name'],
                'title' => $data['title'],
                'npi_number' => $data['npi_number'] ?? null,
                'license_number' => $data['license_number'] ?? null
            ]);
            $providerId = $this->db->lastInsertId();
            
            // Add locations
            if (!empty($data['locations'])) {
                $locationSql = "INSERT INTO provider_locations (provider_id, location, is_primary) 
                               VALUES (:provider_id, :location, :is_primary)";
                $stmt = $this->db->prepare($locationSql);
                
                foreach ($data['locations'] as $index => $location) {
                    $stmt->execute([
                        'provider_id' => $providerId,
                        'location' => $location,
                        'is_primary' => $index === 0 ? 1 : 0
                    ]);
                }
            }
            
            $this->db->commit();
            return $providerId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        $sql = "UPDATE providers SET 
                full_name = :full_name,
                title = :title,
                npi_number = :npi_number,
                license_number = :license_number
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'full_name' => $data['full_name'],
            'title' => $data['title'],
            'npi_number' => $data['npi_number'] ?? null,
            'license_number' => $data['license_number'] ?? null
        ]);
    }
    
    public function deactivate($id) {
        $sql = "UPDATE providers p 
                JOIN users u ON p.user_id = u.id 
                SET p.active = 0, u.active = 0 
                WHERE p.id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    public function setFavoriteProcedure($providerId, $procedureId, $isFavorite = true) {
        $sql = "INSERT INTO provider_procedures (provider_id, procedure_id, is_favorite) 
                VALUES (:provider_id, :procedure_id, :is_favorite)
                ON DUPLICATE KEY UPDATE is_favorite = :is_favorite";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'provider_id' => $providerId,
            'procedure_id' => $procedureId,
            'is_favorite' => $isFavorite ? 1 : 0
        ]);
    }
    
    public function getLocations($providerId) {
        $sql = "SELECT location, is_primary 
                FROM provider_locations 
                WHERE provider_id = :provider_id 
                ORDER BY is_primary DESC, location";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['provider_id' => $providerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
