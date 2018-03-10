<?php
  include_once "img_data.php";
  // foreach ($arrPath as $value) {
  //   # code...
  //   image_crop($value, 1300, 600, 'tmp');
  // }
?>
<?php
function get_ext_name($original_file_name){
  $ext_name = end(explode('.', $original_file_name));
  return $ext_name;
}

function generate_file_name($uid,$original_file_name){
  $file_name = time().$uid.rand(0,100).'.'.get_ext_name($original_file_name);
  return $file_name;
}
function local_get_image_url($str, $tip){
  // return "image/travel-images/".$tip."/".$str;
  return dirname(__FILE__).'/../image/travel-images/'.$tip."/".$str;
}
function abs_get_image_url($str, $tip){
  return dirname(__FILE__).'/../image/travel-images/'.$tip."/".$str;
}
function image_crop($file_name,$dst_width,$dst_height,$output_type){
  $path = local_get_image_url($file_name,'large');
  $output_path = local_get_image_url($file_name,$output_type);

  $ext_name = strtolower(get_ext_name($file_name));

  if($ext_name == 'jpg') $ext_name = 'jpeg';
  $create_func = 'imagecreatefrom'.$ext_name;
  $output_func = 'image'.$ext_name;
  // if (!$create_func($path)) {
    $src_image = imagecreatefromstring(file_get_contents($path));
  // } else {
  //   $src_image = $create_func($path);
  // }
  if(!$src_image){
    return false;
  }
  $src_width = imagesx($src_image);
  $src_height = imagesy($src_image);

  $ratio = $dst_width / $dst_height;

  $clip_width = ($src_width / $ratio > $src_height) ? ($src_height * $ratio) :$src_width;
  $clip_height = $clip_width / $ratio;

  $src_x = ($src_width - $clip_width) / 2;
  $src_y = ($src_height - $clip_height) / 2;

  $dst_img = imagecreatetruecolor($dst_width,$dst_height);

  imagecopyresampled($dst_img,$src_image,0,0,$src_x,$src_y,$dst_width,$dst_height,$clip_width,$clip_height);

  if(!$dst_img){
    imagedestroy($src_image);
    imagedestroy($dst_img);
    return false;
  }

  $output_func($dst_img,$output_path);
  imagedestroy($src_image);
  imagedestroy($dst_img);
  return true;
}
// revise later
function delete_image_file($path){
  $path1 = abs_get_image_url($path,'large');
  $path2 = abs_get_image_url($path,'medium');
  $path3 = abs_get_image_url($path,'square-medium');
  unlink("$path1");
  unlink("$path2");
  unlink("$path3");
}
?>
