<?php


$sets = ["Gello"];

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
                JOIN report_status_logs rsl ON r.id = rsl.report_id 
                AND rsl.changed_at = latest_status.latest_change
                LEFT JOIN profiles p ON rsl.changed_by = p.user_id
                LEFT JOIN barangay b ON r.brgy_id = b.id
        WHERE rsl.new_status = :status -- This ensures the latest status matches the input status (Submitted)
          AND rsl.new_status = r.status -- Ensures the latest status in logs matches the status in reports
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



                function fetchReportsByStatus1($status, $pdo) {
            $stmt = $pdo->prepare("
                SELECT r.*, rsl.new_status, rsl.changed_at, 
                COALESCE(p.first_name, 'Joe') AS first_name, 
                COALESCE(p.last_name, 'Smith') AS last_name,
                b.name AS barangay_name,
                r.status
                FROM reports r
                JOIN (
                    SELECT report_id, MAX(changed_at) AS latest_change
                    FROM report_status_logs
                    GROUP BY report_id
                    ) latest_status ON r.id = latest_status.report_id
                JOIN report_status_logs rsl ON r.id = rsl.report_id 
                AND rsl.changed_at = latest_status.latest_change
                LEFT JOIN profiles p ON rsl.changed_by = p.user_id
                LEFT JOIN barangay b ON r.brgy_id = b.id
        WHERE rsl.new_status NOT IN ('Archived','Accepted','Uploaded') -- This ensures the latest status matches the input status (Submitted)
          AND rsl.new_status = r.status -- Ensures the latest status in logs matches the status in reports
          AND r.user_id = :user_id 
          AND r.city_id = :city_id
          AND r.brgy_id = :brgy_id
          
          ");
    // Bind parameters
         
            $stmt->bindParam(':user_id', $_SESSION["user_data"]['id']);
            $stmt->bindParam(':city_id', $_SESSION["user_data"]['city_id']);
            $stmt->bindParam(':brgy_id', $_SESSION["user_data"]['brgy_id']);
    // Execute the query and return the result
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }



        function fetchReportsByStatusAssistant($status, $pdo) {
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
                WHERE r.status = :status 
                AND r.city_id = :city_id
                
                ");
    // Bind parameters
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':city_id', $_SESSION["user_data"]['city_id']);
    // Execute the query and return the result
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }



        
function fetchReportsByStatusAssistant2($status, $pdo) {
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
        WHERE r.city_id = :city_id
        AND r.form_type = 2
        AND rsl.new_status = :status
    ");

    // Bind parameters
    $stmt->bindParam(':city_id', $_SESSION["user_data"]['city_id']);
    $stmt->bindParam(':status', $status);

    // Execute the query and return the result
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




        $myuploads = $mysubmitted = $myaccepted = $myreverted = $myarchived = [];
        if (in_array('Upload Report', $_SESSION['user_permissions'])) {
            try {
                $myuploads = fetchReportsByStatus("Uploaded", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch uploaded reports.']);
            }
        }
        if (in_array('toVerified', $_SESSION['user_permissions'])) {
            try {
                $myverify = fetchReportsByStatusAssistant("Verified", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch uploaded reports.']);
            }
        }
        if (in_array('Manage Budget', $_SESSION['user_permissions'])) {
            try {
                $bugetPlans = fetchReportsByStatusAssistant2("Accepted", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch uploaded reports.']);
            }
        }
        if (in_array('Read Confirm', $_SESSION['user_permissions'])) {
            try {
                $myconfirm = fetchReportsByStatusAssistant("Confirm", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch uploaded reports.']);
            }
        }
// Role-based report fetching
        if (in_array('View Submitted', $_SESSION['user_permissions'])) {
            try {
                if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" ) {
                    $mysubmitted = fetchReportsByStatusAssistant("Submitted", $pdo);
                } else {
                    $mysubmitted = fetchReportsByStatus("Submitted", $pdo);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch submitted reports.']);
            }
        }
        if (in_array('View Accepted', $_SESSION['user_permissions'])) {
            try {
                if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" ) {
                    $myaccepted = fetchReportsByStatusAssistant("Accepted", $pdo);
                } else {
                    $myaccepted = fetchReportsByStatus("Accepted", $pdo);
                }
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


                if (in_array('Upload Report', $_SESSION['user_permissions'])) {
            try {
                $myfilesroute = fetchReportsByStatus1("Accepted", $pdo);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch reverted reports.']);
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