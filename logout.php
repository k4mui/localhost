<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";

$errors = NULL;
$success = False;
$email_address = NULL;
$password = NULL;

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
  die("Something went wrong");
}
if ($user->is_registered()) {
  $success = True;
  $user->logout();
} else {
  $errors[] = "You need to <a href=\"/login.php\">login</a> first.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<title>wheel - Logout</title>
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
            if ($user->is_modetator()) { // mod
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_anonymous()) { // anon
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
				Logout
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
	    <div id="form-area">
        <div>
        <?php
        if (is_array($errors) && $errors) {
          echo "<div id='errors'>";
          foreach ($errors as $err) {
            echo "<div>$err</div>";
          }
          echo "</div>";
        } else if ($success) {
          echo "<div id=\"success\">"
            .    "Logged out successfully."
            . "</div>";
        }
        ?>
        </div>
      </div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
