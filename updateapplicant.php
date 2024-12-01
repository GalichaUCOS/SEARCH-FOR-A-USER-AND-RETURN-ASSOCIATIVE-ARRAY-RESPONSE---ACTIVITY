<?php  
require_once 'models.php'; 
require_once 'handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>

	<?php $getApplicantByID = getApplicantByID($pdo, $_GET['applicant_id']); ?>
	<form action="handleForms.php?applicant_id=<?php echo $_GET['applicant_id']; ?>" method="POST">
    <p>
        <label for="address">Address</label>
        <input type="text" name="address" value="<?php echo $getApplicantByID['address']; ?>" required>
    </p>
    <p>
        <label for="applicant">Applicant</label>
        <input type="text" name="applicant" value="<?php echo $getApplicantByID['applicant']; ?>" required>
    </p>
    <p>
        <label for="contact_number">Contact Number</label>
        <input type="text" name="contact_number" value="<?php echo $getApplicantByID['contact_number']; ?>" required>
    </p>
    <input type="submit" name="updateApplicantBtn" value="Update">
</form>

</body>
</html>