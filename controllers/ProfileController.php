<?php
header('Content-Type: application/json');
require_once 'connect.php'; 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_data'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'uploadProfilePic':
        handleUpload();
        break;
        case 'UpdateData':
        handleUpdateProfile(); 
        break;
        case 'fetchprofile':
        handlefetch(); 
        break;
        default:
        echo json_encode(['success' => false, 'message' => 'Invalid action: ' . $action]);
    }
}

function handleUpload() {
    global $pdo;
    // Get the user's ID from the session
    $userId = $_POST['user_id'];

    // Fetch current profile picture path from the database
    $stmt = $pdo->prepare("SELECT profile_pic FROM profiles WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a file was uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileSize = $_FILES['profile_pic']['size'];
        $fileType = $_FILES['profile_pic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Set the upload file path
        $uploadFileDir = '../assets/profilePic/';
        $newFileName = $userId . '.' . $fileExtension; // Save with user ID to avoid conflicts
        $dest_path = $uploadFileDir . $newFileName;
        $paths = 'assets/profilePic/' . $newFileName;

        // Check file size (5MB limit)
        if ($fileSize > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB.']);
            exit();
        }

        // Delete the old profile picture if it exists
        if ($profile && !empty($profile['profile_pic'])) {
            $oldFilePath = '../' . $profile['profile_pic']; // Full path to the old profile picture
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old profile picture
            }
        }

        // Move the uploaded file to the destination
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Update the profile picture in the database
            $stmt = $pdo->prepare("UPDATE profiles SET profile_pic = :profile_pic WHERE user_id = :user_id");
            $stmt->bindParam(':profile_pic', $paths);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            // Update session data
            $_SESSION["user_data"]["profile"]["profile_pic"] = $paths;
            echo json_encode(['success' => true, 'message' => 'Profile picture uploaded successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'There was an error moving the uploaded file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or there was an upload error.']);
    }
}








function handleUpdateProfile() {
    global $pdo;
    // Get the user's ID from the session
    $userId = $_SESSION["user_data"]['id'];
    // Extract the data from the request
    $firstName = $_POST['first_name'] ?? '';
    $middleName = $_POST['middle_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $suffix = $_POST['suffix'] ?? '';
    $birthDate = $_POST['birth_date'] ?? '';
    $birthPlace = $_POST['birth_place'] ?? '';
    $contactNo = $_POST['contact_no'] ?? '';
    $address = $_POST['address'] ?? '';
    // Begin transaction
    $pdo->beginTransaction();
    try {
        // Check if the profile already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM profiles WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $profileExists = $stmt->fetchColumn() > 0;
        if ($profileExists) {
            // Update the existing profile
            $stmt = $pdo->prepare("
                UPDATE profiles 
                SET 
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                suffix = :suffix,
                birthdate = :birth_date,
                birthplace = :birth_place,
                contact_number = :contact_no,
                address = :address,
                updated_at = NOW() 
                WHERE user_id = :user_id
                ");
        } else {
            // Insert new profile data
            $stmt = $pdo->prepare("
                INSERT INTO profiles (user_id, first_name, middle_name, last_name, suffix, birthdate, birthplace, contact_number, address, created_at, updated_at) 
                VALUES (:user_id, :first_name, :middle_name, :last_name, :suffix, :birth_date, :birth_place, :contact_no, :address, NOW(), NOW())
                ");
        }
        // Bind parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_name', $middleName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':birth_date', $birthDate);
        $stmt->bindParam(':birth_place', $birthPlace);
        $stmt->bindParam(':contact_no', $contactNo);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':user_id', $userId); // Bind user_id for both insert and update
        // Execute the update or insert
        $stmt->execute();
        $pdo->commit();
        $_SESSION["user_data"]['profile'] = [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'suffix' => $suffix,
        'birthdate' => $birthDate,   // Make sure to format it if needed
        'birthplace' => $birthPlace,
        'contact_number' => $contactNo,
        'address' => $address,
        'updated_at' => date('Y-m-d H:i:s') // Set updated_at to current time
    ];
        // Respond with success
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'An error occurred while updating the profile: ' . $e->getMessage()]);
}
}
function handlefetch(){
    global $pdo;
    // Ensure the session is started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Get the user's ID from the session
    $userId = $_SESSION["user_data"]['id']; 
    try {
        // Fetch the user's profile based on user_id
        $stmt = $pdo->prepare("SELECT * FROM profiles WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($profile) {
            $profiles = $profile; // Assign the fetched profile to $profiles
        } else {
            // No profile exists, provide default static data
            $profiles = [
                'first_name' => 'John',  // Default first name
                'last_name' => 'Doe',    // Default last name
                'middle_name' => '',     // Default middle name
                'suffix' => '',          // Default suffix
                'birth_date' => '',      // Default birth date
                'birth_place' => '',     // Default birth place
                'contact_no' => '',      // Default contact number
                'address' => '',         // Default address
                'profile_pic' => 'assets/profilePic/default.png' // Default profile picture
            ];
        }
        // Respond with success and return the profile data
        echo json_encode(['success' => true, 'profile' => $profiles]);
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Failed to load profile: ' . $e->getMessage()]);
    }
}