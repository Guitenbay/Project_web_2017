<?php include_once "img_data.php";?>
<?php
  if (isset($_GET['UID']) and $_GET['UID']
    and isset($_GET['FOLLOWID']) and $_GET['FOLLOWID']){
      $uid = $_GET['UID'];
      $followid = $_GET['FOLLOWID'];
      $conn = connect_db();
      $sql = "INSERT INTO traveluserfollowing
        (UID, UIDFollowing)
        VALUES
        (\"$uid\",\"$followid\")";
      if ($result = $conn->query($sql)){
        ?>
				<button style="margin-top:10px;" class="btn btn-disabled" disabled="disabled">Followed</button>
				<?php
      }
  }
?>
