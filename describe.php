<?php
	session_set_cookie_params(36000);//10h
	session_start();
  include_once "introduce/img_data.php";
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
  function describeImg($arr){
		global $user_name;
    $describe = $arr[0];
    $path = $describe['Path'];
		$uid = get_db_uid($user_name);
		$arrFavor = get_db_imgfavor($path);
    $img_info = getimagesize("image/travel-images/large/$path");
    $style = "";
    if ($img_info[0] >= $img_info[1]){
      $style = 'max-width:100%;';
    } else {
      $style = 'max-height:780px;';
    }
    ?>
    <div class="entry">
      <!-- 6119130918.jpg -->
			<a id="detailImg" href="image/travel-images/large/<?php echo $describe['Path'];?>">
        <img style=<?php echo $style;?> src="image/travel-images/large/<?php echo $describe['Path'];?>" alt="photo" />
      </a>
			<div>
				<div id="addFavor">
				<?php
				if ($user_name == ''){
					?>
					<button class="disabled" disabled="disabled">Add to Favorite</button>
					<?php
				} else {
					$added = false;
					foreach ($arrFavor as $value) {
						# code...
						if ($value['UID'] == $uid){
							$added = true;
							?>
							<button onclick="delete_favor(<?php echo $uid.','.$describe['ImageID'].',\''.$path.'\'';?>, 'addFavor')">Added</button>
							<?php
						}
					}
					if (!$added){
						?>
						<button onclick="add_favor(<?php echo $uid.','.$describe['ImageID'].',\''.$path.'\'';?>, 'addFavor')">Add to Favorite</button>
						<?php
					}
				}
				?>
				<div class="aside">
					<p>Favorite</p>
					<ul>
						<li class="number"><?php echo sizeof($arrFavor);?></li>
					</ul>
				</div>
				</div>
				<div class="aside">
					<p>Details</p>
					<ul>
						<li>Conutry: <?php echo $describe['CountryName'];?></li>
						<li>City: <?php echo $describe['AsciiName'];?></li>
						<li>Latitude: <?php echo $describe['latitude'];?></li>
						<li>Longitude: <?php echo $describe['Longitude'];?></li>
					</ul>
				</div>
				<section id="from">
          <div>
            <a id="img_account" href="account.php?UID=<?php echo $describe['UID'];?>"><img src="image/usr/<?php
              if ($describe['Headpath'] != "") {
                echo $describe['Headpath'];
              } else {
                echo '未登录.jpg';
              }?>" alt="artor" /></a>
            <hr/>
            <a href="account.php?UID=<?php echo $describe['UID'];?>"><em>from: <?php echo $describe['FirstName'].' '.$describe['LastName'];?></em></a>
          </div>
				</section>
				<p>Free for personal and commercial use</p>
				<p>No attribution required</p>
			</div>
		</div>
		<div id="detail">
			<h2><?php echo $describe['Title'];?></h2>
			<p><?php
      if ($describe['Description']){
        echo $describe['Description'];
      }else{
        echo "<em>"."Oops!**There is nothing about Description...**"."</em>";
      }
      ?></p>
		</div>
    <?php
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Describe</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/describeStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script src="js/navbarScript.js"></script>
		<script src="js/add_favor_ajax.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php
    navbar("", $user_name);
    ?>
    <section id="describe">
    <?php
		$img_path = "";
    if (isset($_GET['img']) and $_GET['img']){
			$img_path = $_GET['img'];
		}
		$arr = get_db_describe($img_path);
		if (sizeof($arr) != 0){
			describeImg($arr);
		}else{
      ?>
      <div class="jumbotron">
        <h1>NO IMAGE!</h1>
        <p>What you wanna is unexisted.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">NONE~ ╮(￣▽￣)╭</a></p>
      </div>
      <div style="height:300px;"></div>
      <?php
    }
    ?>
		</section>
    <?php include_once "introduce/footer.php";?>
  </body>
</html>
