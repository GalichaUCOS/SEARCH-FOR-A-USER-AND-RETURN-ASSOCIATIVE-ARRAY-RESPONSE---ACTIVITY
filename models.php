<?php  

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllApplicants($pdo) {
	$sql = "SELECT * FROM applicants";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getAllApplicantsBySearch($pdo, $search_query) {
	$sql = "SELECT * FROM applicants WHERE 
			CONCAT(address,applicant,
				contact_number,
				date_added,added_by,
				last_updated,
				last_updated_by) 
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$search_query."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getApplicantByID($pdo, $applicant_id) {
	$sql = "SELECT * FROM applicants WHERE applicant_id = ?";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute([$applicant_id])) {
		return $stmt->fetch();
	}
}

function insertAnActivityLog($pdo, $operation, $applicant_id, $address, 
		$applicant, $contact_number, $username) {

	$sql = "INSERT INTO activity_logs (operation, applicant_id, address, 
		applicant, contact_number, username) VALUES(?,?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $applicant_id, $address, 
		$applicant, $contact_number, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs 
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertApplicant($pdo, $address, $applicant, $contact_number, $added_by) {
	$response = array();
	$sql = "INSERT INTO applicants (address, applicant, contact_number, added_by) VALUES(?,?,?,?)";
	$stmt = $pdo->prepare($sql);
	$insertApplicant = $stmt->execute([$address, $applicant, $contact_number, $added_by]);

	if ($insertApplicant) {
		$findInsertedItemSQL = "SELECT * FROM applicants ORDER BY date_added DESC LIMIT 1";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute();
		$getApplicantID = $stmtfindInsertedItemSQL->fetch();

		$insertAnActivityLog = insertAnActivityLog($pdo, "INSERT", $getApplicantID['applicant_id'], 
			$getApplicantID['address'], $getApplicantID['applicant'], 
			$getApplicantID['contact_number'], $_SESSION['username']);

		if ($insertAnActivityLog) {
			$response = array(
				"status" =>"200",
				"message"=>"Applicant added Successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
		
	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"Insertion of data failed!"
		);

	}

	return $response;
}

function updateApplicant($pdo, $address, $applicant, $contact_number, 
	$last_updated, $last_updated_by, $applicant_id) {

	$response = array();
	$sql = "UPDATE applicants
			SET address = ?,
				applicant = ?,
				contact_number = ?, 
				last_updated = ?, 
				last_updated_by = ? 
			WHERE applicant_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$updateApplicant = $stmt->execute([$address, $applicant, $contact_number, 
	$last_updated, $last_updated_by, $applicant_id]);

	if ($updateApplicant) {

		$findInsertedItemSQL = "SELECT * FROM applicants WHERE applicant_id = ?";
		$stmtfindInsertedItemSQL = $pdo->prepare($findInsertedItemSQL);
		$stmtfindInsertedItemSQL->execute([$applicant_id]);
		$getApplicantID = $stmtfindInsertedItemSQL->fetch(); 

		$insertAnActivityLog = insertAnActivityLog($pdo, "UPDATE", $getApplicantID['applicant_id'], 
			$getApplicantID['address'], $getApplicantID['applicant'], 
			$getApplicantID['contact_number'], $_SESSION['username']);

		if ($insertAnActivityLog) {

			$response = array(
				"status" =>"200",
				"message"=>"Updated the applicant successfully!"
			);
		}

		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}

	}

	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;

}


function deleteApplicant($pdo, $applicant_id) {
	$response = array();
	$sql = "SELECT * FROM applicants WHERE applicant_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$applicant_id]);
	$getApplicantByID = $stmt->fetch();

	$insertAnActivityLog = insertAnActivityLog($pdo, "DELETE", $getApplicantByID['applicant_id'], 
		$getApplicantByID['address'], $getApplicantByID['applicant'], 
		$getApplicantByID['contact_number'], $_SESSION['username']);

	if ($insertAnActivityLog) {
		$deleteSql = "DELETE FROM applicants WHERE applicant_id = ?";
		$deleteStmt = $pdo->prepare($deleteSql);
		$deleteQuery = $deleteStmt->execute([$applicant_id]);

		if ($deleteQuery) {
			$response = array(
				"status" =>"200",
				"message"=>"Deleted the applicant successfully!"
			);
		}
		else {
			$response = array(
				"status" =>"400",
				"message"=>"Insertion of activity log failed!"
			);
		}
	}
	else {
		$response = array(
			"status" =>"400",
			"message"=>"An error has occured with the query!"
		);
	}

	return $response;
}


// $getAllapplicantesBySearch = getAllapplicantesBySearch($pdo, "Dasma");
// echo "<pre>";
// print_r($getAllapplicantesBySearch);
// echo "<pre>";



?>