<?php
require_once 'connect.php'; // Assuming this connects to the database
header('Content-Type: application/json'); // Set JSON header
// Ensure the session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
if (!isset($_SESSION['user_data'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}
// Check permissions for the action
if (!in_array('Upload Report', $_SESSION['user_permissions'])) {
    echo json_encode(['success' => false, 'message' => 'Permission denied.']);
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
        handleDelete(); 
        break;

        case 'submited':
        handleSubmit();
        break;

       
        default:
        echo json_encode(['success' => false, 'message' => $action]);
    }
}
function handleUpload() {
    global $pdo; // Ensure you can access $pdo
    // Check if the uploaded file exists
    if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['uploaded_file'];
        // Collect form data
        $title = $_POST['title'] ?? '';
        $form_type = $_POST['form_type'] ?? '';
        $file_name = $file['name'];
        $period_covered = $_POST['period_covered'] ?? '';
        $status = "Uploaded"; // Initial status is 'Uploaded'
        $user_id = $_SESSION["user_data"]["id"] ?? null;
        $city_id = $_SESSION["user_data"]["city_id"] ?? null;
        $brgy_id = $_SESSION["user_data"]["brgy_id"] ?? 0; // Default to 0 if not set
        // File upload directory
        $uploadDir = '../assets/uploaded_files/';
        $uploadFile = $uploadDir . basename($file['name']);
        // Check if the file was uploaded successfully
        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            try {
                // Start a transaction
                $pdo->beginTransaction();
                // Prepare the SQL INSERT statement to insert into the reports table
                $stmt = $pdo->prepare("
                    INSERT INTO reports (title, form_type, file_name, period_covered, status, user_id, city_id, brgy_id) 
                    VALUES (:title, :form_type, :file_name, :period_covered, :status, :user_id, :city_id, :brgy_id)
                    ");
                // Bind parameters
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':form_type', $form_type);
                $stmt->bindParam(':file_name', $file_name);
                $stmt->bindParam(':period_covered', $period_covered);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':city_id', $city_id);
                $stmt->bindParam(':brgy_id', $brgy_id);
                // Execute the statement
                $stmt->execute();
                // Get the last inserted report_id
                $report_id = $pdo->lastInsertId();
                // Log the status change in the report_status_logs table
                $logStmt = $pdo->prepare("
                    INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
                    VALUES (:report_id, NULL, :new_status, :changed_by)
                    ");
                $logStmt->bindParam(':report_id', $report_id);
                $logStmt->bindValue(':new_status', $status); // New status is 'Uploaded'
                $logStmt->bindParam(':changed_by', $user_id);
                $logStmt->execute();
                // Commit the transaction
                $pdo->commit();
                // Return success message
                echo json_encode([
                    'success' => true,
                    'message' => "Form uploaded, report created, and status log added successfully!"
                ]);
            } catch (PDOException $e) {
                // Rollback the transaction on failure
                $pdo->rollBack();
                if ($e->getCode() == 23000) { // Handle duplicate entry error
                    echo json_encode(['success' => false, 'message' => 'Report already exists. Please choose another title or file.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'An error occurred while creating the report or logging the status change.']);
                }
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to upload file.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No file uploaded or an error occurred during the upload.'
        ]);
    }
}
function handleEdit() {
    global $pdo; // Ensure you can access $pdo
    // Collect form data
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $form_type = $_POST['form_type'] ?? '';
    $period_covered = $_POST['period_covered'] ?? '';
    try {
        // Start a transaction
        $pdo->beginTransaction();
        // Prepare the SQL UPDATE statement to update the reports table
        $stmt = $pdo->prepare("
            UPDATE reports SET title = :title, form_type = :form_type, period_covered = :period_covered WHERE id = :id
            ");
        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title); // Added binding for title
        $stmt->bindParam(':form_type', $form_type);
        $stmt->bindParam(':period_covered', $period_covered);
        // Execute the statement
        $stmt->execute();
        // Commit the transaction
        $pdo->commit();
        // Return success message
        echo json_encode([
            'success' => true,
            'message' => "Report updated successfully!" // Adjusted message
        ]);
    } catch (PDOException $e) {
        // Rollback the transaction on failure
        $pdo->rollBack();
        if ($e->getCode() == 23000) { // Handle duplicate entry error
            echo json_encode(['success' => false, 'message' => 'Report already exists. Please choose another title or file.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'An error occurred while updating the report.']);
        }
    }
}



function handleDelete() {
    global $pdo;
    $id = $_POST['id'] ?? null;
    $status = "Archived";
    $user_id = $_SESSION["user_data"]["id"] ?? null;

    if (!$id) {
        sendResponse(false, 'Report ID is required.');
        return;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Fetch current status for logging
        $currentStatusStmt = $pdo->prepare("SELECT status FROM reports WHERE id = ?");
        $currentStatusStmt->execute([$id]);
        $currentStatus = $currentStatusStmt->fetchColumn();

        if (!$currentStatus) {
            throw new Exception('Report not found.');
        }

        // Update the report status to 'Archived'
        $stmt = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Insert the status change into report_status_logs
        $logStmt = $pdo->prepare("
            INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
            VALUES (:report_id, :previous_status, :new_status, :changed_by)
        ");
        $logStmt->bindParam(':report_id', $id);
        $logStmt->bindParam(':previous_status', $currentStatus); // Use the current status from the database
        $logStmt->bindParam(':new_status', $status); // New status is 'Archived'
        $logStmt->bindParam(':changed_by', $user_id);
        $logStmt->execute();

        // Commit transaction
        $pdo->commit();

        // Return success message
        echo json_encode([
            'success' => true,
            'message' => "Report deleted, and status log added successfully!"
        ]);

    } catch (Exception $e) {
        // Rollback transaction on failure
        $pdo->rollBack();

        // Return error message
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
}




function handleSubmit() {
    global $pdo;
    $id = $_POST['id'] ?? null;
    $status = "Submitted";
    $user_id = $_SESSION["user_data"]["id"] ?? null;

    if (!$id) {
        sendResponse(false, 'Report ID is required.');
        return;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Fetch current status for logging
        $currentStatusStmt = $pdo->prepare("SELECT status FROM reports WHERE id = ?");
        $currentStatusStmt->execute([$id]);
        $currentStatus = $currentStatusStmt->fetchColumn();

        if (!$currentStatus) {
            throw new Exception('Report not found.');
        }

        // Update the report status to 'Submitted'
        $stmt = $pdo->prepare("UPDATE reports SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Insert the status change into report_status_logs
        $logStmt = $pdo->prepare("
            INSERT INTO report_status_logs (report_id, previous_status, new_status, changed_by) 
            VALUES (:report_id, :previous_status, :new_status, :changed_by)
        ");
        $logStmt->bindParam(':report_id', $id);
        $logStmt->bindParam(':previous_status', $currentStatus); // Use the current status from the database
        $logStmt->bindParam(':new_status', $status); // New status is 'Submitted'
        $logStmt->bindParam(':changed_by', $user_id);
        $logStmt->execute();

        // Commit transaction
        $pdo->commit();

        // Return success message
        echo json_encode([
            'success' => true,
            'message' => "Report submitted, and status log added successfully!"
        ]);

    } catch (Exception $e) {
        // Rollback transaction on failure
        $pdo->rollBack();

        // Return error message
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
}

?>