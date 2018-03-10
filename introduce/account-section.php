<?php
	session_set_cookie_params(36000);//10h
	session_start();
	include_once "img_data.php";
	include_once "image_processor.php";
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
			<li><a href="account.php?section=Followed"><?php echo sizeof($allfollowed)." ";?>Followed</a></li>
			<li><a href="account.php?section=Followers"><?php echo sizeof($allfollow)." ";?>Followers</a></li>
			<li><a href="account.php?section=Photos"><?php echo sizeof($allmy)." ";?>Photos</a></li>
			<li><a href="account.php?section=Favorites"><?php echo sizeof($allfavor)." ";?>Favorites</a></li>
		</ul>
		<div id="followbtn">
		<?php
		if ($str != $user_name){
			$followed = false;
			$userFollowed = get_db_followed($user_name);
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
?>
<?php
if (isset($_GET['section']) and $_GET['section'] and isset($_GET['user']) and $_GET['user']){
  if ($_GET['section'] == 'Favorites'){
		if (isset($_GET['userImgid']) and $_GET['userImgid']){
			$uid = get_db_uid($_GET['user']);
			$userFavorid = $_GET['userImgid'];
			$conn = connect_db();
			$sql = "DELETE FROM travelimagefavor WHERE UID = $uid and ImageID = $userFavorid";
			if (!$result = $conn->query($sql)){
				die('Delete has wrong：'.mysqli_error($conn));
			}
		}
		$allfavor = get_db_favor($_GET['user'], true);
		if (isset($_GET['page']) and $_GET['page']){
			$arrFavor = get_db_favor($_GET['user'], false, $_GET['page']);
		} else {
			$arrFavor = get_db_favor($_GET['user'], false);
		}
		make_mycontent($_GET['user']);
    ?>
    <h2>My Favorite<?php echo " (".sizeof($allfavor).")";?></h2>
    <div>
    <?php
    if (sizeof($allfavor) != 0){
      foreach ($arrFavor as $value) {
        # code...
        setMyFavorite($value, $_GET['user']);
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
		if (isset($_GET['userImgid']) and $_GET['userImgid']){
			$uid = get_db_uid($_GET['user']);
			$userPhotoid = $_GET['userImgid'];
			$info = get_db_path($userPhotoid);
			$conn = connect_db();
			$sql1 = "DELETE FROM travelimage WHERE UID = $uid and ImageID = $userPhotoid";
			$sql2 = "DELETE FROM travelimagedetails WHERE ImageID = $userPhotoid";
			$sql3 = "DELETE FROM travelimagefavor WHERE ImageID = $userPhotoid";
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$result3 = $conn->query($sql3);
			if (!$result1 or !$result2 or !$result3){
				die('Delete has wrong：'.mysqli_error($conn));
			} else {
				delete_image_file($info[0]['Path']);
			}
		}
		$allmy = get_db_myPhotos($_GET['user'], true);
		if (isset($_GET['page']) and $_GET['page']){
			$arrMy = get_db_myPhotos($_GET['user'], false, $_GET['page']);
		} else {
			$arrMy = get_db_myPhotos($_GET['user'], false);
		}
		make_mycontent($_GET['user']);
    ?>
    <h2>My Photos<?php echo " (".sizeof($allmy).")";?></h2>
    <div>
      <?php
      if (sizeof($allmy) != 0){
        foreach ($arrMy as $value) {
          # code...
          setMyPhotos($value, $_GET['user']);
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
		$allfollow = get_db_follow($_GET['user'], true);
		if (isset($_GET['page']) and $_GET['page']){
			$arrFollow = get_db_follow($_GET['user'], false, $_GET['page']);
		} else {
			$arrFollow = get_db_follow($_GET['user'], false);
		}
		make_mycontent($_GET['user']);
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
		if (isset($_GET['userBeFollow']) and $_GET['userBeFollow']){
			$uid = get_db_uid($_GET['user']);
			$userBeFollow = $_GET['userBeFollow'];
			$conn = connect_db();
			$sql = "DELETE FROM traveluserfollowing WHERE UID = $userBeFollow and UIDFollowing = $uid";
			if (!$result = $conn->query($sql)){
				die('Delete has wrong：'.mysqli_error($conn));
			}
		}
		$allfollowed = get_db_followed($_GET['user'], true);
		if (isset($_GET['page']) and $_GET['page']){
			$arrFollowed = get_db_followed($_GET['user'], false, $_GET['page']);
		} else {
			$arrFollowed = get_db_followed($_GET['user'], false);
		}
		make_mycontent($_GET['user']);
    ?>
    <h2>My Followed<?php echo " (".sizeof($allfollowed).")";?></h2>
    <div>
      <?php
      if (sizeof($allfollowed) != 0){
        foreach ($arrFollowed as $value) {
          # code...
          setMyFollowed($value, $_GET['user']);
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
}
?>
