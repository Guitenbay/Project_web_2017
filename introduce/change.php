<?php
  include_once "img_data.php";
?>
<?php
  if(isset($_GET['change'])){
    if (strlen($_GET['change']) > 0){
      $arrChange = get_db_most_pop($_GET['change'], 6);
      $arr1 = array();
      $arr2 = array();
      for($i=0;$i<6;$i++){
        if($i < 3){
          $arr1[] = $arrChange[$i];
        }else{
          $arr2[] = $arrChange[$i];
        }
      }
      make_imgRow($arr1);
      make_imgRow($arr2);
    }
  }
  if (isset($_GET['new']) and $_GET['new'] == "true"){
    $arrNew = get_db_new();
    foreach ($arrNew as $value) {
			# code...
			make_new_img($value);
		}
  }
?>
<?php
function make_imgRow($arr){
  ?>
  <div class="row">
  <?php
  foreach ($arr as $value) {
    # code...
    ?>
    <div class="col-xs-6 col-md-4">
      <a href="#" class="thumbnail">
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
