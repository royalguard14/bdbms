<?php
require_once 'connect.php';
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    // Decode incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    // Check if specific action is provided
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
        if ($action === 'addOrUpdateBudget' && isset($data['barangayId'], $data['year'], $data['budget'])) {
    // Add or update barangay budget
            $barangay_id = (int)$data['barangayId'];
            $year = (int)$data['year'];
            $allocated_budget = (float)$data['budget'];
    // Check if the user has the required permission
            if (in_array('Manage Budget', $_SESSION['user_permissions'])) {
                try {
            // First, check if a record with the same year and barangay_id exists
                    $stmt = $pdo->prepare("SELECT * FROM barangay_budget WHERE barangay_id = :barangay_id AND year = :year");
                    $stmt->bindParam(':barangay_id', $barangay_id, PDO::PARAM_INT);
                    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                    $stmt->execute();
                    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($existingRecord) {
                // Record exists, perform an update
                        $stmt = $pdo->prepare("
                            UPDATE barangay_budget 
                            SET allocated_budget = :allocated_budget, updated_at = NOW() 
                            WHERE barangay_id = :barangay_id AND year = :year
                            ");
                        $stmt->bindParam(':barangay_id', $barangay_id, PDO::PARAM_INT);
                        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                        $stmt->bindParam(':allocated_budget', $allocated_budget, PDO::PARAM_STR);
                        if ($stmt->execute()) {
                            echo json_encode(['success' => true, 'message' => 'Budget successfully updated.']);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Failed to update the budget.']);
                        }
                    } else {
                // No record exists, perform an insert
                        $stmt = $pdo->prepare("
                            INSERT INTO barangay_budget (barangay_id, year, allocated_budget, created_at, updated_at)
                            VALUES (:barangay_id, :year, :allocated_budget, NOW(), NOW())
                            ");
                        $stmt->bindParam(':barangay_id', $barangay_id, PDO::PARAM_INT);
                        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                        $stmt->bindParam(':allocated_budget', $allocated_budget, PDO::PARAM_STR);
                        if ($stmt->execute()) {
                            echo json_encode(['success' => true, 'message' => 'Budget successfully added.']);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Failed to add the budget.']);
                        }
                    }
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
            }
        } elseif ($action === 'fetchBudgets') {
            // Fetch all budgets for a specific year
            if (isset($data['year'])) {
                $year = (int)$data['year'];
                // Check if the user has the required permission
                if (in_array('Manage Budget', $_SESSION['user_permissions'])) {
                    try {
                        $stmt = $pdo->prepare("
                            SELECT b.name AS barangay_name, bb.year, bb.allocated_budget
                            FROM barangay_budget bb
                            JOIN barangay b ON bb.barangay_id = b.id
                            WHERE bb.year = :year
                            ");
                        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                        $stmt->execute();
                        $budgetsBrgy = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo json_encode(['success' => true, 'data' => $budgetsBrgy]);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => false, 'message' => 'Failed to fetch budgets.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Unauthorized action.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Year is required.']);
            }
        }elseif ($action === 'checkyear') {
            $year = $data['year'];
            // Query to fetch barangays without budget for the given year
            $stmt = $pdo->prepare("
                SELECT barangay.id, barangay.name
                FROM barangay
                LEFT JOIN barangay_budget bb ON barangay.id = bb.barangay_id AND bb.year = :year
                WHERE bb.barangay_id IS NULL
                ");
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            // Fetch barangays without budget
            $barangaysWithoutBudget = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Return the result as a JSON response
            echo json_encode(['success' => true, 'data' =>  $barangaysWithoutBudget]);
        }
        elseif ($action == 'delete') {
          if (in_array('Manage Budget', $_SESSION['user_permissions'])) {
            if (isset($data['id'])) {
              try {
                $stmt = $pdo->prepare('DELETE FROM barangay_budget WHERE id = :id');
                $stmt->bindParam(':id', $data['id']);
                $stmt->execute();
                echo json_encode([
                  'success' => true,
                  'message' => "Delete successful!"
              ]);
            } catch (Exception $e) {
                echo json_encode(['error' => true, 'message' => 'An error occurred.']);
            }
        } else {
          echo json_encode(['error' => true, 'message' => 'Invalid ID for deletion.']);
      }
  } else {
    header("Location: views/errors/500.html");
    exit();
}
}
else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
} 
else {
        // Default action: Fetch all budgets for 2024
    if (in_array('Manage Budget', $_SESSION['user_permissions'])) {
        try {
            $stmt = $pdo->prepare("
                SELECT b.name AS barangay_name, bb.year, bb.allocated_budget, bb.barangay_id,bb.id
                FROM barangay_budget bb
                JOIN barangay b ON bb.barangay_id = b.id
                ");
            $stmt->execute();
            $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch budgets.']);
        }
    } else {
        header("Location: views/errors/500.html");
        exit();
    }
}
} else {
    header("Location: views/errors/404.html");
    exit();
}
?>