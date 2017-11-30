<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if(!isset($_REQUEST['fname'])) {
    header("HTTP/1.0 400 Bad Request");
        print("Missing first name");
        exit();
  }
  $fname = trim($_REQUEST['fname']);
  if($fname == "") {
    header("HTTP/1.0 400 Bad Request");
        print("Bad first name");
        exit();
  }
  if(!isset($_REQUEST['lname'])) {
    header("HTTP/1.0 400 Bad Request");
        print("Missing last name");
        exit();
  }
  $lname = trim($_REQUEST['lname']);
  if($lname == "") {
    header("HTTP/1.0 400 Bad Request");
        print("Bad last name");
        exit();
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

  if(!isset($_REQUEST['susername'])) {
    header("HTTP/1.0 400 Bad Request");
        print("Missing email the second time");
        exit();
  }
  $susername = trim($_REQUEST['susername']);
  if($susername == "") {
    header("HTTP/1.0 400 Bad Request");
        print("Bad email the second time");
        exit();
  }
  if($susername !== $username) {
    header("HTTP/1.0 400 Bad Request");
    print("Emails don't match");
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

  $new_user = Users::create($username, $password, $fname, $lname);

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
  private $fname;
  private $lname;

	public static function connect() {
		return new mysqli("classroom.cs.unc.edu", 
                   "elaineek", 
                   "Jennifer420!*", 
		           "elaineekdb");
	}

	public static function create($username, $password, $fname, $lname) {
		$mysqli = Users::connect(); 

    $select = $mysqli->query("select id from Users where username = '" . 
      $mysqli->real_escape_string($username) . "'");

    $info = $select->fetch_array();
    $id = $info['id'];

    if ($id) {
      header("HTTP/1.0 500 Server Error");
      print("This username is already associated with a user.");
      exit();
    } else {
      $s = "insert into Users values ('0', " .
            "'" . $mysqli->real_escape_string($fname) . "', " .
            "'" . $mysqli->real_escape_string($lname) . "', " .
            "'" . $mysqli->real_escape_string($username) . "', " .
            "'" . $mysqli->real_escape_string($password) . "')";

      $result = $mysqli->query($s);

      if ($result) {
        $id = $mysqli->insert_id;
        return new Users($id, $username, $password, $fname, $lname);
      }
      return null;
    }
	}

	private function __construct($id, $username, $password, $fname, $lname) {
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->fname = $fname;
    $this->lname = $lname;
  }

  public function getID() {
    return $this->id;
  }

  public function getJSON() {
    $json_obj = array('id' => $this->id,
		      'username' => $this->username,
		      'password' => $this->password,
          'fname' => $this->fname,
          'lname' => $this->lname);
    return json_encode($json_obj);
  }
}

?>