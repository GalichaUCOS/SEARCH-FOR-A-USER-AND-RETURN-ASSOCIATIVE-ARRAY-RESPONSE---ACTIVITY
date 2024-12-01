<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location:login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location:register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location:register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location:register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location:index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location:login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: register.php");
	}

}


if (isset($_POST['insertNewApplicantBtn'])) {
	$address = trim($_POST['address']);
	$applicant = trim($_POST['applicant']);
	$contact_number = trim($_POST['contact_number']);

	if (!empty($address) && !empty($applicant) && !empty($contact_number)) {
		$insertApplicant = insertApplicant($pdo, $address, $applicant, 
			$contact_number, $_SESSION['username']);
		$_SESSION['status'] =  $insertApplicant['status']; 
		$_SESSION['message'] =  $insertApplicant['message']; 
		header("Location: index.php");
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: index.php");
	}

}

if (isset($_POST['updateApplicantBtn'])) {
    $address = trim($_POST['address']);
    $applicant = trim($_POST['applicant']);
    $contact_number = trim($_POST['contact_number']);
    $last_updated = date('Y-m-d H:i:s'); // Get the current timestamp
    $last_updated_by = $_SESSION['username']; // The user who updated

    if (!empty($address) && !empty($applicant) && !empty($contact_number)) {
        $updateApplicant = updateApplicant($pdo, $address, $applicant, $contact_number, 
            $last_updated, $last_updated_by, $_GET['applicant_id']);
        
        $_SESSION['message'] = $updateApplicant['message'];
        $_SESSION['status'] = $updateApplicant['status'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields.";
        $_SESSION['status'] = '400';
        header("Location: updateapplicant.php?applicant_id=" . $_GET['applicant_id']);
        exit();
    }
}


if (isset($_POST['deleteApplicantBtn'])) {
    // Correctly get applicant_id from the URL using $_GET
    $applicant_id = $_GET['applicant_id'];

    // Check if the applicant_id is valid
    if (!empty($applicant_id)) {
        // Call the delete function from models.php and pass the applicant_id
        $deleteApplicant = deleteApplicant($pdo, $applicant_id);

        // Store the result message in the session
        $_SESSION['message'] = $deleteApplicant['message'];
        $_SESSION['status'] = $deleteApplicant['status'];

        // Redirect to the index page
        header("Location: index.php");
        exit(); // Stop script execution after redirection
    } else {
        $_SESSION['message'] = "Invalid applicant ID";
        $_SESSION['status'] = '400';
        header("Location: index.php");
        exit(); // Stop script execution
    }
}


if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['username']);
	header("Location: login.php");
}

?>