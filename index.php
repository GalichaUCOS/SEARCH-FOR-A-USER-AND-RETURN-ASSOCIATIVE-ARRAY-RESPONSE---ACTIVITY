<?php
require_once 'models.php'; 
require_once 'handleForms.php'; 

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch applicants based on search or get all applicants
$applicants = [];
if (isset($_GET['searchBtn']) && !empty($_GET['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];
    $applicants = getAllApplicantsBySearch($pdo, $searchQuery);
} else {
    $applicants = getAllApplicants($pdo);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .tableClass table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1rem;
            text-align: left;
        }

        .tableClass th, .tableClass td {
            border: 1px solid #ddd;
            padding: 12px;
        }

        .tableClass th {
            background-color: #f4f4f4;
            color: #333;
        }

        .tableClass tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .tableClass tr:hover {
            background-color: #f1f1f1;
        }

        .action-links a {
            text-decoration: none;
            color: #007bff;
            margin-right: 10px;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .searchForm {
            margin: 20px;
        }

        .searchForm form {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .searchForm input[type="text"] {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .searchForm input[type="submit"] {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .searchForm input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="searchForm">
        <form action="index.php" method="GET">
            <input type="text" name="searchQuery" placeholder="Search applicants" value="<?php echo htmlspecialchars($_GET['searchQuery'] ?? '', ENT_QUOTES); ?>">
            <input type="submit" name="searchBtn" value="Search">
            <a href="index.php" style="margin-left: 10px;">Reset</a>
        </form>
    </div>

    <?php  
    if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        echo '<h1 style="color: ' . ($_SESSION['status'] == "200" ? 'green' : 'red') . ';">' . htmlspecialchars($_SESSION['message']) . '</h1>';
        unset($_SESSION['message'], $_SESSION['status']);
    }
    ?>

    <div class="tableClass">
        <table>
            <thead>
                <tr>
                    <th>Address</th>
                    <th>Applicant</th>
                    <th>Contact Number</th>
                    <th>Date Added</th>
                    <th>Added By</th>
                    <th>Last Updated</th>
                    <th>Last Updated By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applicants)): ?>
                    <?php foreach ($applicants as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['applicant']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                            <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_updated_by']); ?></td>
                            <td class="action-links">
                                <a href="updateapplicant.php?applicant_id=<?php echo $row['applicant_id']; ?>">Update</a>
                                <a href="deleteapplicant.php?applicant_id=<?php echo $row['applicant_id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No applicants found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
