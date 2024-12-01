<?php 
require_once 'models.php'; 
require_once 'dbConfig.php';
 
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
	<style>
		body {
			font-family: "Arial";
		}
		input {
			font-size: 1.5em;
			height: 50px;
			width: 200px;
		}
		table, th, td {
			border:1px solid black;
		}
	</style>
</head>
<body>
	<h1>Are you sure you want to delete this Applicant?</h1>
	<?php $getApplicantByID = getApplicantByID($pdo, $_GET['applicant_id']); ?>
	<div class="container" style="border-style: solid; border-color: red; background-color: #ffcbd1;height: 500px;">
		<h2>Address: <?php echo $getApplicantByID['address']; ?></h2>
		<h2>Applicant: <?php echo $getApplicantByID['applicant']; ?></h2>
		<h2>Contact Number: <?php echo $getApplicantByID['contact_number']; ?></h2>

		<div class="deleteBtn" style="float: right; margin-right: 10px;">
			<form action="handleForms.php?applicant_id=<?php echo $_GET['applicant_id']; ?>" method="POST">
				<input type="submit" name="deleteApplicantBtn" value="Delete" style="background-color: #f69697; border-style: solid;">
			</form>			
		</div>	

	</div>
</body>
</html>
