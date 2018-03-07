<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";
require "$root/lib/db.php";
require "$root/lib/pre_processing.php";
require "$root/lib/validation.php";

$errors = NULL;
$success = False;
$email_address = NULL;
$password = NULL;
$confirm_password = NULL;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_POST["email_address"])) {
    $email_address = strsanitize($_POST["email_address"]);
  }
  if (isset($_POST["password"])) {
    $password = $_POST["password"];
  }
  if (isset($_POST["confirm_password"])) {
    $confirm_password = $_POST["confirm_password"];
  }
  $errors = array();
  if ($email_address) {
    check_email_address($email_address, $errors);
  } else {
    $errors[] = "Email address is required";
  }
  if ($password) {
    check_password_pair($password, $confirm_password, $errors);
  } else {
    $errors[] = "Password is required";
  }
  if (!$errors) {
    $da = new DataAccess;
    $success = $da->insert_user($email_address, $password);
    if (!$success) {
      $errors[] = "An account associated with $email_address already exists.";
    }
    unset($da);
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<title>wheel - Registration</title>
	<link href="/fonts/font-awesome/css/fontawesome-all.css" rel="stylesheet" type="text/css" />
	<link href="/styles/wheel.css?v=<?php echo time();?>" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="wrap-all">
  <div id="head">
			<div id="user-panel">
				<div class="row">
          <ul class="list float-left">
            <li id="home-link"><i class="fas fa-info-circle"></i> <a href="/">FAQ</a></li>
            <li id="home-link"><i class="fas fa-question-circle"></i> <a href="/">Help</a></li>
            <li id="home-link"><i class="fas fa-clipboard"></i> <a href="/">Rules</a></li>
          </ul>
					<ul class="list float-right">
						<?php
            if ($user->is_admin()) { //admin
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_mod()) { // mod
              echo '<li><i class="fas fa-envelope"></i> <a href="inbox.php">Admin Panel</a></li>';
            }
            if ($user->is_anon()) { // anon
              echo "<li><i class=\"fas fa-user-plus\"></i> <a href=\"/register.php\">Register</a></li>"
                .  "<li><i class=\"fas fa-sign-in-alt\"></i> <a href=\"/login.php\">Login</a></li>";
            }
            if ($user->is_registered()) { // common for registered
              echo "<li><i class=\"fas fa-user\"></i> <a href=\"/account.php\">Account</a></li>"
                .  "<li><i class=\"fas fa-sign-out-alt\"></i> <a href=\"/logout.php\">Logout</a></li>";
            }
            ?>
					</ul>
				</div> <!-- .row -->
			</div> <!-- #user-panel -->
			<div id="site-nav">
				<div class="row">
          <img id="site-logo" src="/images/shi.png" />
          <span id="site-title">wheel</span>
					<div id="site-search" class="float-right">
            <form class="input-group" action="/search.php" method="GET">
              <input type="text" name="q" placeholder="Search discussions..." />
              <input type="hidden" name="id" value="-1" />
							<button type="submit">
								<i class="fas fa-search"></i>
							</button>
						</form>
					</div>
				</div> <!-- .row -->
			</div> <!-- #site-nav -->
			<div id="page-title">
        <ul class="list">
          <li><i class="fas fa-home"></i> <a href="/">Boards Index</a></li>
          <li>/</li>
          <li><i class="fas fa-user-plus"></i> <a href="/register.php">Register</a></li>
        </ul>
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
            .    "Account has been created successfully. We have sent a link to "
            .    "<span class=\"font-bold\">$email_address</span>. Use that link "
            .    "to activate your account before using it."
            . "</div>";
        } else if ($user->is_registered()) {
          echo "<div>"
            .    "You are already logged in."
            .  "</div>";
        }
        ?>
        </div>
        <div>
          <?php
          if (!$success && !$user->is_registered()) {
            echo  "<form class=\"account-form\" action=\"\" method=\"post\">"
              .     "Email Address:<br/>"
              .     "<input type=\"text\" name=\"email_address\" maxlength=\"254\" value=\"\"/><br/>"
              .     "Password:<br/>"
              .     "<input type=\"password\" name=\"password\"/><br/>"
              .     "Confirm Password:<br/>"
              .     "<input type=\"password\" name=\"confirm_password\"/><br/>"
              .     "· Already have an account? <a href=\"/login.php\">Login here</a>."
              .     "<br/>"
              .     "· By registering, you agree to our <a href=\"\">Terms</a>."
              .     "<br/>"
              .     "<input type=\"submit\" value=\"Create account\"/>"
              .     "</form>";
          }
          ?>
        </div>
      </div> <!-- #form-area -->
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>
