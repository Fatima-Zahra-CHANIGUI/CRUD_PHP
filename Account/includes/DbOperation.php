<?php
 
class DbOperation
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';

        $db = new DbConnect();
 
        $this->con = $db->connect();
    }
	
	function createUser($FirstName, $LastName, $Email, $Password){
		$stmt = $this->con->prepare("INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssss", $FirstName, $LastName, $Email, $Password);
		if($stmt->execute())
			return true; 
		return false; 
	}

	function getUser($Email){
		$sql = "SELECT * FROM users where Email ='$Email'";
		$result = $this->con->query($sql);

		$user  = array();

		$row = $result->fetch_array(MYSQLI_NUM);
		
		if(!is_null($row[0])){
			$user['id'] = $row[0];
        	$user['FirstName'] = $row[1];
        	$user['LastName'] = $row[2];
        	$user['Email'] = $row[3];
        	$user['Password'] = $row[4];
		}
		
        return $user; 
	}
	
	function updateUser($id, $FirstName, $LastName, $Email, $Password){
		$stmt = $this->con->prepare("UPDATE users SET FirstName = ?, LastName = ?, Email = ?, Password = ? WHERE id = ?");
		$stmt->bind_param("ssssi", $FirstName, $LastName, $Email, $Password, $id);
		if($stmt->execute())
			return true; 
		return false; 
	}
	
	function deleteUser($id){
		$stmt = $this->con->prepare("DELETE FROM users WHERE id = ? ");
		$stmt->bind_param("i", $id);
		if($stmt->execute())
			return true; 
		return false; 
	}
}
