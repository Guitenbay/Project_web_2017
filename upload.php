<?php
	session_set_cookie_params(36000);//10h
	session_start();
	include_once "introduce/img_data.php";
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
?>
<?php
	$name = $describe = $city = $country = $longitude = $latitude = "";
	$err = $info = '';
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$name = test_input($_POST['photo_name']);
		$describe = test_input($_POST['photo_description']);
		$city = test_input($_POST['city']);
		$country = test_input($_POST['country']);
		$longitude = test_input($_POST['longitude']);
		$latitude = test_input($_POST['latitude']);
		if ($name == '') {
			$err = "Write your photo's TITLE please.";
		} else {
			if ($city == '') {
				$err = "Select City that your photo is from please.";
			} else {
				if (isset($_POST['modify']) and $_POST['modify'] and isset($_POST['imgPath']) and $_POST['imgPath']){
					$connect = connect_db();
					$name = $connect->real_escape_string($name);
					$describe = $connect->real_escape_string($describe);
					$city = $connect->real_escape_string($city);
					$country = $connect->real_escape_string($country);
					$longitude = $connect->real_escape_string($longitude);
					$latitude = $connect->real_escape_string($latitude);
					$imgID = get_db_imageID($_POST['imgPath']);
					$sql = "UPDATE travelimagedetails
					 SET Title=\"$name\", Description=\"$describe\", CityCode=\"$city\",
				 		CountryCodeISO=\"$country\", Longitude=\"$longitude\", Latitude=\"$latitude\"
						WHERE ImageID=$imgID";
					$result = $connect->query($sql);
					if ($result){
						header("Location: describe.php?img=".$_POST['imgPath']);
						exit;
					} else {
						die("Change has wrong: ".mysqli_error($connect));
					}
				} else {
					if ((($_FILES["file"]["type"] == "image/gif")
					 || ($_FILES["file"]["type"] == "image/jpeg")
					 || ($_FILES["file"]["type"] == "image/png")
					 || ($_FILES['file']['type'] == "image/jpg"))) {
						 if ($_FILES['file']['error'] > 0) {
							 $err = 'Upload is Wrong.';
						 } else {
							 $arr = get_db_userimg($user_name);
							 $UID = $arr[0]['UID'];
							 $filename = generate_file_name($arr[0]['UID'], $_FILES['file']['name']);
							 move_uploaded_file($_FILES['file']['tmp_name'], "image/travel-images/large/".$filename);
							 image_crop($filename, 600, 400, 'medium');
							 image_crop($filename, 150, 150, 'square-medium');

							 $connect = connect_db();
							 $name = $connect->real_escape_string($name);
		 					 $describe = $connect->real_escape_string($describe);
		 					 $city = $connect->real_escape_string($city);
		 					 $country = $connect->real_escape_string($country);
							 $longitude = $connect->real_escape_string($longitude);
		 					 $latitude = $connect->real_escape_string($latitude);
							 $sql1 = "INSERT INTO travelimage
							 	(UID, Path)
								VALUES
								(\"$UID\", \"$filename\")";
							 $result1 = $connect->query($sql1);
							 $imgID = get_db_imageID($filename);
							 $sql2 = "INSERT INTO travelimagedetails
							 	(ImageID, Title, Description, Latitude, Longitude, CityCode, CountryCodeISO)
								VALUES
								(\"$imgID\", \"$name\", \"$describe\", \"$latitude\", \"$longitude\", \"$city\", \"$country\")";
							 $result2 = $connect->query($sql2);
							 if ($result1 and $result2){
								 header("Location: describe.php?img=".$filename);
								 exit;
							 } else {
								 die("Upload has wrong: ".mysqli_error($connect));
							 }
						 }
					} else {
						$err = "The file's type don't allow " . $_FILES['file']['type'];
					}
				}
			}
		}
	}
?>
<?php
$titleValue = $describeValue = $longValue = $laValue = $pathValue = "";
if (isset($_GET['img']) and $_GET['img']) {
	$arrUpInfo = get_db_uploadedInfo($_GET['img']);
	$titleValue = $arrUpInfo[0]['Title'];
	$describeValue = $arrUpInfo[0]['Description'];
	$longValue = $arrUpInfo[0]['Longitude'];
	$laValue = $arrUpInfo[0]['latitude'];
	$path = $arrUpInfo[0]['Path'];
	$img_info = getimagesize("image/travel-images/large/$path");
	$style = "";
	if ($img_info[0] >= $img_info[1]){
		$style = 'max-width:100%;';
	} else {
		$style = 'max-height:780px;';
	}
}
?>
<?php
function make_img_selectors($arr){
	?>
	<div class="col-sm-4">
		<select id="continent" placeholder="Filter by continent" name="continent"
			 onchange='make_options("continent=" + this.options[this.selectedIndex].value, "country")'>
			<option value="">--select continent--</option>
			<?php
			$arrContinent = get_db_continentname();
			foreach ($arrContinent as $value) {
				# code...
				$selected = '';
				if ($value['ContinentCode'] == $arr[0]['ContinentCode']){
					 $selected = 'selected';
					 ?>
					 <option value="<?php echo $value['ContinentCode'];?>" selected><?php echo $value['ContinentName'];?></option>
					 <?php
				} else {
					?>
					<option value="<?php echo $value['ContinentCode'];?>"><?php echo $value['ContinentName'];?></option>
					<?php
				}
			}
			?>
		</select>
	</div>
	<div class="col-sm-4">
	<select id="country" placeholder="Filter by country" name="country"
		onchange='make_options("country=" + this.options[this.selectedIndex].value, "city")'>
		<option value="">--select country--</option>
		<?php
		$arrCountry = get_db_countryarray($arr[0]['ContinentCode']);
		foreach ($arrCountry as $value) {
			# code...
			$selected = '';
			if ($value['ISO'] == $arr[0]['CountryCodeISO']){
				 $selected = 'selected';
				 ?>
				 <option value="<?php echo $value['ISO'];?>" selected><?php echo $value['CountryName'];?></option>
				 <?php
			} else {
				?>
				<option value="<?php echo $value['ISO'];?>"><?php echo $value['CountryName'];?></option>
				<?php
			}
		}
		?>
	</select>
	</div>
	<div class="col-sm-4">
		<select id="city" placeholder="Filter by city" name="city">
			<option value="">--select city--</option>
			<?php
			$arrCity = get_db_cityarray($arr[0]['CountryCodeISO']);
			foreach ($arrCity as $value) {
				# code...
				$selected = '';
				if ($value['GeoNameID'] == $arr[0]['GeoNameID']){
					 $selected = 'selected';
					 ?>
					 <option value="<?php echo $value['GeoNameID'];?>" selected><?php echo $value['AsciiName'];?></option>
					 <?php
				} else {
					?>
					<option value="<?php echo $value['GeoNameID'];?>"><?php echo $value['AsciiName'];?></option>
					<?php
				}
			}
			?>
		</select>
	</div>
	<?php
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Upload</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/fileinput.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/uploadStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/fileinput.js"></script>
		<script src="js/navbarScript.js"></script>
		<script src="js/selector_ajax.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php navbar("Upload", $user_name)?>
		<div id="upload_head">
			<h1>Enrich, your world</h1>
		</div>
		<section id="upload">
		<?php
		if ($user_name == ''){
			?>
      <div class="jumbotron">
        <h1>NO LOGIN!</h1>
        <p>What you wanna is unexisted.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Return</a></p>
      </div>
      <div style="height:300px;"></div>
      <?php
		} else {
			?>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
					<p>New Photos</p>
						<ul>
							<?php
							if ($err != ''){
								?>
								<li>
								<div class="alert alert-danger" role="alert">
									<p><b>Oops!</b> try again please.</p>
									<p><?php echo $err;?></p>
								</div>
								</li>
								<?php
							}
							?>
							<?php
							if (isset($_GET['img']) and $_GET['img']){
								?>
								<li>
									<img style=<?php echo $style;?> src="image/travel-images/large/<?php echo $path;?>" alt="photo" />
								</li>
								<?php
							}
							?>
							<?php
							if (!isset($_GET['img'])){
								?>
								<li class="upphoto">
		              <p class="col-sm-2 control-label">Select photo:</p>
		              <div class="col-sm-6">
		                <input name="file" type="file" class="file" multiple data-show-upload="false" data-max-file-count="1" data-max-file-size="7800"/>
		              </div>
								</li>
								<?php
							} else {
								?>
								<li>
									<input type="text" class="form-control" name="imgPath" value="<?php echo $_GET['img'];?>" readonly/>
								</li>
								<?php
							}
							?>
							<li>
								<input type="text" class="form-control" placeholder="Title..." name="photo_name" value="<?php echo $titleValue;?>"/>
							</li>
							<li>
								<?php
								if (isset($_GET['img']) and $_GET['img']){
									?>
									<textarea rows="3" class="form-control" placeholder="Description..." name="photo_description"><?php echo $describeValue;?></textarea>
								</li>
								<li>
									<?php
									make_img_selectors($arrUpInfo);
								} else {
									?>
									<textarea rows="3" class="form-control" placeholder="Description..." name="photo_description" value="<?php echo $describeValue;?>"></textarea>
								</li>
								<li>
									<div class="col-sm-4">
										<select id="continent" placeholder="Filter by continent" name="continent"
											 onchange='make_options("continent=" + this.options[this.selectedIndex].value, "country")'>
											<option value="">--select continent--</option>
											<?php
											$arrContinent = get_db_continentname();
											foreach ($arrContinent as $value) {
												# code...
												?>
												<option value="<?php echo $value['ContinentCode'];?>"><?php echo $value['ContinentName'];?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div class="col-sm-4">
									<select id="country" placeholder="Filter by country" name="country"
										onchange='make_options("country=" + this.options[this.selectedIndex].value, "city")'>
										<option value="">--select country--</option>
									</select>
									</div>
									<div class="col-sm-4">
										<select id="city" placeholder="Filter by city" name="city">
											<option value="">--select city--</option>
										</select>
									</div>
									<?php
								}
								?>
							</li>
							<li>
								<input type="number" class="form-control" placeholder="Longitude..." name="longitude" value="<?php echo $longValue;?>"/>
							</li>
							<li>
								<input type="number" class="form-control" placeholder="Latitude..." name="latitude" value="<?php echo $laValue;?>"/>
							</li>
							<li>
								<input type="reset" value="Reset"/>
								<?php
								if (isset($_GET['img']) and $_GET['img']){
									?>
									<input type="submit" name="modify" value="Submit"/>
									<?php
								} else {
									?>
									<input type="submit" value="Submit"/>
									<?php
								}
								?>
							</li>
						</ul>
				</form>
			<?php
		}
		?>
	</section>
    <?php include_once "introduce/footer.php";?>
  </body>
</html>
