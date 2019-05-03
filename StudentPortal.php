<?php
	
	ini_set("session.cookie_secure", 1);
	ini_set("session.cookie_httponly", 1);
	ini_set("session.use_cookies", 1);
	ini_set("session.use_only_cookies", 1);
	//ini_set("session.entropy_file", 1);
	ini_set("session.entropy_length", 16);
	ini_set("session.cookie_lifetime", 1800); //half hour - 0 deletes session on closing browser
	ini_set("session.gc_maxlifetime", 1800);
	
	session_start();
	if(isset($_SESSION['user'])) {
		
		include('dbConnect.php');
		//mysqli_query($conn, "SET GLOBAL sql_mode = ''");
		//mysqli_query($conn, "SET SESSION sql_mode = ''");
		
		$uname = $_SESSION['user'];
		
		
		//$role_query = "SELECT isFaculty FROM User WHERE uname='$uname'";
		$role_query = "SELECT isFaculty FROM User WHERE uname=?";
		$role_stmt = $conn->prepare($role_query);
		$role_stmt->bind_param("s", $uname);
		$role_stmt->execute();
		
		if($result_rolequery = $role_stmt->get_result()){
			$row = $result_rolequery->fetch_assoc();
			if($row['isFaculty'] ==0){ //it is a student
				$month = date('n'); 
				if($month<=5){
					$semester = "Spring";
				}elseif($month<=7){
					$semester = "Summer";
				}else{
					$semester = "Fall";
				}
				$info_query = "SELECT Uid,name,email FROM User WHERE uname=?";
				$info_stmt = $conn->prepare($info_query);
				$info_stmt->bind_param("s", $uname);
				$info_stmt->execute();
				
				if($resultquery = $info_stmt->get_result()){
					//$uid = $resultquery->fetch_object()->Uid;
					//$name = $resultquery->fetch_object()->name;
					//$email = $resultquery->fetch_object()->email;
					$row = $resultquery->fetch_row();
					$uid = $row[0];
					$name = $row[1];
					$email = $row[2];
				}else{
					$uid = "???";
					$name = "???";
					$email = "???";
					echo "error";
				}
				
				if(isset($_POST['grades'])) {//view grades selected
					die(header("Location: YourGrade.php"));
				}else if(isset($_POST['logout'])) {//logout selected
					session_destroy();//ends session
					die(header("Location: LogIn.html"));
				}
			}else{//it is faculty
				session_destroy();
				die(header("Location: LogIn.html"));
			}
			
		}else{
			//unable to retrieve user's role_query
			session_destroy();
			die(header("Location: LogIn.html"));
		}
		
		
	}else {
		session_destroy();
		die(header("Location: LogIn.html"));
	}
	
?>

<html>

	<link href="/css/yourgrade.css" rel="stylesheet" type="text/css">

	<div class="form-style-5">
		<div class="form-style-6">
			<h1>Student Portal</h1>
		</div>
		<form action="StudentPortal.php" method='POST'>
			<fieldset>
				<label for="Uid">ID: <?php echo $uid; ?> </lable>
				<label for="Name">Name: <?php echo $name; ?></lable>
				<label for="Email">Email: <?php echo $email; ?></lable>
				<label for="Semester">Semester: <?php echo $semester ." ". date('y'); ?> </label>
			</fieldset>
			<input type="submit" name="grades" value="View Grades" />
			<input type="submit" name="logout" value="Logout" />
		</form>
	</div>
</html>
