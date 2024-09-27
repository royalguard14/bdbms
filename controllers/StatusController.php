<?php
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
    } else {
        // Default action: Fetch all barangays if no specific action is passed
        function fetchReportsByStatus($status, $pdo) {
    // SQL query to get reports with the latest status from report_status_logs
            $stmt = $pdo->prepare("
                SELECT r.*, rsl.new_status, rsl.changed_at, 
                COALESCE(p.first_name, 'Joe') AS first_name, 
                COALESCE(p.last_name, 'Smith') AS last_name,
                b.name AS barangay_name
                FROM reports r
                JOIN (
                    SELECT report_id, MAX(changed_at) AS latest_change
                    FROM report_status_logs
                    GROUP BY report_id
                    ) latest_status ON r.id = latest_status.report_id
                JOIN report_status_logs rsl ON r.id = rsl.report_id AND rsl.changed_at = latest_status.latest_change
                LEFT JOIN profiles p ON rsl.changed_by = p.user_id
                LEFT JOIN barangay b ON r.brgy_id = b.id
                WHERE rsl.new_status = :status 
                AND r.user_id = :user_id 
                AND r.city_id = :city_id
                AND r.brgy_id = :brgy_id
                ");
    // Bind parameters
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':user_id', $_SESSION["user_data"]['id']);
            $stmt->bindParam(':city_id', $_SESSION["user_data"]['city_id']);
            $stmt->bindParam(':brgy_id', $_SESSION["user_data"]['brgy_id']);
    // Execute the query and return the result
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
// Initialize arrays for each report category
        $myuploads = $mysubmitted = $myaccepted = $myreverted = $myarchived = [];
        if (in_array('Upload Report', $_SESSION['user_permissions'])) {
            try {
                $myuploads = fetchReportsByStatus("Uploaded", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch uploaded reports.']);
            }
        }
        if (in_array('View Submitted', $_SESSION['user_permissions'])) {
            try {
                $mysubmitted = fetchReportsByStatus("Submitted", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch submitted reports.']);
            }
        }
        if (in_array('View Accepted', $_SESSION['user_permissions'])) {
            try {
                $myaccepted = fetchReportsByStatus("Accepted", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch accepted reports.']);
            }
        }
        if (in_array('View Reverted', $_SESSION['user_permissions'])) {
            try {
                $myreverted = fetchReportsByStatus("Reverted", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch reverted reports.']);
            }
        }
        if (in_array('View Archived', $_SESSION['user_permissions'])) {
            try {
                $myarchived = fetchReportsByStatus("Archived", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch archived reports.']);
            }
        }
            // else {
            //     header("Location: views/errors/500.html");
            //     exit();
            // }
    } 
} else {
    header("Location: views/errors/404.html");
    exit();
}
?>