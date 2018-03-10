<?php
	include_once "img_data.php";
?>
<?php
	if (isset($_GET['UID']) and $_GET['UID']
    and isset($_GET['ImgID']) and $_GET['ImgID']
		and isset($_GET['Path']) and $_GET['Path']){
    $uid = $_GET['UID'];
    $imgid = $_GET['ImgID'];
    $conn = connect_db();
		if (isset($_GET['delete']) and $_GET['delete'] == 'true'){
			$sql = "DELETE FROM travelimagefavor WHERE UID = $uid and ImageID = $imgid";
			if ($result = $conn->query($sql)){
				$arrFavor = get_db_imgfavor($_GET['Path']);
				?>
				<button onclick="add_favor(<?php echo $uid.','.$imgid.',\''.$_GET['Path'].'\'';?>, 'addFavor')">Add to Favorite</button>
				<div class="aside">
					<p>Favorite</p>
					<ul>
						<li class="number"><?php echo sizeof($arrFavor);?></li>
					</ul>
				</div>
				<?php
			}
		} else {
			$sql = "INSERT INTO travelimagefavor
			(UID, ImageID)
			VALUES
			(\"$uid\",\"$imgid\")";
			if ($result = $conn->query($sql)){
				$arrFavor = get_db_imgfavor($_GET['Path']);
				?>
				<button onclick="delete_favor(<?php echo $uid.','.$imgid.',\''.$_GET['Path'].'\'';?>, 'addFavor')">Added</button>
				<div class="aside">
					<p>Favorite</p>
					<ul>
						<li class="number"><?php echo sizeof($arrFavor);?></li>
					</ul>
				</div>
				<?php
			}
		}
  }
?>
