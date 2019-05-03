<?php
  
    include('dbConnect.php');
    include('Logging_activities.php');
    $event = "Login";
    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    ini_set("session.cookie_secure", 1);
    ini_set("session.cookie_httponly", 1);
    ini_set("session.use_cookies", 1);
    ini_set("session.use_only_cookies", 1);
    //ini_set("session.entropy_file", 1);
    ini_set("session.entropy_length", 16);
    ini_set("session.cookie_lifetime", 0); //half hour - 0 deletes session on closing browser
	ini_set("session.gc_maxlifetime", 0);
    
    session_start();
	
	if(isset($_POST['login'])) {//login selected
		$username = $_POST['username'];
		$password = $_POST['password'];
		if(empty($username))
		{
			$error_msg = "Invalid username and password";
			header("Location: Error.php?errormsg=$error_msg");
			hasAccess($username,$error_msg,$event);
			return false;
		}
		if(empty($password))
		{
			$error_msg =  "Invalid username and password";
			header("Location: Error.php?errormsg=$error_msg");
			hasAccess($username,$error_msg,$event);
			return false;
		}
		
		$uname_valid = validate($username, "username");
		$pwd_valid = validate($password, "password");
		
		if($pwd_valid == False or $uname_valid == False)
		{
			$error_msg = 'Invalid username and password';
			hasAccess($username,$error_msg,$event);
			header("Location: Error.php?errormsg=$error_msg");
			
			exit;
		}
		
		  
		$sql_query = "Select * from User where uname = '$username' ";
		$result = $conn->query($sql_query);
		if($result->num_rows == 1)
		{
			$_SESSION['user'] = $username ;
			
			$row = $result->fetch_assoc();
			if (password_verify($password, $row['pwd'])) {
			if($row['isFaculty'] ==0)
			{
				
				$error_msg = "Successful student Login";
				hasAccess($username,$error_msg,$event);
				die(header("location: StudentPortal.php"));
			}
			else
			{
				$error_msg = "Successful faculty login";
				hasAccess($username,$error_msg,$event);
				die(header("location: FacultyPortal.php"));
				
			}
			}
			else {
				
			$error_msg ='Invalid login credentials';
			hasAccess($username,$error_msg,$event);
			header("Location: Error.php?errormsg=$error_msg");
			exit;
				
			}
			
		}
		else
		{   
			
			$error_msg =  'Invalid credentials';
			hasAccess($username,$error_msg,$event);
			header("Location: Error.php?errormsg=$error_msg");
			exit;
		
			
		}
		
	}else if(isset($_POST['signup'])) {//signup selected
		session_destroy();//ends session
		die(header("Location: SignUp.php"));
	}    

	function validate($string, $type){
		$result = False;
		if($type == "username"){
			if(preg_match("/^[A-Za-z0-9]{1,34}$/", $string)){
				//proceed checks
				$result = True;
			}else{
				//die(header("Location: SignUp.php?error=invalid_username"));
				
				$result = False;
			}
		}if($type == "password"){
			/*-- Policy: at least 1 uppercase
			//-- 		 at least 1 lowercase
			//--		 at least 1 number
			//--		 at least 8 characters
			*/
			
			$uppercase = preg_match('/[A-Z]/', $string);
			$lowercase = preg_match('/[a-z]/', $string);
			$number = preg_match('/[0-9]/', $string);
			
			if($uppercase && $lowercase && $number && strlen($string)>=8){
				
				$result = True;
			}else{
				
				$result = False;
			}
		}
		
		return $result;
	}
    
?>