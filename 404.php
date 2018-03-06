<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
  <title>wheel - Error</title>
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css" />
	<link href="/styles/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="wrap-all">
		<div id="head">
			<div id="user-panel" class="bg-wet-asphalt">
				<div class="row">
					<ul class="list float-right">
						<?php
              if ($user->is_admin()) { //admin
                  echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
              }
              if ($user->is_mod()) { // mod
                  echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
              }
              if ($user->is_anon()) { // anon
                  echo "<li><i class=\"fas fa-user-plus\"></i> <a href=\"register.php\">Register</a></li>"
                  .  "<li><i class=\"fas fa-sign-in-alt\"></i> <a href=\"login.php\">Login</a></li>";
              }
              if ($user->is_registered()) { // common for registered
                  echo "<li><i class=\"fas fa-user\"></i> <a href=\"/account.php\">Account</a></li>"
                  .  "<li><i class=\"fas fa-sign-out-alt\"></i> <a href=\"/logout.php\">Logout</a></li>";
              }
            ?>
						<li></li>
					</ul>
				</div> <!-- .row -->
			</div> <!-- #user-panel -->
			<div id="site-nav">
				<div class="row">
					<div id="nav-links" class="float-left">
						<span id="site-logo">wheel</span>
						<a href="">FAQ</a>
						<a href="">Rules</a>
						<a href="">Help</a>
						<a href="">Contact</a>
					</div>
					<div id="site-search" class="float-right">
						<form class="input-group" action="index.html" method="get">
							<input type="text" name="_query" placeholder="Search discussions..." />
							<button type="submit">
								<i class="fas fa-search"></i>
							</button>
						</form>
					</div>
				</div> <!-- .row -->
			</div> <!-- #site-nav -->
			<div id="page-title">
				Error = occured
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
     	<div class="row">
       Sorry your request cannot be fullfilled :)
			</div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
