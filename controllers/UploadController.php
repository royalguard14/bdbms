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

    // Check for file upload errors
    if (!isset($_FILES['uploaded_file']) || $_FILES['uploaded_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or an error occurred during the upload.']);
        return;
    }


    // Extract form data
    $file = $_FILES['uploaded_file'];
    $title1 = trim($_POST['title'] ?? '');
    $title2 = trim($_POST['name_extention'] ?? '');
    $title = strtoupper($title2 . ': ' . $title1);
    $amount_budget = $_POST['amount_budget'] ?? null;
    $form_type = (int) ($_POST['form_type'] ?? 0);
    $file_name = basename($file['name']);
    $period_covered = $_POST['period_covered'] ?? date('Y-m-d');
    #$status = ($form_type == 2 || $form_type == 5) ? 'Accepted' : 'Uploaded';
    $status = ($form_type == 2 || $form_type == 5) ? 'Uploaded' : 'Uploaded';
    $user_id = $_SESSION['user_data']['id'] ?? null;
    $city_id = $_SESSION['user_data']['city_id'] ?? null;
    $brgy_id = $_SESSION['user_data']['brgy_id'] ?? 0;
    $amount_spent = $_POST['amount_spent'] ?? 0.00;
    $description = $_POST['description'] ?? '';
    $liquidation_date = $_POST['liquidation_date'] ?? date('Y-m-d');
    $bpid = (int) ($_POST['budget_plan_id'] ?? 0);



    // Define upload directory and file path
    $uploadDir = '../assets/uploaded_files/';
    $uploadFile = $uploadDir . $file_name;

    // Move the uploaded file
    if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
        return;
    }

    try {
        $pdo->beginTransaction();

        if ($form_type == 4) { // Liquidation
         
            $stmt = $pdo->prepare("
                INSERT INTO liquidations (budget_plan_id, amount_spent, description, liquidation_date, supporting_document, created_by) 
                VALUES (:budget_plan_id, :amount_spent, :description, :liquidation_date, :uploaded_file, :user_id)
            ");
            $stmt->execute([
                ':budget_plan_id' => $bpid,
                ':amount_spent' => $amount_spent,
                ':description' => $description,
                ':liquidation_date' => $liquidation_date,
                ':uploaded_file' => $file_name,
                ':user_id' => $user_id,
            ]);
        } else { // Other form types
            $remark = ($form_type == 2 || $form_type == 5) 
                ? json_encode(['amount_request' => $amount_budget, 'actual_amount' => 0.00]) 
                : null;

            $stmt = $pdo->prepare("
                INSERT INTO reports (title, form_type, status, remark, file_name, user_id, city_id, brgy_id) 
                VALUES (:title, :form_type,  :status, :remark, :uploaded_file, :user_id, :city_id, :brgy_id)
            ");
            $stmt->execute([
                ':title' => $title,
                ':form_type' => $form_type,
                
                ':status' => $status,
                ':remark' => $remark,
                ':uploaded_file' => $file_name,
                ':user_id' => $user_id,
                ':city_id' => $city_id,
                ':brgy_id' => $brgy_id,
            ]);




            $report_id = $pdo->lastInsertId();
                $logStmt = $pdo->prepare("
                    INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
                    VALUES (:report_id, NULL, :new_status, :changed_by)
                ");
                $logStmt->bindParam(':report_id', $report_id);
                $logStmt->bindValue(':new_status', $status); 
                $logStmt->bindParam(':changed_by', $user_id);
                $logStmt->execute();
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => ($form_type == 4) ? 'Liquidation record successfully created!' : 'Form uploaded successfully!'
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()]);
    }
}


function handleEdit() {
    if (in_array('Update Report', $_SESSION['user_permissions'])) {
        try {
            global $pdo;
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $form_type = $_POST['form_type'] ?? '';
            $period_covered = $_POST['period_covered'] ?? date('Y-m-d');
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("
                UPDATE reports SET title = :title, form_type = :form_type WHERE id = :id
                ");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title); 
            $stmt->bindParam(':form_type', $form_type);
          
            $stmt->execute();
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'Report updated successfully!']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'An error occurred while updating the report.']);
        }
    }
}

// function handleStatusChange($access, $statuss) {
//     if (in_array($access, $_SESSION['user_permissions'])) {
//         try {
//             global $pdo;
//             $id = $_POST['id'] ?? null;
//             $remark = $_POST['remark'] ?? '';
//             $status = $statuss;
//             $user_id = $_SESSION['user_data']['id'] ?? null;

//             if (!$id) {
//                 echo json_encode(['success' => false, 'message' => 'Report ID is required.']);
//                 return;
//             }

//             $pdo->beginTransaction();

//             // Fetch the current status of the report
//             $currentStatusStmt = $pdo->prepare("SELECT status FROM reports WHERE id = ?");
//             $currentStatusStmt->execute([$id]);
//             $currentStatus = $currentStatusStmt->fetchColumn();

//             if (!$currentStatus) {
//                 throw new Exception('Report not found.');
//             }

//             // Update the report status
//             $stmt = $pdo->prepare("UPDATE reports SET status = :status, remark = :remark WHERE id = :id");
//             $stmt->bindParam(':status', $status);
//             $stmt->bindParam(':remark', $remark);
//             $stmt->bindParam(':id', $id);
//             $stmt->execute();

//             // Log the status change in report_status_logs
//             $logStmt = $pdo->prepare("
//                 INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
//                 VALUES (:report_id, :previous_status, :new_status, :changed_by)
//                 ");
//             $logStmt->bindParam(':report_id', $id);
//             $logStmt->bindParam(':previous_status', $currentStatus);
//             $logStmt->bindParam(':new_status', $status);
//             $logStmt->bindParam(':changed_by', $user_id);
//             $logStmt->execute();

//             $pdo->commit();

//             // Return a success response
//             echo json_encode(['success' => true, 'message' => "Report status changed to '$status' and log updated successfully!"]);
//         } catch (Exception $e) {
//             $pdo->rollBack();
//             echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
//         }
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Permission denied.']);
//     }
// }



function handleStatusChange($access, $statuss) {
    if (in_array($access, $_SESSION['user_permissions'])) {
        try {
            global $pdo;
            $id = $_POST['id'] ?? null;
            $remark = $_POST['remark'] ?? '';
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

            // Check if status is "Reverted" and adjust the SQL query accordingly
            if ($status === 'Reverted') {
                $stmt = $pdo->prepare("UPDATE reports SET status = :status, remark = :remark WHERE id = :id");
                $stmt->bindParam(':remark', $remark);  // Bind remark for "Reverted" status
            } else {
                $stmt = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
            }
            
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

