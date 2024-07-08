<?php
session_start();
ini_set('display_errors', 1);

class Action {
    private $db;

    public function __construct() {
        ob_start();
        include '../db.php'; 
        $this->db = $conn;
    }

    public function __destruct() {
        $this->db->close();
        ob_end_flush();
    }

    public function login() {
        extract($_POST);

        $username = $this->db->real_escape_string($username);
        $password = $this->db->real_escape_string($password);

        $qry = $this->db->query("SELECT * FROM user_ad WHERE username = '$username'");

        if ($qry->num_rows > 0) {
            $user = $qry->fetch_assoc();

            if ($password === $user['password']) { 
                foreach ($user as $key => $value) {
                    if ($key !== 'password' && !is_numeric($key)) {
                        $_SESSION['login_' . $key] = $value;
                    }
                }
                return 1; // Login successful
            } else {
                return 'Incorrect password'; // Incorrect password
            }
        } else {
            return 'Username not found'; // Username not found
        }
    }

	public function save_supply(){
		extract($_POST);
		$data = " name = '$name' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO supply_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE supply_list set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	public function delete_supply(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM supply_list where id = ".$id);
		if($delete)
			return 1;
	}
	public function save_inv(){
		extract($_POST);
		$data = " supply_id = '$supply_id' ";
		$data .= ", qty = '$qty' ";
		$data .= ", stock_type = '$stock_type' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO inventory set ".$data);
		}else{
			$save = $this->db->query("UPDATE inventory set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	public function delete_inv(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM inventory where id = ".$id);
		if($delete)
			return 1;
	}
	public function logout() {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }
}
?>