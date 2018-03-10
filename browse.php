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
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Browse</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/browseStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script src="js/browseScript.js"></script>
		<script src="js/selector_ajax.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php navbar("Browse", $user_name);?>
    <div id="browse">
			<h1>Find, Beauties of the World</h1>
		</div>
    <div id="row">
      <!-- <section class="floatbar"> -->
			<div class="col-md-3">
      <aside id="continents">
        <p>Continents</p>
        <nav>
        <ul>
					<?php
					$arrContinent = get_db_continentname();
					foreach ($arrContinent as $value) {
						# code...
						?>
						<li><a href="browse.php?<?php echo "continentname=".$value['ContinentCode'];?>"><?php echo $value['ContinentName'];?></a></li>
						<?php
					}
					?>
        </ul>
        </nav>
      </aside>
      <aside id="countris">
        <p>Popular Countries</p>
        <nav>
        <ul>
					<?php
					$arrCountry = get_db_countryname();
					foreach (array_slice($arrCountry, 0, 10, true) as $value) {
						# code...
						?>
						<li><a href="browse.php?<?php echo "countryname=".$value['ISO']."&continentname=".$value['ContinentCode'];?>"><?php echo $value['CountryName'];?></a></li>
						<?php
					}
					?>
        </ul>
        </nav>
      </aside>
			</div>
			<div class="col-md-9">
				<form id="selector" action="browse.php" method="GET">
					<ul>
					<li>
						<select id="continent" placeholder="Filter by continent" name="continentname"
							 onchange='make_options("continent=" + this.options[this.selectedIndex].value, "country")'>
							<option value="">--select continent--</option>
							<?php
							foreach ($arrContinent as $value) {
								# code...
								?>
								<option value="<?php echo $value['ContinentCode'];?>"><?php echo $value['ContinentName'];?></option>
								<?php
							}
							?>
						</select>
					</li>
					<li>
						<select id="country" placeholder="Filter by country" name="countryname"
							onchange='make_options("country=" + this.options[this.selectedIndex].value, "city")'>
							<option value="">--select country--</option>
						</select>
					</li>
					<li>
						<select id="city" placeholder="Filter by city" name="cityname">
							<option value="">--select city--</option>
						</select>
					</li>
					<li>
					<input type="submit" value="submit"/>
					</li>
				</ul>
				</form>

