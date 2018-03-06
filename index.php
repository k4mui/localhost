<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/lib/init.php";
require "$root/lib/db.php";

$data = array();
$da = new DataAccess;
$da->load_boards_to_array($data);
unset($da);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
  <title>wheel - Home</title>
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png" />
  <link rel="shortcut icon" type="image/x-icon" href="/favicon/favicon.ico" />
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
              echo "<li><i class=\"fas fa-user-plus\"></i> <a href=\"register.php\">Register</a></li>"
                .  "<li><i class=\"fas fa-sign-in-alt\"></i> <a href=\"login.php\">Login</a></li>";
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
        <ul class="list">
          <li><i class="fas fa-home"></i> <a href="/">Boards Index</a></li>
        </ul>
			</div> <!-- #page-title -->
		</div> <!-- #head -->
		<div id="body-wrapper">
			<div class="row">
					<div id="body-left" class="float-left">
            <div id="boards-section">
              <div class="card-header">Boards</div>
              <?php
              foreach ($data as $id => $info) {
                echo "<div class=\"boards-item\">"
                  .    "<span class=\"boards-icon\">"
                  .       "<i class=\"fas fa-" . $info["icon"] . "\"></i>"
                  .    "</span>"
                  .    "<div class=\"boards-title\">"
                  .      "<h3><a href=\"viewboard.php?id=" . $id . "\">" . $info["title"] . "</a></h3>"
                  .      "<div class=\"boards-stats\">"
                  .        "<span class=\"fg-bright\">Discussions:</span> <span class=\"fg-black\">" . $info["discussion_count"] . "</span> ·"
                  .	       "<span class=\"fg-bright\">Replies:</span> <span class=\"fg-black\">" . $info["post_count"] . "</span> ·"
                  .        "<span class=\"fg-bright\">Images:</span> <span class=\"fg-black\">" . $info["image_count"] . "</span>"
                  .      "</div> <!-- .boards-stats -->"
                  .    "</div>"
                  .    "<div class=\"boards-recent\">"
                  .      "<img class=\"recent-img\" src=\"/images/Kakashi_Hatake.png\" alt=\"x\"/>"
                  .      "<div class=\"boards-recent-info\">"
                  .        "<div class=\"boards-recent-title\">"
                  .          "<span class=\"fg-bright\">Recent:</span> <a href=\"\">Re: Verrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrry big title33333333333333333333333333333333333</a>"
                  .        "</div>"
                  .        "<div class=\"\"><span class=\"fg-bright\">Posted at:</span> Today at 2Pm</div>"
                  .      "</div>"
                  .    "</div>"
                  .  "</div> <!-- .boards-item -->";
              }
              ?>
            </div> <!-- #boards-section -->
					</div>
					<div id="body-right">
            <div id="recent-posts-section">
              <div class="card-header">Recent Posts</div>
              <div id="recent-posts">
                <div class="recent-item">
                  <img class="top" src="/images/kakashi.jpg">
                  <div class="recent-info">
                    <div class="boards-recent-title">
                      <a href="viewboard.php?id=1">Re: Shiva's guard oero untet oehtoeipoetuietueitueitueiueirueirueiruieruier uieruier</a>
                    </div>
                    <div class="boards-stats">
                      Today at 2pm
                    </div> <!-- .boards-stats -->
                    <div class="boards-stats">
                      <a href="">Programming</a>
                    </div>
                  </div>
                </div> <!-- .recent-item -->
              </div>
            </div> <!-- #recent-posts-section -->
            <div id="statistics-section">
              <div class="card-header">Statistics</div>
              <div id="stats">
                <div class="stats-item">
                  <span class="stats-left">Total Discussions:</span>
                  <span class="stats-right">23,444</span>
                </div>
                <div class="stats-item">
                  <span class="stats-left">Total Replies:</span>
                  <span class="stats-right">23,444</span>
                </div>
                <div class="stats-item">
                  <span class="stats-left">Total Images:</span>
                  <span class="stats-right">23,444</span>
                </div>
                <div class="stats-item">
                  <span class="stats-left">Total Content:</span>
                  <span class="stats-right">23GB</span>
                </div>
              </div>
            </div> <!-- #statistics-section -->
					</div>
			</div>
		</div> <!-- #body-wrapper -->
		<div id="foot">
			&copy; 2018 wheel
		</div> <!-- #footer -->
	</div> <!-- #wrap-all -->
</body>
</html>