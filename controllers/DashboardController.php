<?php
require_once 'connect.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Total users (global for admin)
function getTotalUsers($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
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

function getUserTotalReportsForYear($pdo, $userId) {
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

// Determine if the user is admin or barangay user
$userId = $_SESSION['user_data']['id'];
$role = $_SESSION['role']['name'];

// Fetch global data for admin users
if ($role == "ADMIN ASSISTANT" || $role == "HDRRMO ADMIN") {
    $totalUsers = getTotalUsers($pdo);
    $totalBarangays = getTotalBarangays($pdo);
    $totalReportsForMonth = getTotalReportsForMonth($pdo);
    $pendingReports = getPendingReports($pdo);
    $notSubmittedBarangays = getNotSubmittedBarangays($pdo);
    $totalReportsForYear = getTotalReportsForYear($pdo);
} 

// Fetch user-specific data for BRGY USER
if ($role == "BRGY USER") {
    $totalReportsForMonth = getUserTotalReportsForMonth($pdo, $userId);
    $pendingReports = getUserPendingReportsForMonth($pdo, $userId);
    $totalReportsForYear = getUserTotalReportsForYear($pdo, $userId);
}

?>
