<?php
include_once "img_data.php";
function write_ul($highlight="", $arrURL){
  $arr = ['Home', 'Browse', 'Search', 'Upload'];
  $h = 0;
  switch ($highlight) {
    case 'Home':
      $h = 0;
      break;
    case 'Browse':
      $h = 1;
      break;
    case 'Search':
      $h = 2;
      break;
    case 'Upload':
      $h = 3;
      break;
    default:
      $h = -1;
      break;
  }
  for ($i=0;$i<4;$i++){
    if ($i == $h) {
      ?>
      <li><a id='highlight' href="<?php echo $arrURL[$i];?>"><?php echo $arr[$i];?></a></li>
      <?php
    } else {
      ?>
      <li><a href="<?php echo $arrURL[$i];?>"><?php echo $arr[$i];?></a></li>
      <?php
    }
  }
}
function navbar($highlight="", $user=""){
  ?>
  <header class="navbar">
    <form id="photosearch" class="left_area" action="search.php" method="GET">
      <input id="search_area" placeholder="Search for free photos..." type="search" name="search" />
      <input type="image" onclick="searchpage.html" src="image/search.png" id="search_button">
    </form>
    <div id="pageName"><b>PIXBaR</b></div>
    <nav>
      <ul class="head_right">
        <?php
        // if ($user){
          write_ul($highlight, ['index.php', 'browse.php', 'search.php', 'upload.php']);
        // } else {
        //   write_ul($highlight, ['index.php', 'login.php', 'login.php', 'login.php']);
        // }
        ?>
      </ul>
    </nav>
  </header>
  <a href="#" id="top"><img src="image/TOP.png" alt="top" /></a>
  <?php
  $flag = false;
  $arrUserImg = array();
  if ($user) {
    $arrUserImg = get_db_userimg($user);
    if (empty($arrUserImg)){
      $flag = false;
    } else {
      $flag = true;
    }
  }
  if(isset($_GET["out"]) and $_GET["out"] == "1"){
    session_destroy();
    $flag = false;
  }
  if($flag){
    ?>
    <span id="user" onmouseover="dropdownIn()" onmouseout="dropdownOut()">
      <img id="user_img" src="image/usr/<?php
        if ($arrUserImg[0]['Headpath'] != '') {
          echo $arrUserImg[0]['Headpath'];
        } else {
          echo '未登录.jpg';
        }?>"/>
      <div>
      <nav id="dropdown">
        <img class="arrow" src="image/框箭头.png" />
        <ul>
          <div id="user_name"><?php echo $arrUserImg[0]['FirstName'];?></div>
          <li><a href="index.php?out=1">Login out</a></li>
          <li><a href="account.php">My photos</a></li>
          <li><a href="upload.php">Upload</a></li>
          <li><a href="info.php">Info</a></li>
          <hr/>
          <li><a href="register.php">Sign up</a></li>
        </ul>
      </nav>
      </div>
    </span>
    <?php
  } else {
    ?>
    <span id="user" onmouseover="dropdownIn()" onmouseout="dropdownOut()">
      <img id="user_img" src="image/usr/未登录.jpg"  />
      <div>
      <nav id="dropdown">
        <img class="arrow" src="image/框箭头.png" />
        <ul>
          <div id="user_name">Nobody...</div>
          <li><a href="login.php">Login</a></li>
          <li><a href="login.php">My photos</a></li>
          <li><a href="upload.php">Upload</a></li>
          <li><a href="info.php">Info</a></li>
          <hr/>
          <li><a href="register.php">Sign up</a></li>
        </ul>
      </nav>
      </div>
    </span>
    <?php
  }
}
?>
