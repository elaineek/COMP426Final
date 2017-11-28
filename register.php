<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if(!isset($_REQUEST['username'])) {
		header("HTTP/1.0 400 Bad Request");
      	print("Missing username");
      	exit();
	}
	$username = trim($_REQUEST['username']);
	if($username == "") {
		header("HTTP/1.0 400 Bad Request");
      	print("Bad username");
      	exit();
	}
	if(!isset($_REQUEST['password'])) {
		header("HTTP/1.0 400 Bad Request");
      	print("Missing password");
      	exit();
	}
	$password = trim($_REQUEST['password']);
	if($password == "") {
		header("HTTP/1.0 400 Bad Request");
      	print("Bad password");
      	exit();
	}
    $groupname = null;
    if (isset($_REQUEST['groupname'])) {
      $groupname = trim($_REQUEST['groupname']);
    }

  $new_user = Users::create($username, $password, $groupname);

    if($new_user == null) {
      header("HTTP/1.0 500 Server Error");
      print("Server couldn't create new user.");
      exit();
    }

    //Generate JSON encoding of new Todo
    header("Content-type: application/json");
    print($new_user->getJSON());
    exit();
}

class Users
{
	private $id;
	private $username;
	private $password;
	private $groupname;

	public static function connect() {
		return new mysqli("classroom.cs.unc.edu", 
                   "elaineek", 
                   "Jennifer420!*", 
		           "elaineekdb");
	}

	public static function create($username, $password, $groupname) {
		$mysqli = Users::connect();

    $drop = $mysqli->query("drop table if exists Users");

    $create = $mysqli->query("create table Users ( " .
                  "id int primary key not null auto_increment, " .
                  "username char(25) not null, " .
                  "password char(25) not null, " .
                  "groupname char(25))");   

    $select = $mysqli->query("select exists(select * from Users where username = '" . $mysqli->real_escape_string($username) . "')");

    if ($select == 0) {
      header("HTTP/1.0 500 Server Error");
      print("This username is already associated with a user.");
      exit();
    }

    $s = "insert into Users values ('0', " .
          "'" . $mysqli->real_escape_string($username) . "', " .
          "'" . $mysqli->real_escape_string($password) . "', " .
          "'" . $mysqli->real_escape_string($groupname) . "')";

		$result = $mysqli->query($s);

		if ($result) {
			$id = $mysqli->insert_id;
			return new Users($id, $username, $password, $groupname);
		}
		return null;
	}

	private function __construct($id, $username, $password, $groupname) {
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->groupname = $groupname;
  }

  public function getID() {
    return $this->id;
  }

  public function getJSON() {
    if ($this->groupname == null) {
      $groupname = null;
    }

    $json_obj = array('id' => $this->id,
		      'username' => $this->username,
		      'password' => $this->password,
		      'groupname' => $groupname);
    return json_encode($json_obj);
  }
}

?>