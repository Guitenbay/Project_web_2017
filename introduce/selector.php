<?php
 include_once "img_data.php";
?>
<?php
if (isset($_GET['continent'])){
  if(strlen($_GET['continent']) > 0){
    $countryNames = get_db_countryarray($_GET['continent']);
    ?>
    <option value="">--select country--</option>
    <?php
    foreach ($countryNames as $value) {
      # code...
      ?>
      <option value="<?php echo $value['ISO'];?>"><?php echo $value['CountryName'];?></option>
      <?php
    }
  }
}elseif(isset($_GET['country'])){
  if(strlen($_GET['country']) > 0){
    $cityNames = get_db_cityarray($_GET['country']);
    ?>
    <option value="">--select city--</option>
    <?php
    foreach ($cityNames as $value) {
      # code...
      ?>
      <option value="<?php echo $value['GeoNameID'];?>"><?php echo $value['AsciiName'];?></option>
      <?php
    }
  }
}
?>
