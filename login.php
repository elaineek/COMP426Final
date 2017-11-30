<?php
session_start();

function check_password($username, $password) {
  $request = Users::find($username, $password);
  if ($request == null) {
    header("HTTP/1.0 500 Server Error");
    print("Incorrect username or password. Please try again or register as a new user.");
    exit();
  }
}

if(!isset($_REQUEST['username'])) {
  header("HTTP/1.0 400 Bad Request");
  print("Missing email");
  exit();
}
$username = trim($_REQUEST['username']);
if($username == "") {
  header("HTTP/1.0 400 Bad Request");
  print("Bad email");
  exit();
}
if(!isset($_REQUEST['password'])) {
  header("HTTP/1.0 400 Bad Request");
  print("Missing password");
  exit();
}
$ipassword = trim($_REQUEST['password']);
if($ipassword == "") {
  header("HTTP/1.0 400 Bad Request");
  print("Bad password");
  exit();
}
$password = md5($ipassword);

$check = check_password($username, $password);
return $check;

class Users
{
  private $id;
  private $username;
  private $password;

  public static function connect() {
    return new mysqli("classroom.cs.unc.edu", 
                   "elaineek", 
                   "Jennifer420!*", 
               "elaineekdb");
  }

  public static function find($username, $password) {
    $mysqli = Users::connect(); 

    $select = $mysqli->query("select id from Users where username = '" . 
      $mysqli->real_escape_string($username) . "' and password = '" .
      $mysqli->real_escape_string($password) . "'");

    $info = $select->fetch_array();
    $id = $info['id'];

    if ($id) {
      return new Users($id, $username, $password);
    } else {
      return null;
    }
  }

  private function __construct($id, $username, $password) {
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
  }

  public function getID() {
    return $this->id;
  }

  public function getJSON() {

    $json_obj = array('id' => $this->id,
          'username' => $this->username,
          'password' => $this->password);
    return json_encode($json_obj);
  }
}