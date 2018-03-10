<?php
function connect_db(){
	$dbhost = 'localhost';
	$dbuser = 'tenbay';
	$dbpass = '272779';
	$connect = new mysqli($dbhost, $dbuser, $dbpass);
	if (!$connect){
		die('连接失败：'.mysqli_error($connect));
	}
	$connect->query("set names utf8");
	$connect->select_db('travels');
	return $connect;
}
?>
<?php #Index.php
	function get_db_most_pop($num1, $num2){
		$connect = connect_db();
		$num1 = $connect->real_escape_string($num1);
		$num2 = $connect->real_escape_string($num2);
		$sql = "SELECT ImageID, Path, Title, Description, FavorNum FROM
			(SELECT travelimage.ImageID, travelimage.Path,
				travelimagedetails.Title,travelimagedetails.Description, favor.FavorNum
			FROM (travelimage LEFT JOIN travelimagedetails ON travelimage.ImageID = travelimagedetails.ImageID)
			LEFT JOIN (SELECT ImageID, COUNT(*) AS FavorNum FROM travelimagefavor GROUP BY ImageID) AS favor
			ON travelimage.ImageID = favor.ImageID) AS d1 ORDER BY FavorNum DESC LIMIT $num1,$num2";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_new(){
		$connect = connect_db();
		$sql = "SELECT ImageID, Path
			FROM travelimage ORDER BY ImageID DESC LIMIT 20";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>

<?php #Browse.php
	function get_db_countryname(){
		$connect = connect_db();
		$sql = "SELECT DISTINCT
			geocountries.ISO, geocountries.CountryName,
			geocontinents.ContinentCode, geocontinents.ContinentName
			FROM geocountries
			INNER JOIN geocontinents
			ON geocountries.Continent = geocontinents.ContinentCode";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_continentname(){
		$connect = connect_db();
		$sql = "SELECT DISTINCT ContinentCode, ContinentName
			FROM geocontinents";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_countryarray($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT ISO, CountryName, Continent
			FROM geocountries
			WHERE Continent = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_cityarray($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT GeoNameID, AsciiName, CountryCodeISO
			FROM geocities
			WHERE CountryCodeISO = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_continent($str, $all, $page=1){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1) * 8;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num, 8";
		}
		$sql = "SELECT DISTINCT travelimage.Path, travelimagedetails.Title, geocountries.continent
			FROM ((travelimagedetails INNER JOIN travelimage ON travelimagedetails.ImageID = travelimage.ImageID)
			INNER JOIN geocities ON geocities.CountryCodeISO = travelimagedetails.CountryCodeISO)
			INNER JOIN geocountries ON geocities.CountryCodeISO = geocountries.ISO
			WHERE geocountries.Continent = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_country($str, $all, $page=1){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1) * 8;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num, 8";
		}
		$sql = "SELECT DISTINCT travelimage.Path, travelimagedetails.Title, geocities.CountryCodeISO
			FROM (travelimagedetails INNER JOIN travelimage ON travelimagedetails.ImageID = travelimage.ImageID)
			INNER JOIN geocities ON geocities.CountryCodeISO = travelimagedetails.CountryCodeISO
			WHERE geocities.CountryCodeISO = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_city($str, $all, $page=1){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1) * 8;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num, 8";
		}
		$sql = "SELECT DISTINCT travelimage.Path, travelimagedetails.Title, geocities.AsciiName
			FROM (travelimagedetails INNER JOIN travelimage ON travelimagedetails.ImageID = travelimage.ImageID)
			INNER JOIN geocities ON geocities.CountryCodeISO = travelimagedetails.CountryCodeISO
			WHERE geocities.AsciiName = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>

