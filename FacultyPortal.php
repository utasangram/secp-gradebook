<?php
	
	ini_set("session.cookie_secure", 1);
	ini_set("session.cookie_httponly", 1);
	ini_set("session.use_cookies", 1);	
	ini_set("session.use_only_cookies", 1);
	ini_set("session.entropy_length", 16);
	ini_set("session.cookie_lifetime", 1800); //half hour - 0 deletes session on closing browser
	ini_set("session.gc_maxlifetime", 1800);
	
	session_start();
	if(isset($_SESSION['user'])) {
		include('dbConnect.php');
		include('Logging_activities.php');
			
		$uname = $_SESSION['user'];
		$month = date('n'); 
		if($month<=5){
		    $semester = "Spring";
		}elseif($month<=7){
		    $semester = "Summer";
		}else{
		    $semester = "Fall";
		}
		$query = "Select * from Course where Fid=(SELECT Uid FROM User where uname=? and isFaculty='1')";
		try{
			$stmt = $conn->prepare($query);
			$stmt->bind_param("s", $uname);
			$stmt->execute();
			$resultquery = $stmt->get_result();
			echo '<html>';
			echo "<link href='/css/yourgrade.css' rel='stylesheet' type='text/css'>";
			echo '<div class="form-style-5">';
			echo '<div class="form-style-6">';
			echo '<h1>Faculty Portal</h1>';
			echo '</div>';
			echo "<form action='EditStudentGrades.php' method='POST'>";
			echo '<fieldset>';
			echo '<table style="width:100%">';
			echo '<tr>';
			echo '<th>Course id</th>';
			echo '<th>Year</th>';
			echo '<th>Semester</th>';
			echo '<th>Name</th>';
			echo '<th>Course Number</th>';
			echo '</tr>';
			 while($row = $resultquery->fetch_assoc()){ 
				echo '<tr style="width:100%">';
				echo "<td> <input type='radio' name='radiobutton' value='{$row['Cid']}'required>" . $row['Cid'] .'</td>';
				echo '<td>' . $row['year'] .'</td>';
				echo '<td>' . $row['semester'] .'</td>';
				echo '<td>' . $row['name'] .'</td>';
				echo '<td>' . $row['cnum'] .'</td>';
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
			$stmt->close();
		} catch (Error $e) {
			$errormsg="Something Went Wrong";
			logInfoError($uname,$e->getMessage());
			session_destroy();
			header("Location: Error.php?errormsg=$errormsg");
		}
	}else {
		session_destroy();
		die(header("Location: LogIn.html"));
	}
?>