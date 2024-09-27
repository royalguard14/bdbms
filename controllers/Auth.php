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
			$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($user && password_verify($password, $user['password'])) {
				$_SESSION['log_in'] = true;
				$_SESSION["user_data"] = $user;
            // Send JSON response for success





				echo json_encode([
					'success' => true,
					'message' => "Login successful. Welcome!"
				]);


// Assuming the user session is already set
if (isset($_SESSION['user_data']['role_id'])) {
    $roleId = $_SESSION['user_data']['role_id'];
    $permissions = getUserPermissions($roleId, $pdo);
    $_SESSION['user_permissions'] = $permissions;
} else {
    echo "User role not found in session.";
}


				
			} else {
            // Send JSON response for failure
				echo json_encode([
					'success' => false,
					'message' => "Invalid username or password."
				]);
			}
		} catch(PDOException $e) {
        // Send JSON response for error
			echo json_encode([
				'success' => false,
				'message' => "Error: " . $e->getMessage()
			]);
		}
	}


	if ($action == 'logout') {
    // Clear all session variables
		$_SESSION = array();
    // Destroy the session
		session_destroy();
    // Ensure the log_in flag is unset
		unset($_SESSION['log_in']);
    // Send a JSON response indicating success
		echo json_encode([
			'success' => true,
			'message' => "Logout successful."
		]);
		exit();
	}



	if ($action=='register') {
		$email = $data['username'];
		$password = $data['password'];
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		try {
			$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':password', $hashedPassword);
			$stmt->execute();
			echo "New users registered!";
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