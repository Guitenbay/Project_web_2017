<?php
	session_set_cookie_params(36000);//10h
	session_start();
	include_once "introduce/img_data.php";
	include_once "introduce/password_hash.php";
	include_once "introduce/navbar.php";
?>
<?php
	$user_name = '';
	if (isset($_SESSION['user'])) {
		$user_name = $_SESSION['user'];
		$arr = get_db_NP($user_name);
		if (empty($arr)){
			$user_name = '';
		}
	}
?>
<?php
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
	}
?>
<?php
	$user = $password = "";
	$userErr = $passwordErr = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST['username'])) {
			$userErr = "Name is required";
		} else {
			$userErr = '';
			$user = test_input($_POST['username']);
		}
		if (empty($_POST['password'])) {
			$passwordErr = "Password is required";
		} else {
			$passwordErr = '';
			$password = test_input($_POST['password']);
		}
	}
?>
<?php
if ($user) {
	$arrNP = get_db_NP($user);
	if (sizeof($arrNP) == 0) {
		$userErr = "Username is unregistered.";
	} else {
		$userErr = '';
		if (is_password($password, $arrNP[0]['Pass'])){
			$passwordErr = '';
			session_start();
			$_SESSION['user'] = $user;
			header("location: index.php");
			exit;
		} else {
			$passwordErr = "Password is wrong.";
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Login</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/LRStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/navbarScript.js"></script>
		<script src="js/logingrammar.js"></script>
	</head>
	<body onscroll="topButton()">
  	<?php navbar("", $user_name);?>
		<section id="register">
	    <div id="tabs_head">
	      <a href="login.php" class="over">LOGIN</a>
	      <a href="register.php">SIGN UP</a>
	    </div>
			<div>
				<?php
				if ($userErr != '' or $passwordErr != ''){
					?>
					<div class="alert alert-danger" role="alert">
						<p><b>Oops!</b> try again please.</p>
					<?php
						if ($userErr != ''){
							?>
							<p><?php echo "* ".$userErr;?></p>
							<?php
						}?>
					<?php
						if ($passwordErr != ''){
							?>
							<p><?php echo "* ".$passwordErr;?></p>
							<?php
						}
					?>
					</div>
					<?php
				}
				?>
				<!-- trigger modal -->
				<div onclick="$('#myModal').modal();">
					<span id="quest" class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
				</div>

				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  				<div class="modal-dialog" role="document">
    				<div class="modal-content">
      				<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
      				</div>
      				<div class="modal-body">
        			<p><b>* Username</b>: Input your email.</p>
							<p><b>* Password</b>: Input at least 6 charactors which are any charactor except SPACE.</p>
      				</div>
      				<div class="modal-footer">
        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      				</div>
    				</div>
  				</div>
				</div>
			</div>
	    <div>
	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
	      <table>
	      <tr>
	        <td colspan="2">
	          <label>User Name</label>
	          <br/>
	          <div id="username-div" class="input-group">
  						<span class="input-group-addon" id="basic-addon1">@</span>
  						<input type="text" id="username" name="username" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
						</div>
						<span id="username-warn"></span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan="2">
	          <label>Password</label>
	          <br/>
	          <div id="password-div" class="input-group">
  						<span class="input-group-addon" id="basic-addon1">PW</span>
  						<input type="password" id="password" name="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1">
						</div>
						<span id="password-warn"></span>
	        </td>
	      </tr>
	      <tr>
	        <td colspan="2" id="submit">
	          <input type="submit" value="Login"/>
	        </td>
	      </tr>
	      </table>
	    </form>
	    </div>
	  </section>
  	<?php include_once 'introduce/footer.php'; ?>
  </body>
</html>
