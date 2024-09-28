<?php
header('Content-Type: application/json');
require_once 'connect.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_data'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'upload':
            handleUpload();
            break;
        case 'updateUpload':
            handleEdit(); 
            break;
        case 'delete':
            handleStatusChange('toArchived', 'Archived');
            break;
        case 'submited':
            handleStatusChange('ToSubmit', 'Submitted');
            break;
        case 'toVerify':
            handleStatusChange('toVerified', 'Verified');
            break;
        case 'toRevert':
            handleStatusChange('toRevert', 'Reverted');
            break;
        case 'toConfirm':
            handleStatusChange('ToConfirm', 'Confirm');
            break;
        case 'toAccepted':
            handleStatusChange('toAccept', 'Accepted');
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
    }
}

function handleUpload() {
    global $pdo;

    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['uploaded_file'];
        $title = $_POST['title'] ?? '';
        $form_type = $_POST['form_type'] ?? '';
        $file_name = $file['name'];
        $period_covered = $_POST['period_covered'] ?? '';
        $status = 'Uploaded'; 
        $user_id = $_SESSION['user_data']['id'] ?? null;
        $city_id = $_SESSION['user_data']['city_id'] ?? null;
        $brgy_id = $_SESSION['user_data']['brgy_id'] ?? 0;
        $uploadDir = '../assets/uploaded_files/';
        $uploadFile = $uploadDir . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("
                    INSERT INTO reports (title, form_type, file_name, period_covered, status, user_id, city_id, brgy_id) 
                    VALUES (:title, :form_type, :file_name, :period_covered, :status, :user_id, :city_id, :brgy_id)
                ");
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':form_type', $form_type);
                $stmt->bindParam(':file_name', $file_name);
                $stmt->bindParam(':period_covered', $period_covered);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':city_id', $city_id);
                $stmt->bindParam(':brgy_id', $brgy_id);
                $stmt->execute();

                $report_id = $pdo->lastInsertId();
                $logStmt = $pdo->prepare("
                    INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
                    VALUES (:report_id, NULL, :new_status, :changed_by)
                ");
                $logStmt->bindParam(':report_id', $report_id);
                $logStmt->bindValue(':new_status', $status); 
                $logStmt->bindParam(':changed_by', $user_id);
                $logStmt->execute();
                $pdo->commit();

                echo json_encode(['success' => true, 'message' => 'Form uploaded, report created, and status log added successfully!']);
            } catch (PDOException $e) {
                $pdo->rollBack();
                if ($e->getCode() == 23000) { 
                    echo json_encode(['success' => false, 'message' => 'Report already exists. Please choose another title or file.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'An error occurred while creating the report or logging the status change.']);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or an error occurred during the upload.']);
    }
}

function handleEdit() {
    if (in_array('Update Report', $_SESSION['user_permissions'])) {
        try {
            global $pdo;
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $form_type = $_POST['form_type'] ?? '';
            $period_covered = $_POST['period_covered'] ?? '';
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("
                UPDATE reports SET title = :title, form_type = :form_type, period_covered = :period_covered WHERE id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title); 
            $stmt->bindParam(':form_type', $form_type);
            $stmt->bindParam(':period_covered', $period_covered);
            $stmt->execute();
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Report updated successfully!']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'An error occurred while updating the report.']);
        }
    }
}

function handleStatusChange($access, $statuss) {
    if (in_array($access, $_SESSION['user_permissions'])) {
        try {
            global $pdo;
            $id = $_POST['id'] ?? null;
            $status = $statuss;
            $user_id = $_SESSION['user_data']['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Report ID is required.']);
                return;
            }

            $pdo->beginTransaction();

            // Fetch the current status of the report
            $currentStatusStmt = $pdo->prepare("SELECT status FROM reports WHERE id = ?");
            $currentStatusStmt->execute([$id]);
            $currentStatus = $currentStatusStmt->fetchColumn();

            if (!$currentStatus) {
                throw new Exception('Report not found.');
            }

            // Update the report status
            $stmt = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Log the status change in report_status_logs
            $logStmt = $pdo->prepare("
                INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
                VALUES (:report_id, :previous_status, :new_status, :changed_by)
            ");
            $logStmt->bindParam(':report_id', $id);
            $logStmt->bindParam(':previous_status', $currentStatus);
            $logStmt->bindParam(':new_status', $status);
            $logStmt->bindParam(':changed_by', $user_id);
            $logStmt->execute();

            $pdo->commit();

            // Return a success response
            echo json_encode(['success' => true, 'message' => "Report status changed to '$status' and log updated successfully!"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Permission denied.']);
    }
}
