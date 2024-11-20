<?php
require_once 'connect.php';

class LiquidationController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function storeLiquidation()
    {
        if (in_array('Create Liquidation', $_SESSION['user_permissions'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['title']) && !empty($data['title']) && isset($data['file_name']) && !empty($data['file_name']) && isset($data['period_covered']) && !empty($data['period_covered']) && isset($data['budget_plan_id'])) {
                $title = $data['title'];
                $fileName = $data['file_name'];
                $periodCovered = $data['period_covered'];
                $budgetPlanId = $data['budget_plan_id'];
                $barangayId = $_SESSION['user_data']['barangay_id'];
                $changedBy = $_SESSION['user_data']['user_id'];

                try {
                    $stmt = $this->db->prepare("INSERT INTO liquidations (title, file_name, period_covered, budget_plan_id, barangay_id, changed_by, date_uploaded) 
                                                VALUES (:title, :file_name, :period_covered, :budget_plan_id, :barangay_id, :changed_by, NOW())");
                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':file_name', $fileName);
                    $stmt->bindParam(':period_covered', $periodCovered);
                    $stmt->bindParam(':budget_plan_id', $budgetPlanId);
                    $stmt->bindParam(':barangay_id', $barangayId);
                    $stmt->bindParam(':changed_by', $changedBy);
                    $stmt->execute();
                    echo json_encode(['success' => true, 'message' => 'Liquidation record successfully created!']);
                } catch (PDOException $e) {
                    echo json_encode(['error' => true, 'message' => 'An error occurred during creation.', 'error_details' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'All fields are required.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }

public function fetchBudgetPlans()
{
    if (in_array('My Liquidation', $_SESSION['user_permissions'])) {
        try {
            $cityId = $_SESSION['user_data']['city_id']; 
            $brgyId = $_SESSION['user_data']['brgy_id']; 
            
            $stmt = $this->db->prepare("SELECT * FROM reports WHERE status = 'Accepted' AND form_type = 2 AND city_id = :city AND brgy_id = :brgy");
            $stmt->bindParam(':city', $cityId, PDO::PARAM_INT);
            $stmt->bindParam(':brgy', $brgyId, PDO::PARAM_INT);
            $stmt->execute();
            
            $budgetPlans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'budgetPlans' => $budgetPlans]);
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to fetch budget plans.', 
                'error_details' => $e->getMessage()
            ]);
        }
    } else {
        header("Location: views/errors/500.html");
        exit();
    }
}


    public function fetchLiquidationRecords()
    {
        if (in_array('Read Liquidation', $_SESSION['user_permissions'])) {
            try {
                $stmt = $this->db->prepare('SELECT l.id, l.title, l.file_name, l.period_covered, l.date_uploaded, l.changed_at, 
                                            u.first_name, u.last_name, br.barangay_name 
                                            FROM liquidations l 
                                            INNER JOIN users u ON l.changed_by = u.user_id 
                                            INNER JOIN barangays br ON l.barangay_id = br.barangay_id 
                                            ORDER BY l.id ASC');
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'records' => $records]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch records.', 'error_details' => $e->getMessage()]);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
}

// Instantiate and handle the request
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    $controller = new LiquidationController($pdo);

    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];

        if ($action === 'store') {
            $controller->storeLiquidation();
        } elseif ($action === 'fetch_budget_plans') {
            $controller->fetchBudgetPlans();
        } elseif ($action === 'fetch') {
            $controller->fetchLiquidationRecords();
        } else {
            echo json_encode(['error' => true, 'message' => 'Invalid action.']);
        }
    } else {
        echo json_encode(['error' => true, 'message' => 'No action specified.']);
    }
} else {
    header("Location: views/errors/404.html");
    exit();
}
