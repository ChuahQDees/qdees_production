<?php
define( 'QDEES_AUTH_USERNAME', 'WxgKhOBo');
define( 'QDEES_AUTH_PASSWORD', 'g02yyyZb4MNE8DgBcqBD2SGuPVicFmzQwsUOr4pZjBQTphFJ3NbjSM9RCmCal79GONrwXQQkEr7lWrdGcjFtarXktlHogXuChUYZC8GrBcplaXyhYSQcGC4L6HNYJ7ZuJ3d4UGGB1rcbVPTy7UdMyrRpVvvBvlfYu6TETTPkJz2yuGvBb8iUB3PcpumQi0yRU0G0AlinG1FlovcY9lJ80Nj7yPvrkFCq8QeHKAmzFc3YkRvf36SHjDYdFb14BG2t');

class QdeesAuth{
  public function __construct(){
    $username = null;
    $password = null;

    // mod_php
    if (isset($_SERVER['PHP_AUTH_USER'])) {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

    // most other servers
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {

            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
              list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

    }

    $auth_error = true;

    if( empty(QDEES_AUTH_USERNAME) OR empty(QDEES_AUTH_PASSWORD) ) {
      $auth_error = true;
    }

    if (empty($username) OR empty($password)) {
      $auth_error = true;
    }

    if( $username === QDEES_AUTH_USERNAME AND $password === QDEES_AUTH_PASSWORD ){
      $auth_error = false;
    }

    if( $auth_error ){
      header('WWW-Authenticate: Basic realm="Q-dees"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'Unauthorized Access';

      die();
    }
  }
}

$giAuth = new QdeesAuth();
