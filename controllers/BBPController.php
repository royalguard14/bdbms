<?php 
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in'] && isset($_SESSION["user_data"]["role_id"])) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
if ($action == 'delete') {
    if (in_array('My Liquidation', $_SESSION['user_permissions'])) {
        if (isset($data['id'])) {
            try {
                // Fetch the file name before deletion (if a file exists)
                $stmt = $pdo->prepare("SELECT file_name FROM reports WHERE id = :id");
                $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                $stmt->execute();
                $report = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($report) {
                    $filePath = '../assets/uploaded_files/' . $report['file_name'];

                    // Start a transaction to ensure atomicity
                    $pdo->beginTransaction();

                    // Delete connected data in the `liquidations` table
                    $stmt = $pdo->prepare('DELETE FROM liquidations WHERE budget_plan_id = :budget_plan_id');
                    $stmt->bindParam(':budget_plan_id', $data['id'], PDO::PARAM_INT);
                    $stmt->execute();

                    // Delete the report from the `reports` table
                    $stmt = $pdo->prepare('DELETE FROM reports WHERE id = :id');
                    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                    $stmt->execute();

                    // Commit the transaction
                    $pdo->commit();

                    // Delete the file if it exists
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }

                    echo json_encode([
                        'success' => true,
                        'message' => 'Liquidation and report deleted successfully, file removed.',
                    ]);
                } else {
                    echo json_encode(['error' => true, 'message' => 'Report not found.']);
                }
            } catch (Exception $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                echo json_encode([
                    'error' => true,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                ]);
            }
        } else {
            echo json_encode(['error' => true, 'message' => 'Invalid user ID for deletion.']);
        }
    } else {
        header("Location: views/errors/500.html");
        exit();
    }
}

        if ($action == 'get_liquidations') {
            if (isset($data['id'])) {
                try {
            // Fetch all liquidations connected to the budget plan ID
                    $stmt = $pdo->prepare('SELECT * FROM liquidations WHERE budget_plan_id = :budget_plan_id');
                    $stmt->bindParam(':budget_plan_id', $data['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $liquidations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Calculate the total amount spent
                    $totalAmount = 0;
                    foreach ($liquidations as $liquidation) {
                        $totalAmount += $liquidation['amount_spent'];
                    }
                    echo json_encode([
                        'success' => true,
                        'liquidations' => $liquidations,
                        'total_amount' => $totalAmount
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to fetch liquidation data.',
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid budget plan ID.'
                ]);
            }
            exit();
        }
        if ($action === 'delete_liquidation') {
            $liquidationID = $data['liquidation_id'] ?? null;
            if ($liquidationID) {
                try {
                // Fetch the file name before deletion
                    $stmt = $pdo->prepare("SELECT supporting_document FROM liquidations WHERE id = :id");
                    $stmt->bindParam(':id', $liquidationID, PDO::PARAM_INT);
                    $stmt->execute();
                    $liquidation = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($liquidation) {
                        $filePath = '../assets/uploaded_files/' . $liquidation['supporting_document'];
                    // Begin transaction
                        $pdo->beginTransaction();
                    // Delete the liquidation record
                        $stmt = $pdo->prepare("DELETE FROM liquidations WHERE id = :id");
                        $stmt->bindParam(':id', $liquidationID, PDO::PARAM_INT);
                        $stmt->execute();
                    // Commit the transaction
                        $pdo->commit();
                    // Delete the file if it exists
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        echo json_encode(['success' => true, 'message' => 'Liquidation record and file deleted successfully!']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Liquidation record not found.']);
                    }
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    error_log('PDO Exception: ' . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'Failed to delete liquidation record.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid liquidation ID.']);
            }
            exit;
        }
        if ($action === 'get_updated_total') {
    // Get the budget plan ID (it should come from the POST data)
            $budget_plan_id = $data['budget_plan_id'] ?? null;
    // Check if budget_plan_id is valid
            if ($budget_plan_id === null) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Budget plan ID is missing or invalid.'
                ]);
                exit;
            }
    // Prepare and execute the query to get the updated total liquidation value
            $stmt = $pdo->prepare("SELECT SUM(amount_spent) as updated_total FROM liquidations WHERE budget_plan_id = :plan_id");
            $stmt->bindParam(':plan_id', $budget_plan_id, PDO::PARAM_INT);
            $stmt->execute();
    // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // Return the updated total in the response
            echo json_encode([
                'success' => true,
                'updated_total' => $result['updated_total'] ?? 0
            ]);
            exit;
        }
    } else {
        if (in_array('My Liquidation', $_SESSION['user_permissions'])) {
            $stmt = $pdo->prepare('
                SELECT r.*, 
                (SELECT SUM(l.amount_spent) 
                    FROM liquidations l 
                    WHERE l.budget_plan_id = r.id) AS total_liquidation
                FROM reports r
                WHERE r.user_id = :user_id 
                AND r.city_id = :city_id
                AND r.brgy_id = :brgy_id
                AND r.form_type = 2
                ');
            $stmt->bindParam(':user_id', $_SESSION["user_data"]['id']);
            $stmt->bindParam(':city_id', $_SESSION["user_data"]['city_id']);
            $stmt->bindParam(':brgy_id', $_SESSION["user_data"]['brgy_id']);
            $stmt->execute();
            $bybudgetPlan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
} else {
    // Redirect if not logged in or not an admin
    header("Location: views/errors/404.html");
    exit();
}
?>