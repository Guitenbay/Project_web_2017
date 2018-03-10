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
	function test_empty($post) {
		global $err;
		if (empty($post)) {
			$err = 'Something is empty; ';
		} else {
			$err = $err;
		}
		return test_input($post);
	}
	function test_err($ele, $reg){
		global $err;
		if (preg_match($reg, $ele)) {
			$err = $err;
		} else {
			if (preg_match("/Wrong input; /", $err)){
				$err = $err;
			} else {
				$err = "Wrong input; " . $err;
			}
		}
	}
?>
<?php
	$fname = $lname = $email = $pass = $passagain = '';
	$err = '';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$fname = test_empty($_POST['firstname']);
		$lname = test_empty($_POST['lastname']);
		$email = test_empty($_POST['email']);
		$pass  = test_empty($_POST['password']);
		$passagain  = test_empty($_POST['passwordagain']);

		test_err($fname, "/^[A-Z][a-z|À-ʸ|Σ-և]{2,}$/");
		test_err($lname, "/^[A-Z][a-z|À-ʸ|Σ-և]{2,}$/");
		test_err($email, "/^[a-z0-9]+@([a-z0-9]+\.)+[a-z]{2,}$/i");
		test_err($pass, "/^[^ ]{6,}$/");
		test_err($passagain, "/^[^ ]{6,}$/");
		if ($pass != $passagain) {
			$err = "Passwords aren't same; ".$err;
		} else {
			$err = '' . $err;
		}
		if ($err == '') {
			if (empty(get_db_email($email))) {
				$connect = connect_db();
				$email = $connect->real_escape_string($email);
				$pass = $connect->real_escape_string($pass);
				$passhash = make_hash($pass);
				$fname = $connect->real_escape_string($fname);
				$lname = $connect->real_escape_string($lname);
				$time = date('Y-m-d H:i:s', time());
				$sql1 = "INSERT INTO traveluser
					(UserName, Pass, State, DateJoined)
					VALUES
					(\"$email\", \"$passhash\", \"1\", \"$time\")";
				$result1 = $connect->query($sql1);
				$sql2 = "INSERT INTO traveluserdetails
					(FirstName, LastName, Email, Privacy)
					VALUES
					(\"$fname\", \"$lname\", \"$email\", \"1\")";
				$result2 = $connect->query($sql2);
				if ($result1 and $result2){
					session_start();
					$_SESSION['user'] = $email;
					header("location: index.php");
					exit;
				}
			} else {
				$err = $err . "This E-mail has existed.";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Register</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/LRStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/navbarScript.js"></script>
		<script src="js/registergrammar.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php navbar("", $user_name);?>
    <section id="register">
			<div id="tabs_head">
				<a href="login.php">LOGIN</a>
				<a href="register.php" class="over">SIGN UP</a>
			</div>
			<div>
				<?php
				if ($err != ''){
					?>
					<div class="alert alert-danger" role="alert">
						<p><b>Oops!</b> try again please.</p>
						<p><?php echo $err;?></p>
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
        			<p><b>* Firstname || Lastname</b>: Input 3 charactors at least and the initail must be written in CAPITAL.</p>
							<p><b>* E-mail</b>: Input existed e-mail.</p>
							<p><b>* Password</b>: Input at least 6 charactors which are any charactor except SPACE.</p>
							<p><b>* Password Again</b>: Input password again.</p>
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
					<td class="user_name">
						<label>FirstName</label>
						<br/>
						<div id="firstname-div" class="input-group">
  						<span class="input-group-addon" id="basic-addon1">F</span>
  						<input type="text" id="firstname" name="firstname" class="form-control" placeholder="Firstname" aria-describedby="basic-addon1">
						</div>
						<span id="firstname-warn"></span>
					</td>
					<td class="user_name">
						<label>LastName</label>
						<br/>
						<div id="lastname-div" class="input-group">
  						<span class="input-group-addon" id="basic-addon1">L</span>
  						<input type="text" id="lastname" name="lastname" class="form-control" placeholder="Lastname" aria-describedby="basic-addon1">
						</div>
						<span id="lastname-warn"></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<label>E-mail</label>
						<br/>
						<div id="email-div" class="input-group">
							<input type="text" name="email" id="email" class="form-control" placeholder="Recipient's username" aria-describedby="basic-addon2">
 							<span class="input-group-addon" id="basic-addon2">@example.com</span>
						</div>
						<span id="email-warn"></span>
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
					<td colspan="2">
						<label>Password again</label>
						<br/>
						<div id="passwordagain-div" class="input-group">
  						<span class="input-group-addon" id="basic-addon1">PA</span>
  						<input type="password" id="passwordagain" name="passwordagain" class="form-control" placeholder="Password again" aria-describedby="basic-addon1">
						</div>
						<span id="passwordagain-warn"></span>
					</td>
				</tr>
				<tr>
					<td colspan="2" id="submit">
            <input type="submit" value="Sign Up"/>
					</td>
				</tr>
				</table>
			</form>
			</div>
		</section>
    <?php include "introduce/footer.php";?>
  </body>
</html>
