<?php
  function make_hash($password){
    $options = ['cost' => 10, 'salt' =>mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)];
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);
    return $hash;
  }
  function is_password($pass, $hash){
    $arr = password_get_info($hash);
    if ($arr['algo'] == 0){
      return $pass == $hash;
    } else {
      return password_verify($pass, $hash);
    }
  }
?>
