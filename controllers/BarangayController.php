<?php
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null && isset($data['action'])) {
        $action = $data['action'];
        // Handle store (create) action
        if ($action == 'store') {
            if (in_array('Create Barangay', $_SESSION['user_permissions'])) {
                if (isset($data['barangayname']) && !empty($data['barangayname'])) {
                    $name = $data['barangayname'];
                    $cityId = $data['cityId'];
                    if ($_SESSION["user_data"]['city_id']==0) {
                        $cityId = $data['cityId'];
                    }else{
                        $cityId = $_SESSION["user_data"]['city_id'];
                    }
                    try {
                        $stmt = $pdo->prepare("INSERT INTO barangay (name, city_id) VALUES (:name, :cityId)");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':cityId', $cityId);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Barangay creation successful!"
                        ]);
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                            echo json_encode(['error' => true, 'message' => 'Barangay already exists. Please choose another.']);
                        } else {
                            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Barangay name is required.']);
                }
            } else {
                header("Location: views/errors/500.html");
                exit();
            }
        }
        // Handle update action
        if ($action == 'update') {
            if (in_array('Update Barangay', $_SESSION['user_permissions'])) {
                if (isset($data['barangayname']) && !empty($data['barangayname']) && isset($data['barangayId'])) {
                    $name = $data['barangayname'];
                    $id = $data['barangayId'];
                    try {
                        $stmt = $pdo->prepare("UPDATE barangay SET name = :name WHERE id = :id");
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        echo json_encode([
                            'success' => true,
                            'message' => "Update successful!"
                        ]);
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry code
                            echo json_encode(['error' => true, 'message' => 'Barangay already exists. Please choose another.']);
                        } else {
                            echo json_encode(['error' => true, 'message' => 'An error occurred.']);
                        }
                    }
                } else {
                    echo json_encode(['error' => true, 'message' => 'Invalid barangay name or ID.']);
                }
            } else {
                header("Location: views/errors/500.html");
                exit();
            }
        }
        // Handle delete action
        if ($action == 'delete') {
            if (in_array('Delete Barangay', $_SESSION['user_permissions'])) {
                if (isset($data['id'])) {
                    try {
                        $stmt = $pdo->prepare('DELETE FROM barangay WHERE id = :id');
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
        // Handle fetch barangays action
        if ($action == 'fetch') {
            if (in_array('Read Barangay', $_SESSION['user_permissions'])) {
                try {
                   if ($_SESSION["user_data"]['city_id']==0) {
                    $stmt = $pdo->prepare('SELECT * FROM barangay ORDER BY id ASC');
                }else{
                    $stmt = $pdo->prepare('SELECT * FROM barangay Where city_id = :cityid ORDER BY id ASC');
                }
                $stmt->bindParam(':cityid', $_SESSION["user_data"]['city_id']);
                $stmt->execute();
                $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode([
                    'success' => true,
                    'barangays' => $barangays
                ]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Failed to fetch barangays.']);
            }
        } else {
            header("Location: views/errors/500.html");
            exit();
        }
    }
        // Handle fetch cities action
    if ($action == 'fetchCities') {
        if (in_array('Read City', $_SESSION['user_permissions'])) {
            try {
                $stmt = $pdo->prepare('SELECT id, name FROM city ORDER BY name ASC');
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


        // Default action: Fetch all barangays if no specific action is passed
    if (in_array('Read Barangay', $_SESSION['user_permissions'])) {
        try {
            if ($_SESSION["user_data"]['city_id']==0) {
                $stmt = $pdo->prepare('SELECT * FROM barangay ORDER BY id ASC');
            }else{
                $stmt = $pdo->prepare('SELECT * FROM barangay Where city_id = :cityid ORDER BY id ASC');
            }
            $stmt->bindParam(':cityid', $_SESSION["user_data"]['city_id']);
            $stmt->execute();
            $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch barangays.']);
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