<?php #Search.php
	function get_db_pictures($str) {
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT travelimage.Path, travelimagedetails.Title, travelimagedetails.Description
		  	FROM travelimage INNER JOIN travelimagedetails
				on travelimage.ImageID = travelimagedetails.ImageID
				WHERE travelimagedetails.Title REGEXP \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_detail($str) {
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT travelimage.Path, travelimagedetails.Title, travelimagedetails.Description
		  	FROM travelimage INNER JOIN travelimagedetails
				on travelimage.ImageID = travelimagedetails.ImageID
				WHERE travelimagedetails.Description REGEXP \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>

<?php #Describe.php
	function get_db_describe($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT
			traveluser.Headpath,
			traveluserdetails.UID, travelimagedetails.ImageID,
			traveluserdetails.FirstName, traveluserdetails.LastName,
			travelimage.Path,
			travelimagedetails.Title, travelimagedetails.Description,
			travelimagedetails.latitude, travelimagedetails.Longitude,
			geocities.AsciiName, geocountries.CountryName
			FROM ((((travelimage LEFT JOIN travelimagedetails ON travelimagedetails.ImageID = travelimage.ImageID)
			LEFT JOIN geocities ON geocities.GeoNameID = travelimagedetails.CityCode)
			LEFT JOIN traveluserdetails ON traveluserdetails.UID = travelimage.UID)
			LEFT JOIN traveluser ON traveluser.UID = traveluserdetails.UID)
			LEFT JOIN geocountries ON geocities.CountryCodeISO = geocountries.ISO
			WHERE travelimage.Path = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_uid($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
			UID, UserName
		 	FROM traveluser
			WHERE UserName = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$uid = "";
		if($row = $result->fetch_array(MYSQLI_ASSOC)){
			$uid = $row['UID'];
		}
		$connect->close();
		return $uid;
	}
	function get_db_imgfavor($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
		travelimage.Path, travelimagefavor.UID
		FROM travelimage
		INNER JOIN travelimagefavor ON travelimage.ImageID = travelimagefavor.ImageID
		WHERE travelimage.Path = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>

<?php #Login.php
	function get_db_NP($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT UserName, Pass
		FROM traveluser WHERE UserName = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>

<?php #Register.php
	function get_db_email($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT UserName
		FROM traveluser WHERE UserName = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>

<?php #Navbar.php/ Upload.php
	function get_db_userimg($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT traveluser.UID, traveluser.Headpath, traveluser.UserName, traveluserdetails.FirstName
			FROM traveluser INNER JOIN traveluserdetails
			ON traveluser.UserName = traveluserdetails.Email WHERE traveluser.UserName = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_imageID($str) {
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT ImageID, Path
		FROM travelimage WHERE Path = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$connect->close();
		return $row['ImageID'];
	}
	function get_db_uploadedInfo($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT
			travelimage.Path,
			travelimagedetails.Title, travelimagedetails.Description,
			travelimagedetails.latitude, travelimagedetails.Longitude, travelimagedetails.CountryCodeISO,
			geocities.AsciiName, geocities.GeoNameID, geocountries.CountryName, geocontinents.ContinentCode, geocontinents.ContinentName
			FROM ((((travelimage INNER JOIN travelimagedetails ON travelimagedetails.ImageID = travelimage.ImageID)
			INNER JOIN geocities ON geocities.GeoNameID = travelimagedetails.CityCode)
			INNER JOIN traveluserdetails ON traveluserdetails.UID = travelimage.UID)
			INNER JOIN geocountries ON geocities.CountryCodeISO = geocountries.ISO)
			INNER JOIN geocontinents ON geocontinents.ContinentCode = geocountries.Continent
			WHERE travelimage.Path = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>
<?php #Account.php
	function get_db_myPhotos($str, $all, $page=1) {
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1)*12;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num,12";
		}
		$sql = "SELECT traveluser.UserName,travelimage.ImageID, travelimage.Path, travelimagedetails.Title
			FROM (traveluser INNER JOIN travelimage ON traveluser.UID = travelimage.UID)
			INNER JOIN travelimagedetails ON travelimage.ImageID = travelimagedetails.ImageID
			WHERE traveluser.UserName = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_myInfo($str) {
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT traveluser.UID, traveluser.UserName, traveluserdetails.FirstName, traveluserdetails.LastName
			FROM traveluser INNER JOIN traveluserdetails
			ON traveluser.UserName = traveluserdetails.Email WHERE traveluser.UserName = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>
<?php #Account.php
	function get_db_favor($str, $all, $page=1){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1)*12;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num,12";
		}
		$sql = "SELECT DISTINCT traveluser.UserName, travelimage.Path, travelimage.ImageID, travelimagedetails.Title
			FROM ((traveluser INNER JOIN travelimagefavor ON travelimagefavor.UID = traveluser.UID)
			INNER JOIN travelimage ON travelimagefavor.ImageID = travelimage.ImageID)
			INNER JOIN travelimagedetails ON travelimage.ImageID = travelimagedetails.ImageID
			WHERE traveluser.UserName = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_follow($str, $all, $page=1){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1)*12;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num,12";
		}
		$sql = "SELECT DISTINCT
			traveluserfollowing.UID, traveluserfollowing.UIDFollowing,
			traveluser.UserName, traveluserdetails.FirstName, traveluserdetails.LastName
			FROM (traveluserfollowing INNER JOIN traveluser ON traveluser.UID = traveluserfollowing.UID)
			INNER JOIN traveluserdetails ON traveluserfollowing.UIDFollowing = traveluserdetails.UID
			WHERE traveluser.UserName = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_followed($str, $all, $page=1){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$page = $connect->real_escape_string($page);
		$num = ($page-1)*12;
		$limit = "";
		if (!$all){
			$limit = "LIMIT $num,12";
		}
		$sql = "SELECT DISTINCT
			traveluserfollowing.UID, traveluserfollowing.UIDFollowing,
			traveluser.UserName, traveluserdetails.FirstName, traveluserdetails.LastName
		 	FROM (traveluserfollowing INNER JOIN traveluser ON traveluser.UID = traveluserfollowing.UIDFollowing)
			INNER JOIN traveluserdetails ON traveluserfollowing.UID = traveluserdetails.UID
			WHERE traveluser.UserName = \"$str\" ".$limit;
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_username($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
			UID, UserName
		 	FROM traveluser
			WHERE UID = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$username = "";
		if($row = $result->fetch_array(MYSQLI_ASSOC)){
			$username = $row['UserName'];
		}
		$connect->close();
		return $username;
	}
	function get_db_img($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
			UID, Path
		 	FROM travelimage
			WHERE UID = \"$str\" LIMIT 5";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
	function get_db_path($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
			ImageID, Path
		 	FROM travelimage
			WHERE ImageID = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>
<?php #Info.php
	function get_db_password($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
			Pass, UserName
		 	FROM traveluser
			WHERE UserName = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$password = "";
		if($row = $result->fetch_array(MYSQLI_ASSOC)){
			$password = $row['Pass'];
		}
		$connect->close();
		return $password;
	}
	function get_db_changeInfo($str){
		$connect = connect_db();
		$str = $connect->real_escape_string($str);
		$sql = "SELECT DISTINCT
			FirstName, LastName, Address, City, Country, Phone, Email
		 	FROM traveluserdetails
			WHERE Email = \"$str\"";
		if (!$result = $connect->query($sql)){
			die('输入错误：'.mysqli_error($connect));
		}
		$result = $connect->query($sql);
		$arr = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$arr[] = $row;
		}
		$connect->close();
		return $arr;
	}
?>
