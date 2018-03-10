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
function searchResult($img_num, $search_result="Search for Photos..."){
	// global $arrPath;
	// global $arrTitle;
	?>
	<div class="head">
		<h2 class="title_headline"><?php echo $search_result;?></h2>
	</div>
	<?php
	$arrMost = get_db_most_pop(0, 10);
	foreach ($arrMost as $value) {
		# code...
		?>
		<a href="describe.php?img=<?php echo $value['Path'];?>" class="thumbnail">
			<img style="height:300px;"src="image/travel-images/large/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
			<div>
				<h4><?php echo $value['Title'];?></h4>
			</div>
			<div>
				<?php
				if ($value['Description'] == ""){
					?>
					<p>no description.</p>
					<?php
				} else {
					?>
					<p><?php echo $value['Description']?></p>
					<?php
				}
				?>
			</div>
		</a>
		<?php
	}
}?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>PIXBaR--Seach</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/searchStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/searchScript.js"></script>
	</head>
	<body onscroll="navbarTop()">
    <?php navbar("Search", $user_name);?>
    <header id="search">
			<div class="l_container">
				<h1>PIXBaR</h1>
				<form class="huge_search" action="search.php" method="GET">
					<span class="input-radio">
						<input type="radio" name="searchChoose" checked="true" value="pictures" />
					</span>
					<input id="huge_search_row" placeholder="Search for free photos..." type="search" name="title" />
					<br/>
					<span class="input-radio input-area">
						<input type="radio" name="searchChoose" value="introduce" />
					</span>
					<textarea id="huge_search_area" placeholder="Search for free photos through the description..." type="search" name="detail"></textarea>
					<input type="image" id="huge_search_button" src="image/search.png" alt="Submit" />
				</form>
			</div>
		</header>
    <section id="main">
			<?php
			if (isset($_GET['searchChoose']) and $_GET['searchChoose']) {
				$search_title = $search_describe = "";
				if ($_GET['searchChoose'] == 'pictures') {
					if (isset($_GET['title']) and $_GET['title']) {
						$search_title = $_GET['title'];
						$arrPic = get_db_pictures($_GET['title']);
						?>
						<div class="head">
							<h2 class="title_headline">Photos from Title : <?php echo $search_title;?>.</h2>
						</div>
						<?php
						if (sizeof($arrPic) == 0) {
							?>
							<div class="alert alert-danger" role="alert">
								<h3>Oops!</h3>
								<p>We are SO sorry about what you wanna is nothing.</p>
							</div>
							<?php
						} else {
							foreach ($arrPic as $value) {
								# code...
								?>
								<a href="describe.php?img=<?php echo $value['Path'];?>" class="thumbnail">
			      			<img style="height:300px;"src="image/travel-images/large/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
									<div>
										<h4><?php echo $value['Title'];?></h4>
									</div>
									<div>
										<?php
										if ($value['Description'] == ""){
											?>
											<p>no description.</p>
											<?php
										} else {
											?>
											<p><?php echo $value['Description']?></p>
											<?php
										}
										?>
									</div>
			    			</a>
								<?php
							}
						}
					} else {
						searchResult(0);
					}
				} else {
					if (isset($_GET['detail']) and $_GET['detail']) {
						$search_describe = $_GET['detail'];
						$arrPic = get_db_detail($_GET['detail']);
						?>
						<div class="head">
							<h2 class="title_headline">Photos from Description : <?php echo $search_describe;?>.</h2>
						</div>
						<?php
						if (sizeof($arrPic) == 0) {
							?>
							<div class="alert alert-danger" role="alert">
								<h3>Oops!</h3>
								<p>We are SO sorry about what you wanna is nothing.</p>
							</div>
							<?php
						} else {
							foreach ($arrPic as $value) {
								# code...
								?>
								<a href="describe.php?img=<?php echo $value['Path'];?>" class="thumbnail">
			      			<img style="height:300px;"src="image/travel-images/large/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
									<div>
										<h4><?php echo $value['Title'];?></h4>
									</div>
									<div>
										<?php
										if ($value['Description'] == ""){
											?>
											<p>no description.</p>
											<?php
										} else {
											?>
											<p><?php echo $value['Description']?></p>
											<?php
										}
										?>
									</div>
			    			</a>
								<?php
							}
						}
					} else {
						searchResult(0);
					}
				}
			} elseif (isset($_GET['search']) and $_GET['search']) {
				$arrPic = get_db_pictures($_GET['search']);
				?>
				<div class="head">
					<h2 class="title_headline">Photos from Title.</h2>
				</div>
				<?php
				if (sizeof($arrPic) == 0) {
					?>
					<div class="alert alert-danger" role="alert">
						<h3>Oops!</h3>
						<p>We are SO sorry about what you wanna is nothing.</p>
					</div>
					<?php
				} else {
					foreach ($arrPic as $value) {
						# code...
						?>
						<a href="describe.php?img=<?php echo $value['Path'];?>" class="thumbnail">
							<img style="height:300px;"src="image/travel-images/large/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
							<div>
								<h4><?php echo $value['Title'];?></h4>
							</div>
							<div>
								<?php
								if ($value['Description'] == ""){
									?>
									<p>no description.</p>
									<?php
								} else {
									?>
									<p><?php echo $value['Description']?></p>
									<?php
								}
								?>
							</div>
						</a>
						<?php
					}
				}
			} else {
				searchResult(0);
			}

			?>
    </section>
    <?php include_once "introduce/footer.php";?>
  </body>
</html>
