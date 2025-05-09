
<?php if($_SESSION['id'])
{ ?><div class="brand clearfix">
    <a href="#" class="logo" style="font-size:16px;">Hostel Management System</a>
    <span class="menu-btn"><i class="fa fa-bars"></i></span>
    <ul class="ts-profile-nav">
        <li>
            <a href="logout.php" style="font-size:16px;"><i class="fa fa-sign-out"></i> Logout</a>
        </li>
    </ul>
</div>

<?php
} else { ?>
<div class="brand clearfix">
		<a href="#" class="logo" style="font-size:16px;">Hostel Management System</a>
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		
	</div>
	<?php } ?>