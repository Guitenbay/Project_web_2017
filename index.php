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
	$arrMost = get_db_most_pop(0,6);
	$arrNew = get_db_new();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Home</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/homeStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/homeScript.js"></script>
		<script src="js/changeImg.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php navbar("Home", $user_name);?>
    <section class="image">
			<div id="mediumWall">
			<button id="back" onclick="back()"><img src="image/arrow-L.png" /></button>
			<?php
			for($i=0; $i<4; $i++){
				?>
				<a id="<?php echo "medium$i";?>" href="<?php echo "describe.php?img={$arrMost[$i]['Path']}";?>"><img id="medium" src="<?php echo "image/travel-images/large/{$arrMost[$i]['Path']}";?>"/></a>
				<?php
			}
			?>
			<button id="pre" onclick="pre()"><img src="image/arrow-R.png" /></button>
			</div>
			<h2>PIXBaR, Your OWN Gallery</h2>
			<div id="changebar">
			<ul>
				<li id="slide0" onclick="pointChange(0)"></li>
				<li id="slide1" onclick="pointChange(1)"></li>
				<li id="slide2" onclick="pointChange(2)"></li>
				<li id="slide3" onclick="pointChange(3)"></li>
			</ul>
			</div>
		</section>
<?php
function make_imgRow($arr){
  ?>
  <div class="row">
  <?php
  foreach ($arr as $value) {
    # code...
    ?>
    <div class="col-xs-6 col-md-4">
      <a href="describe.php?img=<?php echo $value['Path'];?>" class="thumbnail">
        <img class="img-responsive" src="image/travel-images/medium/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
        <div class="caption">
          <h3><?php echo $value['Title'];?></h3>
          <p><?php echo $value['Description'];?></p>
        </div>
      </a>
    </div>
    <?php
  }
  ?>
  </div>
  <?php
}
function make_new_img($value){
	?>
	<a href="describe.php?img=<?php echo $value['Path'];?>"><img style="height:250px;" src="image/travel-images/large/<?php echo $value['Path'];?>"></a>
	<?php
}
?>
<section>
	<div class="head">
		<h2 class="title_headline">Most Popular Photos</h2>
		<p>The most popular photos of the last days.</p>
		<button id="changebtn" type="button" aria-label="Left Align">
  		<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
			Change
		</button>
	</div>
	<div class="container" id="most">
		<div class="row">
		<?php
		$arr1 = array();
		$arr2 = array();
		for($i=0;$i<6;$i++){
			if($i < 3){
				$arr1[] = $arrMost[$i];
			}else{
				$arr2[] = $arrMost[$i];
			}
		}
		make_imgRow($arr1);
		make_imgRow($arr2);
		?>
		</div>
	</div>
</section>
<hr/>
<section>
	<div class="head">
		<h2 class="title_headline">New Stock Photos</h2>
		<p>Daily 20 new high-quality photos.</p>
		<button id="newbtn" type="button" aria-label="Left Align">
  		<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
			New
		</button>
	</div>
	<section id="newWall">
		<?php
		foreach ($arrNew as $value) {
			# code...
			make_new_img($value);
		}
		?>
	</section>
</section>
  <?php include 'introduce/footer.php';?>
  </body>
</html>
