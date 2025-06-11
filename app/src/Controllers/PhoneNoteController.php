<?php
namespace App\Controllers;

use App\Models\PhoneNote;

class PhoneNoteController extends BaseController
{
    private $phoneNoteModel;

    public function __construct()
    {
        $this->phoneNoteModel = new PhoneNote();
    }

    public function showForm()
    {
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $providers = $this->phoneNoteModel->getActiveProviders();
        $locations = $this->phoneNoteModel->getLocations();
        $user = $this->getUserInfo();
        $csrf_token = $_SESSION['csrf_token'];

        // Load the view
        require_once __DIR__ . '/../../templates/views/phone-note/form.php';
    }

    public function submit()
    {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Verify CSRF token
        if (!$this->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid security token']);
            return;
        }

        // Validate input
        $validation = $this->validatePhoneNote($_POST);
        if (!$validation['valid']) {
            $this->jsonResponse(['success' => false, 'errors' => $validation['errors']]);
            return;
        }

        // Save to database
        $noteId = $this->phoneNoteModel->create([
            'patient_name' => $_POST['pname'],
            'dob' => $_POST['dob'],
            'phone' => preg_replace('/\D/', '', $_POST['phone']),
            'caller_name' => $_POST['caller_name'] ?? null,
            'location' => $_POST['location'],
            'provider' => $_POST['provider'],
            'description' => $_POST['description'],
            'last_seen' => $_POST['last_seen'],
            'upcoming_appointment' => $_POST['upcoming'],
            'created_by' => $_SERVER['REMOTE_USER'] ?? 'Unknown',
            'status' => 'new'
        ]);

        if ($noteId) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Phone note saved successfully',
                'id' => $noteId
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to save phone note']);
        }
    }

    public function listNotes()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $filter = $_GET['filter'] ?? 'all';

        $notes = $this->phoneNoteModel->getNotes($page, $search, $filter);
        $totalPages = $this->phoneNoteModel->getTotalPages($search, $filter);
        $currentPage = $page;

        // Load the view
        require_once __DIR__ . '/../../templates/views/phone-note/list.php';
    }

    public function viewNote($id)
    {
        $note = $this->phoneNoteModel->getById($id);
        if (!$note) {
            header("Location: //admin/phone-notes");
            exit;
        }

        // Load the view
        require_once __DIR__ . '/../../templates/views/phone-note/view.php';
    }

    public function printNote($id)
    {
        $note = $this->phoneNoteModel->getById($id);
        if (!$note) {
            die('Phone note not found');
        }

        // Load the view
        require_once __DIR__ . '/../../templates/views/phone-note/print.php';
    }

    public function updateStatus($id)
    {
        header('Content-Type: application/json');
        
        $status = $_POST['status'] ?? '';
        $followUpNotes = $_POST['follow_up_notes'] ?? '';

        $updated = $this->phoneNoteModel->updateStatus($id, $status, $followUpNotes);

        $this->jsonResponse(['success' => $updated]);
    }

    private function validatePhoneNote($data)
    {
        $errors = [];
        $required = ['pname', 'dob', 'phone', 'location', 'provider', 'description', 'last_seen', 'upcoming'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }

        // Validate phone number
        if (!empty($data['phone'])) {
            $phone = preg_replace('/\D/', '', $data['phone']);
            if (strlen($phone) !== 10) {
                $errors['phone'] = 'Phone number must be 10 digits';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    private function getUserInfo()
    {
        $user = $_SERVER['REMOTE_USER'] ?? 'Unknown User';
        $first_initial = ucfirst(substr($user, 0, 1));
        $last_name = ucfirst(substr($user, 1));
        return $first_initial . '. ' . $last_name;
    }
    
    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    private function verifyCSRFToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
