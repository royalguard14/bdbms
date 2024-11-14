<?php 
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in'] && isset($_SESSION["user_data"]["role_id"]) && $_SESSION["user_data"]["role_id"] == 1) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
        // Handle store (create) action for permissions
        if ($action == 'store') {
            if (in_array('Create Permission', $_SESSION['user_permissions'])) {
                if (isset($data['permissionname']) && !empty($data['permissionname'])) {
                    $name = $data['permissionname'];
                    try {
                        $stmt = $pdo->prepare("INSERT INTO permissions (name) VALUES (:name)");
                        $stmt->bindParam(':name', $name);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Permission added successfully!"
                        ]);
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                            echo json_encode(['error' => true, 'message' => 'Permission already exists. Please choose another.']);
                        } else {
                            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Permission name is required.']);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'You do not have permission to create a permission.']);
            }
        }
        // Handle edit action for permissions
        if ($action == 'edit') {
            if (in_array('Update Permission', $_SESSION['user_permissions'])) {
                // Handle permission editing logic here
            } else {
                echo json_encode(['error' => true, 'message' => 'You do not have permission to edit permissions.']);
            }
        }
        // Handle update action for permissions
        if ($action == 'update') {
            if (in_array('Update Permission', $_SESSION['user_permissions'])) {
                if (isset($data['permissionname']) && !empty($data['permissionname']) && isset($data['id'])) {
                    $name = $data['permissionname'];
                    $id = $data['id'];
                    try {
                        $stmt = $pdo->prepare("UPDATE permissions SET name = :name WHERE id = :id");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Permission updated successfully!"
                        ]);
                    } catch (PDOException $e) {
                        echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Invalid permission name or ID.']);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'You do not have permission to update permissions.']);
            }
        }
        // Handle delete action for permissions
        if ($action == 'delete') {
            if (in_array('Delete Permission', $_SESSION['user_permissions'])) {
                if (isset($data['id'])) {
                    try {
                        $stmt = $pdo->prepare('DELETE FROM permissions WHERE id = :id');
                        $stmt->bindParam(':id', $data['id']);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Permission deleted successfully!"
                        ]);
                    } catch (Exception $e) {
                        echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Invalid ID for deletion.']);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'You do not have permission to delete permissions.']);
            }
        }
    } else {
        // Fetch permissions if no action is provided
        if (in_array('Read Permission', $_SESSION['user_permissions'])) {
            $stmt = $pdo->prepare('SELECT * FROM permissions ORDER BY id ASC');
            $stmt->execute();
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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