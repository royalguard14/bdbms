<?php 
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
        if ($action == 'citis') {
        // Fetch all cities
            try {
                $stmt = $pdo->query("SELECT * FROM city ORDER BY name ASC");
                $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'cities' => $cities]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch cities.']);
            }
        }
        if ($action == 'brang') {
            $cityId = $data["city_id"];
            try {
                $stmt = $pdo->prepare("SELECT * FROM barangay WHERE city_id = :city_id ORDER BY name ASC");
                $stmt->bindParam(':city_id', $cityId);
                $stmt->execute();
                $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'barangays' => $barangays]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch barangays.']);
            }
        }
        // Handle store (create) action for users
        if ($action == 'store') {
            if (in_array('Create Account', $_SESSION['user_permissions'])) {
                if (isset($data['email']) && isset($data['password']) && !empty($data['email']) && !empty($data['password'])) {
                    $email = $data['email'];
                    $password = password_hash($data['password'], PASSWORD_BCRYPT);
                    $role_id = $data['role_id'] ?? 1; // Default to role_id 1
                    $city_id = $data['city_id'] ?? 0; // Default to 0
                    $brgy_id = $data['brgy_id'] ?? 0; // Default to 0
                    try {
                        $stmt = $pdo->prepare("INSERT INTO users (email, password, role_id, city_id, brgy_id) VALUES (:email, :password, :role_id, :city_id, :brgy_id)");
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':password', $password);
                        $stmt->bindParam(':role_id', $role_id);
                        $stmt->bindParam(':city_id', $city_id);
                        $stmt->bindParam(':brgy_id', $brgy_id);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Account created successfully!"
                        ]);
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                            echo json_encode(['error' => true, 'message' => 'Email already exists.']);
                        } else {
                            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Email and password are required.']);
                }
            } else {
                header("Location: views/errors/500.html");
                exit();
            }
        }
        // Handle update action for users
        if ($action == 'update') {
            if (in_array('Update Account', $_SESSION['user_permissions'])) {
        // Validate email, role_id, city_id, and accountId are provided
                if (isset($data['email']) && !empty($data['email']) && isset($data['accountId'])) {
                 $accountId = $data['accountId'];
                 $email = $data['email'];
                 $cityId = $data['city_id'] ?? null;
                 $brgyId = $data['brgy_id'] ?? null;
                 try {
                    $stmt = $pdo->prepare("
                        UPDATE users 
                        SET email = :email, city_id = :city_id, brgy_id = :brgy_id, updated_at = NOW() 
                        WHERE id = :id AND is_deleted = 0
                        ");
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':city_id', $cityId);
                    $stmt->bindParam(':brgy_id', $brgyId);
                    $stmt->bindParam(':id', $accountId);
                    $stmt->execute();
                // Success response
                    echo json_encode([
                        'success' => true,
                        'message' => "Account updated successfully!"
                    ]);
                } catch (PDOException $e) {
                    echo json_encode(['error' => true, 'message' => 'An error occurred while updating the account.']);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'Invalid input for email or account ID.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
        // Handle delete action for users
    if ($action == 'delete') {
        if (in_array('Delete Account', $_SESSION['user_permissions'])) {
            if (isset($data['id'])) {
                try {
                    $stmt = $pdo->prepare('UPDATE users SET is_deleted = 1 WHERE id = :id');
                    $stmt->bindParam(':id', $data['id']);
                    $stmt->execute();
                    echo json_encode([
                        'success' => true,
                        'message' => "Account deleted successfully!"
                    ]);
                } catch (Exception $e) {
                    echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'Invalid user ID for deletion.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
        // Handle fetch users action
    if ($action == 'fetch') {
        if (in_array('Read Account', $_SESSION['user_permissions'])) {
            try {
                $stmt = $pdo->prepare('SELECT * FROM users ORDER BY id ASC');
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch users.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
// Handle role update action for users
    if ($action == 'update_role') {
        if (in_array('Update Role', $_SESSION['user_permissions'])) {
            if (isset($data['accountId']) && isset($data['role_id'])) {
                $userId = $data['accountId'];
                $roleId = $data['role_id'];
                try {
                    $stmt = $pdo->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
                    $stmt->bindParam(':role_id', $roleId);
                    $stmt->bindParam(':id', $userId);
                    $stmt->execute();
                    echo json_encode([
                        'success' => true,
                        'message' => "User role updated successfully!"
                    ]);
                } catch (PDOException $e) {
                    echo json_encode(['error' => true, 'message' => 'An error occurred while updating the role.']);
                }
            } else {
                echo json_encode(['error' => true, 'message' => 'Invalid input data for role update.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
// Handle fetch roles action
    if ($action == 'fetch_roles') {
        if (in_array('Read Role', $_SESSION['user_permissions'])) {
            try {
                $stmt = $pdo->prepare('SELECT id, name FROM roles ORDER BY id ASC');
                $stmt->execute();
                $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode([
                    'success' => true,
                    'roles' => $roles
                ]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch roles.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
} else {
        // Default action if no action is passed
    if (in_array('Read Account', $_SESSION['user_permissions'])) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users ORDER BY id ASC');
            $stmt->execute();
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch users.']);
        }
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