<?php
require_once 'connect.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Total users (global for admin)
function getTotalUsers($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users where is_deleted = 0");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Total barangays, either for a specific city or global
function getTotalBarangays($pdo, $city_id = null) {
    if ($city_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM barangay WHERE city_id = :city_id");
        $stmt->bindParam(':city_id', $city_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } else {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM barangay");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}

// Total reports submitted this month (global for admin)
function getTotalReportsForMonth($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reports WHERE MONTH(date_uploaded) = MONTH(CURDATE()) AND YEAR(date_uploaded) = YEAR(CURDATE())");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Pending reports this month (global for admin)
function getPendingReports($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reports WHERE status = 'Pending' AND MONTH(date_uploaded) = MONTH(CURDATE())");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Barangays that have not submitted reports this month (global for admin)
function getNotSubmittedBarangays($pdo) {
    $stmt = $pdo->query("SELECT COUNT(DISTINCT id) as total FROM barangay WHERE id NOT IN (SELECT brgy_id FROM reports WHERE MONTH(date_uploaded) = MONTH(CURDATE()))");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// Total reports for the year (global for admin)
function getTotalReportsForYear($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reports WHERE YEAR(date_uploaded) = YEAR(CURDATE())");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// User-specific queries for BRGY USER (for individual user dashboard)
function getUserTotalReportsForMonth($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS total_reports 
        FROM reports 
        WHERE user_id = :user_id 
        AND MONTH(date_uploaded) = MONTH(CURDATE()) 
        AND YEAR(date_uploaded) = YEAR(CURDATE())
    ");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getUserPendingReportsForMonth($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS total_pending 
        FROM reports 
        WHERE user_id = :user_id 
        AND status = 'Pending' 
        AND MONTH(date_uploaded) = MONTH(CURDATE()) 
        AND YEAR(date_uploaded) = YEAR(CURDATE())
    ");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchColumn();
}



function getOurBudgetForThisYear($pdo, $brgyId) {
    $stmt = $pdo->prepare("
        SELECT allocated_budget FROM barangay_budget
        WHERE barangay_id = :barangay_id 
        AND YEAR(year) = YEAR(CURDATE())
    ");

    $stmt->bindParam(':barangay_id', $brgyId);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function getTotalamountspentofmybrgy($pdo, $brgyId) {
    $stmt = $pdo->prepare("
        SELECT 
            SUM(l.amount_spent) AS total_spent
        FROM 
            liquidations l
        JOIN 
            reports r ON l.budget_plan_id = r.id
        WHERE 
            r.brgy_id = :brgy_id
            AND r.form_type = 2
            AND YEAR(l.liquidation_date) = YEAR(CURDATE())
                    AND status = 'Accepted'
    ");

    $stmt->bindParam(':brgy_id', $brgyId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn(); // Returns the total_spent value directly
}



function getTotalamountspentofmybrgyqrf($pdo, $brgyId) {
$stmt = $pdo->prepare("
        SELECT SUM(CAST(JSON_EXTRACT(remark, '$.amount_request') AS DECIMAL(10,2))) AS total_amount_request
        FROM reports
        WHERE 
          form_type = 5
          AND brgy_id = :brgy_id
          AND YEAR(period_covered) = YEAR(CURDATE())
                  AND status = 'Accepted'
    ");

    $stmt->bindParam(':brgy_id', $brgyId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn(); // Returns the total_spent value directly
}






function getUserTotalReportsForYear($pdo ) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS total_reports 
        FROM reports 
        WHERE user_id = :user_id 
        AND YEAR(date_uploaded) = YEAR(CURDATE())
    ");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Fetch recent activities
function fetchRecentActivities($pdo) {
    $stmt = $pdo->prepare("
        SELECT r.title, rsl.new_status, rsl.changed_at, 
               p.first_name, p.last_name
        FROM report_status_logs rsl
        JOIN reports r ON r.id = rsl.report_id
        LEFT JOIN profiles p ON rsl.changed_by = p.user_id
        ORDER BY rsl.changed_at DESC
        LIMIT 5
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch notifications
function fetchNotifications($pdo) {
    $stmt = $pdo->prepare("
        SELECT r.title, r.status, r.date_uploaded 
        FROM reports r 
        WHERE r.status IN ('Submitted', 'Reverted', 'Accepted') 
        ORDER BY r.date_uploaded DESC
        LIMIT 5
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




function getTotalSpentByUserBarangayGroupedByDate($pdo, $brgy_id) {
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(l.liquidation_date, '%M %Y') AS formatted_date,
            SUM(l.amount_spent) AS total_spent
        FROM 
            liquidations l
        JOIN 
            reports r ON l.budget_plan_id = r.id
        WHERE 
            r.brgy_id = :brgy_id
            AND r.form_type = 2
            AND YEAR(l.liquidation_date) = YEAR(CURDATE())
        GROUP BY 
            DATE_FORMAT(l.liquidation_date, '%M %Y')
        ORDER BY 
            l.liquidation_date ASC
    ");
    $stmt->bindParam(':brgy_id', $brgy_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getCalamityFundsUsage($pdo, $brgy_id) {
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(period_covered, '%M %Y') AS formatted_date,
            JSON_EXTRACT(remark, '$.amount_request') AS amount_request,
            r.title
        FROM 
            reports r
        WHERE 
            brgy_id = :brgy_id
            AND form_type = 5
        ORDER BY 
            period_covered ASC
    ");
    $stmt->bindParam(':brgy_id', $brgy_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}












function getAllBudgetForThisYear($pdo) {
    $stmt = $pdo->prepare("
        SELECT Sum(allocated_budget) FROM barangay_budget
        WHERE YEAR(year) = YEAR(CURDATE())
    ");
    $stmt->execute();
    return $stmt->fetchColumn();
}


function getBarangayBudgetDetails($pdo) {
    $stmt = $pdo->prepare("
  SELECT
    b.name AS barangay_name,
    b.id as barangay_id,
    
    -- Total allocated budget for the barangay
    IFNULL(SUM(bb.allocated_budget), 0) AS total_budget,
    
    -- Total spent from liquidations tied to budget plans (form_type = 2)
    (
        SELECT IFNULL(SUM(l.amount_spent), 0)
        FROM liquidations l
        JOIN reports r ON r.id = l.budget_plan_id
        WHERE r.form_type = 2 AND r.brgy_id = b.id
    ) AS total_budget_plan_spent,
    
    -- Total spent on calamity reports (reports.form_type = 5)
    (
        SELECT IFNULL(SUM(CAST(JSON_EXTRACT(r2.remark, '$.amount_request') AS DECIMAL(10,2))), 0)
        FROM reports r2
        WHERE r2.form_type = 5 AND r2.brgy_id = b.id
    ) AS total_calamity_report_spent
    
FROM
    barangay b
LEFT JOIN
    barangay_budget bb ON b.id = bb.barangay_id AND YEAR(bb.year) = YEAR(CURDATE())
GROUP BY
    b.name;

    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getUnusedBudgetFromPastYears($pdo, $brgyId) {
    $stmt = $pdo->prepare("
        SELECT 
            IFNULL(SUM(b.allocated_budget), 0) - IFNULL(SUM(l.amount_spent), 0) AS unused_budget
        FROM 
            barangay_budget b
        LEFT JOIN 
            reports r ON r.brgy_id = b.barangay_id
        LEFT JOIN 
            liquidations l ON l.budget_plan_id = r.id
        WHERE 
            b.barangay_id = :brgy_id
            AND YEAR(b.year) < YEAR(CURDATE()) -- Include only past years
            AND (r.status = 'Accepted' OR r.status IS NULL) -- Consider only accepted reports or no liquidations
    ");

    $stmt->bindParam(':brgy_id', $brgyId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn(); // Returns the remaining unused budget from past years
}

function getTotalAvailableBudget($pdo, $brgyId) {
    // Get last year's unused budget
    $stmtLastYear = $pdo->prepare("
        SELECT 
            IFNULL(SUM(allocated_budget), 0) AS last_year_budget 
        FROM 
            barangay_budget
        WHERE 
            barangay_id = :barangay_id 
            AND YEAR(year) = YEAR(CURDATE()) - 1
    ");
    $stmtLastYear->bindParam(':barangay_id', $brgyId, PDO::PARAM_INT);
    $stmtLastYear->execute();
    $lastYearBudget = $stmtLastYear->fetchColumn();

    // Get this year's allocated budget
    $stmtThisYear = $pdo->prepare("
        SELECT 
            IFNULL(allocated_budget, 0) AS this_year_budget 
        FROM 
            barangay_budget
        WHERE 
            barangay_id = :barangay_id 
            AND YEAR(year) = YEAR(CURDATE())
    ");
    $stmtThisYear->bindParam(':barangay_id', $brgyId, PDO::PARAM_INT);
    $stmtThisYear->execute();
    $thisYearBudget = $stmtThisYear->fetchColumn();

    // Get total expenses for this year
    $stmtSpentThisYear = $pdo->prepare("
        SELECT 
            IFNULL(SUM(l.amount_spent), 0) AS total_spent
        FROM 
            liquidations l
        JOIN 
            reports r ON l.budget_plan_id = r.id
        WHERE 
            r.brgy_id = :brgy_id
            AND YEAR(l.liquidation_date) = YEAR(CURDATE())
            AND r.form_type = 2
            AND r.status = 'Accepted'
    ");
    $stmtSpentThisYear->bindParam(':brgy_id', $brgyId, PDO::PARAM_INT);
    $stmtSpentThisYear->execute();
    $spentThisYear = $stmtSpentThisYear->fetchColumn();

    // Calculate remaining budget
    $remainingThisYear = $thisYearBudget - $spentThisYear;

    // Total available budget = remaining this year's budget + unused last year's budget
    $totalAvailableBudget = $remainingThisYear + $lastYearBudget;

    return [
        'last_year_unused_budget' => number_format($lastYearBudget, 2),
        'this_year_allocated_budget' => number_format($thisYearBudget, 2),
        'current_year_remaining' => number_format($remainingThisYear, 2),
        'total_available_budget' => number_format($totalAvailableBudget, 2),
    ];
}







// Determine if the user is admin or barangay user
$userId = $_SESSION['user_data']['id'];
$role = $_SESSION['role']['name'];
$brgyID = $_SESSION["user_data"]['brgy_id'];
$cityID = $_SESSION["user_data"]['city_id'];

// Fetch global data for admin users
if ($role == "ADMIN ASSISTANT" || $role == "HDRRMO ADMIN") {
    $totalUsers = getTotalUsers($pdo);
    $totalBarangays = getTotalBarangays($pdo);
    $totalReportsForMonth = getTotalReportsForMonth($pdo);
    $pendingReports = getPendingReports($pdo);
    $notSubmittedBarangays = getNotSubmittedBarangays($pdo);
    $totalReportsForYear = getTotalReportsForYear($pdo);

    $getAllBudgetForThisYear = getAllBudgetForThisYear($pdo);
    $getBarangayBudgetDetails = getBarangayBudgetDetails($pdo);

} 

// Fetch user-specific data for BRGY USER
if ($role == "BRGY USER") {
    $totalReportsForMonth = getUserTotalReportsForMonth($pdo, $userId);
    $pendingReports = getUserPendingReportsForMonth($pdo, $userId);
    $totalAlocatedBudget = getOurBudgetForThisYear($pdo, $brgyID);
    $totalgetfromAlocatedBudget = getTotalamountspentofmybrgy($pdo, $brgyID);
    $totalgetfromQRFBudget = getTotalamountspentofmybrgyqrf($pdo, $brgyID);
    $totalReportsForYear = getUserTotalReportsForYear($pdo, $userId);
    $totalAmounNaNagastosThisYear = ($totalgetfromAlocatedBudget + $totalgetfromQRFBudget);
    $getTotalSpentByUserBarangay =getTotalSpentByUserBarangayGroupedByDate($pdo, $brgyID);
    $calamityFundsUsage = getCalamityFundsUsage($pdo, $brgyID);
    $lastyear = getTotalAvailableBudget($pdo, $brgyID);








}

?>
