<?php 
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in'] ) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
        // Handle store (create) action
        if ($action == 'store') {
            if (in_array('Create City', $_SESSION['user_permissions'])) {
                if (isset($data['cityname']) && !empty($data['cityname'])) {
                    $name = $data['cityname'];
                    try {
                        $stmt = $pdo->prepare("INSERT INTO city (name) VALUES (:name)");
                        $stmt->bindParam(':name', $name);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "City creation successful!"
                        ]);
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                            echo json_encode(['error' => true, 'message' => 'City already exists. Please choose another.']);
                        } else {
                            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'City name is required.']);
                }
            } else {
                header("Location: views/errors/500.html");
                exit();
            }
        }
        // Handle update action
        if ($action == 'update') {
            if (in_array('Update City', $_SESSION['user_permissions'])) {
                if (isset($data['cityname']) && !empty($data['cityname']) && isset($data['cityId'])) {
                    $name = $data['cityname'];
                    $id = $data['cityId'];
                    try {
                        $stmt = $pdo->prepare("UPDATE city SET name = :name WHERE id = :id");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Update successful!"
                        ]);
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                            echo json_encode(['error' => true, 'message' => 'City already exists. Please choose another.']);
                        } else {
                            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Invalid city name or ID.']);
                }
            } else {
                header("Location: views/errors/500.html");
                exit();
            }
        }
        // Handle delete action
        if ($action == 'delete') {
            if (in_array('Delete City', $_SESSION['user_permissions'])) {
                if (isset($data['id'])) {
                    try {
                        $stmt = $pdo->prepare('DELETE FROM city WHERE id = :id');
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
        // Handle fetch cities action
        if ($action == 'fetch') {
            if (in_array('Read City', $_SESSION['user_permissions'])) {
                try {
                    $stmt = $pdo->prepare('SELECT * FROM city ORDER BY id ASC');
                    $stmt->execute();
                    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode([
                        'success' => true,
                        'cities' => $cities
                    ]);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Failed to fetch cities.']);
                }
            } else {
                header("Location: views/errors/500.html");
                exit();
            }
        }
    } else {
        // Default action: Fetch all cities if no specific action is passed
        if (in_array('Read City', $_SESSION['user_permissions'])) {
            try {
                $stmt = $pdo->prepare('SELECT * FROM city ORDER BY id ASC');
                $stmt->execute();
                $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch cities.']);
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