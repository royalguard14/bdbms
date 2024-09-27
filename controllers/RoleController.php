<?php 
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in'] && isset($_SESSION["user_data"]["role_id"]) && $_SESSION["user_data"]["role_id"] == 1) {
  $data = json_decode(file_get_contents('php://input'), true);
  if ($data !== null && isset($data['action'])) {
    $action = $data['action'];
        // Handle store (create) action
    if ($action == 'store') {
      if (in_array('Create Role', $_SESSION['user_permissions'])) {
        if (isset($data['rolename']) && !empty($data['rolename'])) {
          $name = $data['rolename'];
          try {
            $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            echo json_encode([
              'success' => true,
              'message' => "Role creation successful!"
            ]);
          } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                          echo json_encode(['error' => true, 'message' => 'Role already exists. Please choose another.']);
                        } else {
                          echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                      }
                    } else {
                      echo json_encode(['error' => true, 'message' => 'Role name is required.']);
                    }
                  } else {
                    header("Location: views/errors/500.html");
                    exit();
                  }
                }
        // Handle update action
                if ($action == 'update') {
                  if (in_array('Update Role', $_SESSION['user_permissions'])) {
                    if (isset($data['rolename']) && !empty($data['rolename']) && isset($data['id'])) {
                      $name = $data['rolename'];
                      $id = $data['id'];
                      try {
                        $stmt = $pdo->prepare("UPDATE roles SET name = :name WHERE id = :id");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        echo json_encode([
                          'success' => true,
                          'message' => "Update successful!"
                        ]);
                      } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                          echo json_encode(['error' => true, 'message' => 'Role already exists. Please choose another.']);
                        } else {
                          echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                      }
                    } else {
                      echo json_encode(['error' => true, 'message' => 'Invalid role name or ID.']);
                    }
                  } else {
                    header("Location: views/errors/500.html");
                    exit();
                  }
                }
        // Handle delete action
                if ($action == 'delete') {
                  if (in_array('Delete Role', $_SESSION['user_permissions'])) {
                    if (isset($data['id'])) {
                      try {
                        $stmt = $pdo->prepare('DELETE FROM roles WHERE id = :id');
                        $stmt->bindParam(':id', $data['id']);
                        $stmt->execute();
                        echo json_encode([
                          'success' => true,
                          'message' => "Delete successful!"
                        ]);
                      } catch (Exception $e) {
                        echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                      }
                    } else {
                      echo json_encode(['error' => true, 'message' => 'Invalid ID for deletion.']);
                    }
                  } else {
                    header("Location: views/errors/500.html");
                    exit();
                  }
                }
                if ($data['action'] == 'fetchPermissions') {
                  try {
                    $roleId = $data['role_id'];
        // Fetch all permissions
                    $stmt = $pdo->prepare("SELECT * FROM permissions");
                    $stmt->execute();
                    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Fetch assigned permissions for the role
                    $stmt = $pdo->prepare("SELECT permission_id FROM role_permission WHERE role_id = :role_id");
                    $stmt->bindParam(':role_id', $roleId);
                    $stmt->execute();
                    $assignedPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    echo json_encode([
                      'success' => true,
                      'permissions' => $permissions,
                      'assigned_permissions' => $assignedPermissions
                    ]);
                  } catch (PDOException $e) {
        error_log($e->getMessage());  // Log any SQL errors
        echo json_encode(['success' => false, 'message' => 'Failed to fetch permissions.']);
      }
    }
    if ($data['action'] == 'savePermissions') {
      $roleId = $data['role_id'];
      $permissions = $data['permissions'];
      if (in_array('Grant Permission', $_SESSION['user_permissions'])) {
        try {
        // Remove all existing permissions for the role
          $stmt = $pdo->prepare("DELETE FROM role_permission WHERE role_id = :role_id");
          $stmt->bindParam(':role_id', $roleId);
          $stmt->execute();
        // Insert the new permissions
          $stmt = $pdo->prepare("INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)");
          foreach ($permissions as $permissionId) {
            $stmt->bindParam(':role_id', $roleId);
            $stmt->bindParam(':permission_id', $permissionId);
            $stmt->execute();
          }
          echo json_encode([
            'success' => true,
            'message' => 'Permissions updated successfully!'
          ]);
        } catch (PDOException $e) {
          echo json_encode([
            'success' => false,
            'message' => 'Failed to update permissions'
          ]);
        }
      } else {
        header("Location: views/errors/500.html");
        exit();
      }
    }
  } else {
        // No action, fetch roles
    if (in_array('Read Role', $_SESSION['user_permissions'])) {
            
      $stmt = $pdo->prepare('SELECT * FROM roles WHERE id != 1 ORDER BY id ASC ');
      $stmt->execute();
      $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
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