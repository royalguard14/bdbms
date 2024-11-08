<?php 
require_once 'connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true);

if ($data !== null && isset($data['action'])) {
    $action = $data['action'];

  if ($action == 'login') {
    $email = $data['username'];
    $password = $data['password'];

    try {
        // Fetch user by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND is_deleted = 0");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // User authenticated, set basic user data in session
            $_SESSION['log_in'] = true;
            $_SESSION["user_data"] = $user;

            // Fetch user role
            $stmt = $pdo->prepare("SELECT name FROM roles WHERE id = :id");
            $stmt->bindParam(':id', $user['role_id']);
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION["role"] = $role;

            // Now check if there is a profile associated with the user
            $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->execute();
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($profile) {
                // If profile exists, append profile data to session user data
                $_SESSION["user_data"]['profile'] = $profile;
            } else {
                // If no profile exists, provide static/default data
                $_SESSION["user_data"]['profile'] = [
                    'first_name' => 'John', // Default first name
                    'last_name' => 'Doe',   // Default last name
                    'address' => 'N/A',     // Default address
                    'phone' => 'N/A',       // Default phone
                ];
            }

            // Assign permissions to the session
            if (isset($_SESSION['user_data']['role_id'])) {
                $roleId = $_SESSION['user_data']['role_id'];
                $permissions = getUserPermissions($roleId, $pdo);
                $_SESSION['user_permissions'] = $permissions;
            } else {
                echo "User role not found in session.";
            }

            // Fetch the barangay name based on the brgy_id in the users table
            $stmt = $pdo->prepare("SELECT name FROM barangay WHERE id = :brgy_id");
            $stmt->bindParam(':brgy_id', $user['brgy_id']);
            $stmt->execute();
            $barangay = $stmt->fetch(PDO::FETCH_ASSOC);

            // If a barangay is found, set the name, otherwise set as 'None'
            $_SESSION["user_data"]['barangay_name'] = $barangay ? $barangay['name'] : '';




            // Fetch the city name based on the user id in the users table
            $stmt = $pdo->prepare("SELECT name FROM city WHERE id = :city_id");
            $stmt->bindParam(':city_id', $user['city_id']);
            $stmt->execute();
            $mycity = $stmt->fetch(PDO::FETCH_ASSOC);

                        // If a barangay is found, set the name, otherwise set as 'None'
            $_SESSION["user_data"]['city_name'] = $mycity ? $mycity['name'] : '';


            // Send JSON success response with profile and barangay name
            echo json_encode([
                'success' => true,
                'message' => "Login successful. Welcome!",
                'profile' => $_SESSION["user_data"]['profile'], // Return profile data in the response
                'barangay_name' => $_SESSION["user_data"]['barangay_name'] // Return barangay name in the response
            ]);

        } else {
            // Invalid login
            echo json_encode([
                'success' => false,
                'message' => "Invalid username or password."
            ]);
        }

    } catch (PDOException $e) {
        // Error handling
        echo json_encode([
            'success' => false,
            'message' => "Error: " . $e->getMessage()
        ]);
    }
}

    // Logout action
    if ($action == 'logout') {
        // Clear all session variables and destroy session
        $_SESSION = array();
        session_destroy();
        unset($_SESSION['log_in']);

        // Send logout success response
        echo json_encode([
            'success' => true,
            'message' => "Logout successful."
        ]);
        exit();
    }

    // Register action
    if ($action == 'register') {
        $email = $data['username'];
        $password = $data['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();
            echo "New user registered!";
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry code
                echo "Username already exists. Please choose another.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }
} 
?>
