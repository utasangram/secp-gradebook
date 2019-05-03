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
		include('Logging_activities.php');
		//mysqli_query($conn, "SET GLOBAL sql_mode = ''");
		//mysqli_query($conn, "SET SESSION sql_mode = ''");
			
		$uname = $_SESSION['user'];
		if(isset($_POST['radiobutton'])){
			$cid = $_POST['radiobutton'];
		} else {
			$errormsg="User didnt select any choice";
			logInfoError($uname,$errormsg);
			session_destroy();
			header("Location: Error.php?errormsg=$errormsg");
		}
		$_SESSION['cid']=$cid;
		$month = date('n'); 
		if($month<=5){
		    $semester = "Spring";
		}elseif($month<=7){
		    $semester = "Summer";
		}else{
		    $semester = "Fall";
		}
		$query = "Select * from Enroll where Cid=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $cid);
		$stmt->execute();
		if($resultquery = $stmt->get_result()){
			echo '<html>';
			echo "<link href='/css/yourgrade.css' rel='stylesheet' type='text/css'>";
			echo '<div class="form-style-5">';
			echo '<div class="form-style-6">';
			echo '<h1>Edit Grades for Student</h1>';
			echo '</div>';
			echo "<form action='UpdateStudentGrades.php' method='POST'>";
			echo '<fieldset>';
			echo '<table style="width:100%">';
			echo '<tr>';
			echo '<th>Student id</th>';
			echo '<th>Grade</th>';
			echo '</tr>';
			 while($row = $resultquery->fetch_assoc()){
				echo '<tr style="width:100%">';
				echo "<td> <input type='radio' name='uid' value='{$row['Uid']}' required>" . $row['Uid'] .'</td>';
				echo '<td>' . $row['grade'] .'</td>';
				echo '</tr>';
			 }
			echo '</table>';
			echo '</fieldset>';
			echo '<input type="submit" name="editgrades" value="Edit Grades" />';
			echo '</form>';
			echo "<form action='logout.php' method='POST'>";
			echo '<input type="submit" name="logout" value="Logout" />';
			echo '</form>';
			echo '</div>';
			echo '</html>';
		}else{
			echo "error";
		}
	}else {
		session_destroy();
		die(header("Location: LogIn.html"));
	}
?>