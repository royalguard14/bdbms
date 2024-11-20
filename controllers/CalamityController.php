<?php
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];


                if ($action == 'delete') {
            if (in_array('My Liquidation', $_SESSION['user_permissions'])) {
                if (isset($data['id'])) {
                    try {
               
                        $pdo->beginTransaction();
       
                   
                        $stmt = $pdo->prepare('DELETE FROM reports WHERE id = :id');
                        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
                        $stmt->execute();
                // Commit the transaction
                        $pdo->commit();
                        echo json_encode([
                            'success' => true,
                        ]);
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

    }else {



        // Default action: Fetch all barangays if no specific action is passed
        if (in_array('My Liquidation', $_SESSION['user_permissions'])) {
            try {

             $stmt = $pdo->prepare('
                SELECT * FROM reports 
                WHERE brgy_id = :brgy_id
                AND form_type = 5
                AND status = :status
                AND YEAR(period_covered) = YEAR(CURDATE())
                ');
             $stmt->bindParam(':brgy_id', $_SESSION["user_data"]['brgy_id']);
             $stmt->bindValue(':status', "Accepted"); 
             $stmt->execute();

// Fetch all matching reports
             $calamityReport = $stmt->fetchAll(PDO::FETCH_ASSOC);
         } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch barangays.']);
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