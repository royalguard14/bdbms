<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mdrrmo";
$port = 3307;
try {
    // Use $pdo instead of $conn for consistency
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
function getUserPermissions($roleId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT p.name 
        FROM role_permission rp 
        JOIN permissions p ON rp.permission_id = p.id 
        WHERE rp.role_id = :role_id
        ");
    $stmt->execute(['role_id' => $roleId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>