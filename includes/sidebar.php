<nav class="ts-sidebar">
    <ul class="ts-sidebar-menu">
    
        <li class="ts-label">Main</li>
        <?php if(isset($_SESSION['id'])) { ?>
            <li><a href="dashboard.php"><i class="fa fa-desktop"></i> Dashboard</a></li>
            <li><a href="my-profile.php"><i class="fa fa-user"></i> My Profile</a></li>
            <li><a href="change-password.php"><i class="fa fa-files-o"></i> Change Password</a></li>
            <li><a href="room-details.php"><i class="fa fa-file-o"></i> Room Details</a></li>
            <li><a href="#"><i class="fa fa-calendar"></i> Leave Requests</a>
            <ul>
                <li><a href="leave_request.php">Make Requests</a></li>
            </ul>
            <ul>
                <li><a href="track_requests.php">Track Requests</a></li>
            </ul>
        </li>

        <?php } else { ?>
            <li><a href="registration.php"><i class="fa fa-files-o"></i> User Registration</a></li>
            <li><a href="index.php"><i class="fa fa-users"></i> User Login</a></li>
            <li><a href="admin"><i class="fa fa-user"></i> Admin Login</a></li>
        <?php } ?>
    </ul>
</nav>