<?php
function make_imgs($arr){
	?>
	<div class="col-md-6">
	<?php
	foreach ($arr as $value) {
		# code...
		?>
		<a href="describe.php?img=<?php echo $value['Path'];?>" class="thumbnail" style="display: inline-block;">
			<img src="image/travel-images/medium/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
			<div><h4><?php echo $value['Title'];?></h4></div>
		</a>
		<?php
	}
	?>
	</div>
	<?php
}
function make_div_imgs($arr){
	$arrOdd  = array();
	$arrEven = array();
	for ($i=0;$i<sizeof($arr,0);$i++) {
		if($i % 2 == 0){
			$arrEven[] = $arr[$i];
		}else{
			$arrOdd[] = $arr[$i];
		}
	}
	?>
	<div class="container">
	<?php
	make_imgs($arrEven);
	make_imgs($arrOdd);
	?>
	</div>
	<?php
}
function make_page_num($page, $curPage, $all){
	if (!$all){
		$continent = $_GET['continentname'];
		$country = $_GET['countryname'];
		$city = $_GET['cityname'];
	}
	if ($page == $curPage) {
		if ($all) {
			?>
			<li class="active"><a href="browse.php?<?php echo "all=Start&page=$page";?>"><?php echo $page;?></a></li>
			<?php
		} else {
			?>
			<li class="active"><a href="browse.php?<?php echo "continentname=$continent&countryname=$country&cityname=$city&page=$page";?>"><?php echo $page;?></a></li>
			<?php
		}
	} else {
		if ($all) {
			?>
			<li><a href="browse.php?<?php echo "all=Start&page=$page";?>"><?php echo $page;?></a></li>
			<?php
		} else {
			?>
			<li><a href="browse.php?<?php echo "continentname=$continent&countryname=$country&cityname=$city&page=$page";?>"><?php echo $page;?></a></li>
			<?php
		}
	}
}
function make_page_nav($arr, $all, $curPage=1){
	if (!$all){
		$continent = $_GET['continentname'];
		$country = $_GET['countryname'];
		$city = $_GET['cityname'];
	}
	$pagenum = (int)((sizeof($arr, 0) - 1)/8) + 1;
	?>
	<div id="page_foot">
	<nav aria-label="Page navigation">
  <ul class="pagination">
    <li>
			<?php
			$bac = 1;
			if (isset($_GET['page']) and $_GET['page'] > 1){
				$bac = $_GET['page']-1;
			}
			?>
			<?php
			if ($all) {
				?>
				<a href="browse.php?<?php echo "all=Start&page=$bac";?>" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
				<?php
			} else {
				?>
				<a href="browse.php?<?php echo "continentname=$continent&countryname=$country&cityname=$city&page=$bac";?>" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
				<?php
			}
			?>
    </li>
		<?php
		if ($pagenum > 7) {
			if ($curPage > 2 and $curPage < $pagenum-1){
				if ($curPage == $pagenum-2){
					make_page_num(1, $curPage, $all);
					make_page_num(2, $curPage, $all);
				}else{
					make_page_num(1, $curPage, $all);
				}
				if ($curPage >= 4){
					if ($curPage == 4){
						make_page_num(2, $curPage, $all);
					} else {
						?>
						<li><a href="#">...</a></li>
						<?php
					}
				}
				for ($i=$curPage-2;$i<(($curPage+1 < $pagenum)?$curPage+1:$pagenum);$i++){
					$page = $i + 1;
					make_page_num($page, $curPage, $all);
				}
				if ($curPage <= ($pagenum-3)){
					if ($curPage == ($pagenum-3)){
						make_page_num($pagenum-1, $curPage, $all);
					} else {
						?>
						<li><a href="#">...</a></li>
						<?php
					}
				}
				if ($curPage == 3){
					for ($i=$pagenum-2;$i<$pagenum;$i++){
						$page = $i + 1;
						make_page_num($page, $curPage, $all);
					}
				} else {
					make_page_num($pagenum, $curPage, $all);
				}
			} else {
				for ($i=0;$i<3;$i++){
					$page = $i + 1;
					make_page_num($page, $curPage, $all);
				}
				?>
				<li><a href="#">...</a></li>
				<?php
				for ($i=$pagenum-3;$i<$pagenum;$i++){
					$page = $i + 1;
					make_page_num($page, $curPage, $all);
				}
			}
		} else {
			for ($i=0;$i<$pagenum;$i++){
				$page = $i + 1;
				make_page_num($page, $curPage, $all);
			}
		}
		?>
    <li>
			<?php
			$pre = 2;
			if (isset($_GET['page']) and $_GET['page']){
				if ($_GET['page'] == $pagenum){
					$pre = $_GET['page'];
				} else {
					$pre = $_GET['page']+1;
				}
			}
			?>
			<?php
			if ($all) {
				?>
				<a href="browse.php?<?php echo "all=Start&page=$pre";?>" aria-label="Previous">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
				<?php
			} else {
				?>
				<a href="browse.php?<?php echo "continentname=$continent&countryname=$country&cityname=$city&page=$pre";?>" aria-label="Previous">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
				<?php
			}
			?>
    </li>
  </ul>
	</nav>
	</div>
	<?php
}
?>
			<?php
			if(isset($_GET["continentname"]) and $_GET["continentname"]){
				if(isset($_GET["countryname"]) and $_GET["countryname"]){
					if(isset($_GET["cityname"]) and $_GET["cityname"]){
						$cityImg = get_db_city($_GET["cityname"], true);
						if(sizeof($cityImg, 0) == 0){
							?>
							<div class="alert alert-danger" role="alert">
								<h3><b>Oops!</b> There is nothing...</h3>
								<p>TRY AGAIN PLEASE.</p>
							</div>
							<?php
						}else{
							if(isset($_GET['page']) and $_GET['page']){
								make_div_imgs(get_db_city($_GET["cityname"], false, $_GET['page']));
							} else {
								make_div_imgs(get_db_city($_GET["cityname"], false));
							}
						}
						if(sizeof($cityImg, 0) > 6){
							if(isset($_GET['page']) and $_GET['page']){
								make_page_nav($cityImg, false, $_GET['page']);
							}else{
								make_page_nav($cityImg, false);
							}
						}
					}else{
						$countryImg = get_db_country($_GET["countryname"], true);
						if(sizeof($countryImg, 0) == 0){
							?>
							<div class="alert alert-danger" role="alert">
								<h3><b>Oops!</b> There is nothing...</h3>
								<p>TRY AGAIN PLEASE.</p>
							</div>
							<?php
						}else{
							if(isset($_GET['page']) and $_GET['page']){
								make_div_imgs(get_db_country($_GET["countryname"], false, $_GET['page']));
							} else {
								make_div_imgs(get_db_country($_GET["countryname"], false));
							}
							if(sizeof($countryImg, 0) >= 8){
								if(isset($_GET['page']) and $_GET['page']){
									make_page_nav($countryImg, false, $_GET['page']);
								}else{
									make_page_nav($countryImg, false);
								}
							}
						}
					}
				}else{
					$continentImg = get_db_continent($_GET["continentname"], true);
					if(sizeof($continentImg, 0) == 0){
						?>
						<div class="alert alert-danger" role="alert">
							<h3><b>Oops!</b> There is nothing...</h3>
							<p>TRY AGAIN PLEASE.</p>
						</div>
						<?php
					}else{
						if(isset($_GET['page']) and $_GET['page']){
							make_div_imgs(get_db_continent($_GET["continentname"], false, $_GET['page']));
						} else {
							make_div_imgs(get_db_continent($_GET["continentname"], false));
						}
					}
					if(sizeof($continentImg, 0) >= 8){
						if(isset($_GET['page']) and $_GET['page']){
							make_page_nav($continentImg, false, $_GET['page']);
						}else{
							make_page_nav($continentImg, false);
						}
					}
				}
			}else{
				if (isset($_GET['all']) and $_GET['all'] == "Start"){
					$arrImg = get_db_most_pop(0, 90);
					if(sizeof($arrImg, 0) == 0){
						?>
						<div class="alert alert-danger" role="alert">
							<h3><b>Oops!</b> There is nothing...</h3>
							<p>TRY AGAIN PLEASE.</p>
						</div>
						<?php
					}else{
						if(isset($_GET['page']) and $_GET['page']){
							make_div_imgs(get_db_most_pop(($_GET['page']-1)*8, 8));
						} else {
							make_div_imgs(get_db_most_pop(0, 8));
						}
					}
					if(sizeof($arrImg, 0) >= 8){
						if(isset($_GET['page']) and $_GET['page']){
							make_page_nav($arrImg, true, $_GET['page']);
						}else{
							make_page_nav($arrImg, true);
						}
					}
				} else {
					?>
					<div class="jumbotron">
						<h1>Hello, Browse!</h1>
						<p>Welcome to browse the photos of you wanted.</p>
						<form action="browse.php" method="GET">
							<input class="btn" type="submit" name="all" value="Start"/>
						</form>
					</div>
					<?php
				}
			}
			?>
		</div>
    </div>
    <?php include_once "introduce/footer.php";?>
  </body>
</html>
