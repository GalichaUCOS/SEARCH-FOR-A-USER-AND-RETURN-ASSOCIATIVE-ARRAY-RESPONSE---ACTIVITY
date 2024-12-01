<div class="greeting">
    <h1>Hello there! Welcome to the Application Management Portal, <span style="color: blue;"><?php echo $_SESSION['username']; ?></span></h1>
</div>

<div class="navbar">
    <a href="index.php" class="nav-btn">Home</a>
    <a href="insertapplicant.php" class="nav-btn">Add Applicant</a>
    <a href="allusers.php" class="nav-btn">All Users</a>
    <a href="activitylogs.php" class="nav-btn">Activity Logs</a>
    <a href="handleForms.php?logoutUserBtn=1" class="nav-btn logout-btn">Logout</a>
</div>
