<?php
	session_set_cookie_params(36000);//10h
	session_start();
  include_once "introduce/img_data.php";
	include_once "introduce/password_hash.php";
  include_once "introduce/navbar.php";
	include_once "introduce/image_processor.php";
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
  $passbysql = "";
  $passbysql = get_db_password($user_name);
	$fname = $lname = $email = $pass = $address = $country = $city = $phone = '';
	$err = '';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$fname = test_empty($_POST['firstname']);
		$lname = test_empty($_POST['lastname']);
		$email = test_empty($_POST['email']);
		$pass  = test_empty($_POST['password']);
    $address = test_input($_POST['address']);
    $country = test_input($_POST['country']);
    $city = test_input($_POST['city']);
    $phone = test_input($_POST['phone']);

		test_err($fname, "/^[A-Z][a-z|À-ʸ|Σ-և]{2,}$/");
		test_err($lname, "/^[A-Z][a-z|À-ʸ|Σ-և]{2,}$/");
		test_err($email, "/^[a-z0-9]+@([a-z0-9]+\.)+[a-z]{2,}$/i");
		test_err($pass, "/^[^ ]{6,}$/");
		if ($err == '') {
			if (is_password($pass, $passbysql)){
				$filename = "";
				$arr = get_db_userimg($user_name);
				$UID = $arr[0]['UID'];
				$filename = $arr[0]['Headpath'];
				if ($_FILES["file"]['size'] != 0){
					if ((($_FILES["file"]["type"] == "image/gif")
					 || ($_FILES["file"]["type"] == "image/jpeg")
					 || ($_FILES["file"]["type"] == "image/png")
					 || ($_FILES['file']['type'] == "image/jpg"))) {
						 if ($_FILES['file']['error'] > 0) {
							 $err = 'Upload is Wrong.';
						 } else {
							 $ext = get_ext_name($_FILES['file']['name']);
							 $filename = $UID.".".$ext;
							 move_uploaded_file($_FILES['file']['tmp_name'], "image/usr/".$filename);
						 }
					} else {
						$err = "The file's type don't allow " . $_FILES['file']['type'];
						// print_r($_FILES);
					}
				}
				$connect = connect_db();
				$fname = $connect->real_escape_string($fname);
				$lname = $connect->real_escape_string($lname);
				$address = $connect->real_escape_string($address);
				$city = $connect->real_escape_string($city);
				$country = $connect->real_escape_string($country);
				$phone = $connect->real_escape_string($phone);
				$email = $connect->real_escape_string($email);
				$sql1 = "UPDATE traveluser
					SET UserName=\"$email\", State=\"1\", Headpath=\"$filename\"
					WHERE UID=$UID";
				$result1 = $connect->query($sql1);
				$sql2 = "UPDATE traveluserdetails
					SET FirstName=\"$fname\", LastName=\"$lname\", Address=\"$address\",
					City=\"$city\", Country=\"$country\", Phone=\"$phone\", Email=\"$email\"
					WHERE UID = $UID";
				$result2 = $connect->query($sql2);
			}else {
				$err = $err . "This password is wrong.";
			}
		}
	}
?>
<?php
  function make_info($value){
    global $err;
    ?>
      <div id="tabs_head">
				<p class="over">My Info</p>
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
      				</div>
      				<div class="modal-footer">
        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      				</div>
    				</div>
  				</div>
				</div>
			</div>
			<div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
  				<table>
          <tr>
    				<td colspan="2">
              <p class="col-sm-2 control-label"><b>Head Image:</b></p>
              <div class="col-sm-6">
                <input name="file" type="file" class="file" multiple data-show-upload="false" data-max-file-count="1" data-max-file-size="7800"/>
              </div>
    				</td>
    			</tr>
  				<tr>
  					<td class="user_name">
  						<label>FirstName</label>
  						<br/>
  						<div id="firstname-div" class="input-group">
    						<span class="input-group-addon" id="basic-addon1">F</span>
    						<input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $value['FirstName'];?>" aria-describedby="basic-addon1">
  						</div>
  						<span id="firstname-warn"></span>
  					</td>
  					<td class="user_name">
  						<label>LastName</label>
  						<br/>
  						<div id="lastname-div" class="input-group">
    						<span class="input-group-addon" id="basic-addon1">L</span>
    						<input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $value['LastName'];?>" aria-describedby="basic-addon1">
  						</div>
  						<span id="lastname-warn"></span>
  					</td>
  				</tr>
  				<tr>
  					<td colspan="2">
  						<label>E-mail</label>
  						<br/>
  						<div id="email-div" class="input-group">
  							<input type="text" name="email" id="email" class="form-control" value="<?php echo $value['Email'];?>" aria-describedby="basic-addon2">
   							<span class="input-group-addon" id="basic-addon2">@example.com</span>
  						</div>
  						<span id="email-warn"></span>
  					</td>
  				</tr>
          <tr>
            <td colspan="2">
              <label>Address</label>
  						<br/>
  						<div class="input-group">
                <span class="input-group-addon" id="basic-addon1">AD</span>
  							<input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo $value['Address'];?>" aria-describedby="basic-addon2">
  						</div>
            </td>
          </tr>
          <tr>
            <td>
              <label>Country</label>
  						<br/>
  						<div class="input-group">
    						<span class="input-group-addon" id="basic-addon1">Co</span>
    						<input type="text" name="country" class="form-control" placeholder="Country" value="<?php echo $value['Country'];?>" aria-describedby="basic-addon1">
  						</div>
            </td>
            <td>
              <label>City</label>
  						<br/>
  						<div class="input-group">
    						<span class="input-group-addon" id="basic-addon1">Ci</span>
    						<input type="text" name="city" class="form-control" placeholder="City" value="<?php echo $value['City'];?>" aria-describedby="basic-addon1">
  						</div>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <label>Phone</label>
  						<br/>
  						<div class="input-group">
    						<span class="input-group-addon" id="basic-addon1">Ph</span>
    						<input type="text" name="phone" class="form-control" placeholder="Phone" value="<?php echo $value['Phone'];?>" aria-describedby="basic-addon1">
  						</div>
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
              <p class="help-block">If you wanna change information, input password please.</p>
  						<span id="password-warn"></span>
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
    <?php
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Info</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/fileinput.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/infoStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
    <script src="js/fileinput.js"></script>
		<script src="js/navbarScript.js"></script>
		<script src="js/infoScript.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php navbar("", $user_name);?>
    <section id="info">
      <?php
      if ($user_name == ""){
        ?>
        <div class="jumbotron">
          <h1>NO LOGIN!</h1>
          <p>What you wanna is unexisted.</p>
          <p><a class="btn btn-primary btn-lg" href="#" role="button">Return</a></p>
        </div>
        <div style="height:300px;"></div>
        <?php
      } else {
        $arrInfo = get_db_changeInfo($user_name);
        make_info($arrInfo[0]);
      }
      ?>
    </section>
    <?php include_once "introduce/footer.php";?>
  </body>
</html>
