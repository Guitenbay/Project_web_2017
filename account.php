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
	function make_page_num($page, $curPage, $section){
		if ($page == $curPage) {
			if (isset($_GET['UID']) and $_GET['UID']){
				?>
				<li class="active"><a href="account.php?<?php echo "UID={$_GET['UID']}&section=$section&page=$page";?>"><?php echo $page;?></a></li>
				<?php
			} else {
				?>
				<li class="active"><a href="account.php?<?php echo "section=$section&page=$page";?>"><?php echo $page;?></a></li>
				<?php
			}
		} else {
			if (isset($_GET['UID']) and $_GET['UID']){
				?>
				<li><a href="account.php?<?php echo "UID={$_GET['UID']}&section=$section&page=$page";?>"><?php echo $page;?></a></li>
				<?php
			} else {
				?>
				<li><a href="account.php?<?php echo "section=$section&page=$page";?>"><?php echo $page;?></a></li>
				<?php
			}
		}
	}
	function make_page_nav($arr, $curPage=1){
		$pagenum = (int)((sizeof($arr, 0) - 1)/12) + 1;
		$section = $_GET['section'];
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
				if (isset($_GET['UID']) and $_GET['UID']){
					?>
					<a href="account.php?<?php echo "UID={$_GET['UID']}&section=$section&page=$bac";?>" aria-label="Previous">
					<?php
				} else {
					?>
					<a href="account.php?<?php echo "section=$section&page=$bac";?>" aria-label="Previous">
					<?php
				}
				?>
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
			<?php
			if ($pagenum > 7) {
				if ($curPage > 2 and $curPage < $pagenum-2){
					if ($curPage == $pagenum-2){
						make_page_num(1, $curPage, $section);
						make_page_num(2, $curPage, $section);
					}else{
						make_page_num(1, $curPage, $section);
					}
					if ($curPage >= 4){
						if ($curPage == 4){
							make_page_num(2, $curPage, $section);
						} else {
							?>
							<li><a href="#">...</a></li>
							<?php
						}
					}
					for ($i=$curPage-2;$i<(($curPage+1 < $pagenum)?$curPage+1:$pagenum);$i++){
						$page = $i + 1;
						make_page_num($page, $curPage, $section);
					}
					if ($curPage <= ($pagenum-3)){
						if ($curPage == ($pagenum-3)){
							make_page_num($pagenum-1, $curPage, $section);
						} else {
							?>
							<li><a href="#">...</a></li>
							<?php
						}
					}
					if ($curPage == 3){
						make_page_num($pagenum-1, $curPage, $section);
						make_page_num($pagenum, $curPage, $section);
					} else {
						make_page_num($pagenum, $curPage, $section);
					}
				} else {
					for ($i=0;$i<3;$i++){
						$page = $i + 1;
						make_page_num($page, $curPage, $section);
					}
					?>
					<li><a href="#">...</a></li>
					<?php
					for ($i=$pagenum-3;$i<$pagenum;$i++){
						$page = $i + 1;
						make_page_num($page, $curPage, $section);
					}
				}
			} else {
				for ($i=0;$i<$pagenum;$i++){
					$page = $i + 1;
					make_page_num($page, $curPage, $section);
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
				if (isset($_GET['UID']) and $_GET['UID']){
					?>
					<a href="account.php?<?php echo "UID={$_GET['UID']}&section=$section&page=$pre";?>" aria-label="Previous">
					<?php
				} else {
					?>
					<a href="account.php?<?php echo "section=$section&page=$pre";?>" aria-label="Previous">
					<?php
				}
				?>
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
		</nav>
		</div>
		<?php
	}
	function setMyPhotos($value, $user){
		global $user_name;
	  ?>
	  <div class="photo_container">
	    <div class="thumbnail">
				<a href="describe.php?img=<?php echo $value['Path'];?>">
	        <img src="image/travel-images/medium/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
				</a>
				<div class="caption">
					<a href="describe.php?img=<?php echo $value['Path'];?>">
						<h4><?php echo $value['Title'];?></h4>
					</a>
				<?php
				if ($user_name == $user){
					?>
					<a href="upload.php?img=<?php echo $value['Path'];?>" class="btn btn-primary" role="button">Edit</a>
					<button class="btn btn-default" onclick="deletePhoto('<?php echo $user?>','<?php echo $value['ImageID'];?>')">Delete</button>
					<?php
				} else {
					?>
					<a href="#" class="btn btn-primary" role="button" >Edit</a>
					<button class="btn btn-disabled" disabled>Delete</button>
					<?php
				}
				?>
				</div>
	    </div>
	  </div>
	  <?php
	}
	function setMyFavorite($value, $user){
		global $user_name;
		?>
	  <div class="photo_container">
	    <div class="thumbnail">
				<a href="describe.php?img=<?php echo $value['Path'];?>">
	        <img src="image/travel-images/medium/<?php echo $value['Path'];?>" alt="<?php echo $value['Title'];?>">
				</a>
				<div class="caption">
					<a href="describe.php?img=<?php echo $value['Path'];?>">
						<h4><?php echo $value['Title'];?></h4>
					</a>
				<div class="heart">
					<span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
				</div>
				<?php
				if ($user_name == $user){
					?>
					<button class="btn btn-default" onclick="deleteFavor('<?php echo $user?>','<?php echo $value['ImageID'];?>')">Delete</button>
					<?php
				} else {
					?>
					<button class="btn btn-disabled" disabled>Delete</button>
					<?php
				}
				?>
				</div>
	    </div>
	  </div>
	  <?php
	}
	function setImgs($str){
		$arr = get_db_img($str);
		if (empty($arr)){
			?>
			<h4>No photos</h4>
			<?php
		} else {
			foreach ($arr as $value) {
				# code...
				?>
				<a href="describe.php?img=<?php echo $value['Path'];?>" style="display: inline-block;">
					<img src="image/travel-images/square-medium/<?php echo $value['Path'];?>">
				</a>
				<?php
			}
		}
	}
	function setMyFollower($value){
		?>
		<div class="follow">
			<ul>
				<li>
					<a href="account.php?UID=<?php echo $value['UIDFollowing'];?>">
					<img class="userimg" src="image/usr/<?php
	        if ($value['UID'] >= '32') {
	          echo $value['UID'];
	        } else {
	          echo '未登录';
	        }?>.jpg"/>
					</a>
					<p><?php echo $value['FirstName']." ".$value['LastName'];?></p>
				</li>
				<li>
					<?php
					setImgs($value['UIDFollowing']);
					?>
				</li>
			</ul>
		</div>
		<?php
	}
	function setMyFollowed($value, $user){
		global $user_name;
		?>
		<div class="follow">
			<ul>
				<li>
					<a href="account.php?UID=<?php echo $value['UID'];?>">
					<img class="userimg" src="image/usr/<?php
	        if ($value['UID'] >= '32') {
	          echo $value['UID'];
	        } else {
	          echo '未登录';
	        }?>.jpg"/>
					</a>
					<p><?php echo $value['FirstName']." ".$value['LastName'];?></p>
				</li>
				<li>
					<?php
					setImgs($value['UID']);
					?>
				</li>
				<li>
					<?php
					if ($user_name == $user){
						?>
						<button class="btn btn-danger"  onclick="unfollow('<?php echo $user;?>', '<?php echo $value['UID'];?>')">Unfollow</button>
						<?php
					} else {
						?>
						<button class="btn btn-disabled" disabled>Unfollow</button>
						<?php
					}
					?>
				</li>
			</ul>
		</div>
		<?php
	}
	function make_mycontent($str){
		global $user_name;
		$allmy = get_db_myPhotos($str, true);
		$allfavor = get_db_favor($str, true);
		$allfollow = get_db_follow($str, true);
		$allfollowed = get_db_followed($str, true);
		$followid = get_db_uid($user_name);
		$uid = get_db_uid($str);
		?>
		<div class="mycontent">
			<ul>
				<li><a href="account.php?UID=<?php echo $uid."&";?>section=Followed"><?php echo sizeof($allfollowed)." ";?>Followed</a></li>
				<li><a href="account.php?UID=<?php echo $uid."&";?>section=Followers"><?php echo sizeof($allfollow)." ";?>Followers</a></li>
				<li><a href="account.php?UID=<?php echo $uid."&";?>section=Photos"><?php echo sizeof($allmy)." ";?>Photos</a></li>
				<li><a href="account.php?UID=<?php echo $uid."&";?>section=Favorites"><?php echo sizeof($allfavor)." ";?>Favorites</a></li>
			</ul>
			<div id="followbtn">
			<?php
			if ($str != $user_name){
				$followed = false;
				$userFollowed = get_db_followed($user_name, true);
				foreach ($userFollowed as $value) {
					# code...
					if ($value['UID'] == $uid){
						$followed = true;
						?>
						<button style="margin-top:10px;" class="btn btn-disabled" disabled="disabled">Followed</button>
						<?php
					}
				}
				if (!$followed){
					?>
					<button style="margin-top:10px;" class="btn btn-warning" onclick="account_follow(<?php echo "$uid, $followid, 'followbtn'"?>)">+ Follow</button>
					<?php
				}
			}
			?>
			</div>
			<hr/>
		</div>
		<?php
	}
?>
<?php
function make_account($str, $uid=""){
	global $user_name;
	$allmy = get_db_myPhotos($str, true);
	$allfavor = get_db_favor($str, true);
	$allfollow = get_db_follow($str, true);
	$allfollowed = get_db_followed($str, true);
	if (isset($_GET['page']) and $_GET['page']){
		$arrMy = get_db_myPhotos($str, false, $_GET['page']);
		$arrFavor = get_db_favor($str, false, $_GET['page']);
		$arrFollow = get_db_follow($str, false, $_GET['page']);
		$arrFollowed = get_db_followed($str, false, $_GET['page']);
	} else {
		$arrMy = get_db_myPhotos($str, false);
		$arrFavor = get_db_favor($str, false);
		$arrFollow = get_db_follow($str, false);
		$arrFollowed = get_db_followed($str, false);
	}
	$arrInfo = get_db_myInfo($str);
	$followid = get_db_uid($user_name);
	?>
	<section id="my">
	<h2><?php echo $arrInfo[0]['FirstName'].' '.$arrInfo[0]['LastName'];?></h2>
	<p>Please leave your signature</p>
	</section>
	<section id='myphoto'>
		<?php
		if (isset($_GET['section']) and $_GET['section']){
			make_mycontent($str);
		  if ($_GET['section'] == 'Favorites'){
		    ?>
		    <h2>My Favorite<?php echo " (".sizeof($allfavor).")";?></h2>
		    <div>
		    <?php
		    if (sizeof($allfavor) != 0){
		      foreach ($arrFavor as $value) {
		        # code...
		        setMyFavorite($value, $str);
		      }
		    } else {
					?>
					<div class="jumbotron">
						<h1>NO FAVORITES!</h1>
						<p>What you wanna is unexisted.</p>
						<p><a class="btn btn-primary btn-lg" href="#" role="button">NONE~ ╮(￣▽￣)╭</a></p>
					</div>
					<?php
		    }
				if (sizeof($allfavor, 0) > 12){
					if (isset($_GET['page']) and $_GET['page']){
						make_page_nav($allfavor, $_GET['page']);
					} else {
						make_page_nav($allfavor);
					}
				}
		    ?>
		    </div>
		    <?php
		  } elseif ($_GET['section'] == "Photos"){
		    ?>
		    <h2>My Photos<?php echo " (".sizeof($allmy).")";?></h2>
		    <div>
		      <?php
		      if (sizeof($allmy) != 0){
		        foreach ($arrMy as $value) {
		          # code...
		          setMyPhotos($value, $str);
		        }
		      } else {
						?>
			      <div class="jumbotron">
			        <h1>NO PHOTOS!</h1>
			        <p>What you wanna is unexisted.</p>
			        <p><a class="btn btn-primary btn-lg" href="#" role="button">NONE~ ╮(￣▽￣)╭</a></p>
			      </div>
			      <?php
		      }
					if (sizeof($allmy, 0) > 12){
						if (isset($_GET['page']) and $_GET['page']){
							make_page_nav($allmy, $_GET['page']);
						} else {
							make_page_nav($allmy);
						}
					}
		      ?>
		    </div>
		    <?php
		  } elseif ($_GET['section'] == "Followers"){
		    ?>
		    <h2>My Followers<?php echo " (".sizeof($allfollow).")";?></h2>
		    <div>
		      <?php
		      if (sizeof($allfollow) != 0){
		        foreach ($arrFollow as $value) {
		          # code...
		          setMyFollower($value);
		        }
		      } else {
						?>
			      <div class="jumbotron">
			        <h1>NO FOLLOWERS!</h1>
			        <p>What you wanna is unexisted.</p>
			        <p><a class="btn btn-primary btn-lg" href="#" role="button">NONE~ ╮(￣▽￣)╭</a></p>
			      </div>
			      <?php
		      }
					if (sizeof($allfollow, 0) > 9){
						if (isset($_GET['page']) and $_GET['page']){
							make_page_nav($allfollow, $_GET['page']);
						} else {
							make_page_nav($allfollow);
						}
					}
		      ?>
		    </div>
		    <?php
		  } elseif ($_GET['section'] == "Followed"){
		    ?>
		    <h2>My Followed<?php echo " (".sizeof($allfollowed).")";?></h2>
		    <div>
		      <?php
		      if (sizeof($allfollowed) != 0){
		        foreach ($arrFollowed as $value) {
		          # code...
		          setMyFollowed($value, $str);
		        }
		      } else {
						?>
			      <div class="jumbotron">
			        <h1>NO FOLLOWED!</h1>
			        <p>What you wanna is unexisted.</p>
			        <p><a class="btn btn-primary btn-lg" href="#" role="button">NONE~ ╮(￣▽￣)╭</a></p>
			      </div>
			      <?php
		      }
					if (sizeof($allfollowed, 0) > 12){
						if (isset($_GET['page']) and $_GET['page']){
							make_page_nav($allfollowed, $_GET['page']);
						} else {
							make_page_nav($allfollowed);
						}
					}
		      ?>
		    </div>
		    <?php
		  }
		} else {
			make_mycontent($str);
			?>
			<h2>My Photos<?php echo " (".sizeof($allmy).")";?></h2>
			<div>
				<?php
				if (sizeof($allmy) != 0){
					foreach ($arrMy as $value) {
						# code...
						setMyPhotos($value, $str);
					}
				} else {
					?>
					<div class="jumbotron">
						<h1>NO PHOTOS!</h1>
						<p>What you wanna is unexisted.</p>
						<p><a class="btn btn-primary btn-lg" href="#" role="button">NONE~ ╮(￣▽￣)╭</a></p>
					</div>
					<?php
				}
				if (sizeof($allmy, 0) > 12){
					if (isset($_GET['page']) and $_GET['page']){
						make_page_nav($allmy, $_GET['page']);
					} else {
						make_page_nav($allmy);
					}
				}
				?>
			</div>
			<?php
		}
		?>
	</section>
	<?php
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PIXBaR--Account</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/base.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="css/myAccountStyle.css" rel="stylesheet" type="text/css" media="all"/>
		<script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script src="js/myAccountScript.js"></script>
		<script src="js/account_section.js"></script>
		<script src="js/account_follow.js"></script>
	</head>
	<body onscroll="topButton()">
    <?php navbar("", $user_name);?>
    <div id="myAccount">
			<h1>Look, you are photographer</h1>
		</div>
    <?php
		if (isset($_GET['UID']) and $_GET['UID']){
			$username = get_db_username($_GET['UID']);
			if ($username == $user_name) {
				make_account($user_name);
			} else {
				make_account($username, $_GET['UID']);
			}
		} elseif ($user_name != ''){
			make_account($user_name);
    } else {
			?>
      <div class="jumbotron">
        <h1>NO LOGIN!</h1>
        <p>What you wanna is unexisted.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Return</a></p>
      </div>
      <div style="height:300px;"></div>
      <?php
		}
    ?>
    <?php include_once "introduce/footer.php";?>
  </body>
</html>
