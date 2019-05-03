<?php

	$error_msg = "";
	if(isset($_GET["fname"])){
		$fname_error = ($_GET["fname"]);
		$error_msg .= "Invalid First Name \r\n";
	}
	if(isset($_GET["lname"])){
		$lname_error = ($_GET["lname"]);
		$error_msg .="Invalid Last Name \r\n";
	}
	if(isset($_GET["uname"])){
		$uname_error = ($_GET["uname"]);
		$error_msg .="Invalid Username \r\n";
	}
	if(isset($_GET["pwd"])){
		$pass_error = ($_GET["pwd"]);
		$error_msg .="Invalid password. Policy: \r\n"
					 ."    --At least 15 characters\r\n"
					 ."    --At least 1 capital letter\r\n"
					 ."    --At least 1 lowercase letter\r\n"
					 ."    --At least 1 number\r\n";
	}
	if(isset($_GET["email"])){
		$email_error = ($_GET["email"]);
		$error_msg .="Invalid Email Address ";
	}

	if(isset($_POST["signup"])) { //information submitted for signup
		$uid = null;
		$fname = $_POST["f_name"];
		$lname = $_POST["l_name"];
		$uname = $_POST["uname"];
		$pwd = $_POST["pwd"];
		$email = $_POST["email"];
		$admin = null;
		
		if(isset($_POST["role"]) && !empty($_POST["role"])) { //if a role is selected
			$role = $_POST["role"];
			if($role == "student"){ //if the role selected is student
				$faculty = 0;
				if(isset($_POST["semester"]) && !empty($_POST["semester"])) { //if a semester is selected
					/*-- start of input validation --*/
					$fname_valid = validate($fname, "name");
					$lname_valid = validate($lname, "name");
					$uname_valid = validate($uname, "username");
					$pwd_valid = validate($pwd, "password");
					$email_valid = validate($email, "email");
					
					if($fname_valid && $lname_valid && $uname_valid && $pwd_valid && $email_valid){
						/* -- passed all validation checks --*/
						$name = $fname ." ". $lname;
						$semester = $_POST["semester"];
						$year = date("Y");
						$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
					
						include('dbConnect.php');
						mysqli_query($conn, "SET GLOBAL sql_mode = ''");
						mysqli_query($conn, "SET SESSION sql_mode = ''");
						
						if($faculty==1){
							//$insert_query = "INSERT INTO `User` VALUES (null, '$uname', '$name', '$hashed_pwd', '$email', '$year', null, null,'$faculty')";
							$insert_query = "INSERT INTO `User` VALUES (null, ?, ?, ?, ?, ?, null, null,?)";
							$insert_stmt = $conn->prepare($insert_query);
							$insert_stmt->bind_param("ssssii",$uname, $name, $hashed_pwd, $email, $year, $faculty);
						}else {
							//$insert_query = "INSERT INTO `User` VALUES (null, '$uname', '$name', '$hashed_pwd', '$email', '$year', '$semester', null, '$faculty')";
							$insert_query = "INSERT INTO `User` VALUES (null, ?, ?, ?, ?, ?, ?, null, ?)";
							$insert_stmt = $conn->prepare($insert_query);
							$insert_stmt->bind_param("ssssisi",$uname, $name, $hashed_pwd, $email, $year, $semester, $faculty);
						}
						
						$insert_stmt->execute();
						if($result2 = $insert_stmt->get_result()){
							include('Logging_activities.php');
							$log_msg = "Successfully added user to db - user: " .$uname;
							hasAccess($uname,"RegisterSuccessful",$log_msg);
							die(header("Location: LogIn.html"));
							
						}else {
							die(header("Location: SignUp.php"));
						}
						
						$conn->close();
						
					}else{ //one or more inputs invalid
						$url = "SignUp.php?";
						if(!$fname_valid){//invalid first name
							$url.="&fname=1";
						}
						if(!$lname_valid){//invalid last name
							$url.="&lname=1";
						}
						if(!$uname_valid){//invalid username
							$url.="&uname=1";
						}
						if(!$pwd_valid){//invalid password
							$url.="&pwd=1";
						}
						if(!$email_valid){//invalid email
							$url.="&email=1";
						}
						die(header("Location: " .$url));
					}
					/*-- end of input validation --*/
					
				}else { //no semester selected
					die(header("Location: SignUp.php"));
				}
				
			}elseif($role == "faculty") { //the role selected is faculty
				/*-- start of input validation --*/
				$fname_valid = validate($fname, "name");
				$lname_valid = validate($lname, "name");
				$uname_valid = validate($uname, "username");
				$pwd_valid = validate($pwd, "password");
				$email_valid = validate($email, "email");
				
				if($fname_valid && $lname_valid && $uname_valid && $pwd_valid && $email_valid){
					/* -- passed all validation checks --*/
					$name =  $fname." ". $lname;
					$faculty = 1;
					$year = 0;
					$semester = null;
					$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
					
					include('dbConnect.php');
					mysqli_query($conn, "SET GLOBAL sql_mode = ''");
					mysqli_query($conn, "SET SESSION sql_mode = ''");
					
					if($faculty==1){
						$insert_query = "INSERT INTO `User` VALUES (null, '$uname', '$name', '$hashed_pwd', '$email', '$year', null, null,'$faculty')";
					}else {
						$insert_query = "INSERT INTO `User` VALUES (null, '$uname', '$name', '$hashed_pwd', '$email', '$year', '$semester', null, '$faculty')";
					}
					
					if($result2 = $conn->query($insert_query)){
						include('Logging_activities.php');
						$log_msg = "Successfully added user to db - user: " .$uname;
						hasAccess($uname,"RegisterSuccessful",$log_msg);
						die(header("Location: LogIn.html"));
						
					}else {
						die(header("Location: SignUp.php"));
					}
					
					$conn->close();
					
				}else{
					$url = "SignUp.php?";
					if(!$fname_valid){//invalid first name
						$url.="&fname=1";
					}
					if(!$lname_valid){//invalid last name
						$url.="&lname=1";
					}
					if(!$uname_valid){//invalid username
						$url.="&uname=1";
					}
					if(!$pwd_valid){//invalid password
						$url.="&pwd=1";
					}
					if(!$email_valid){//invalid email
						$url.="&email=1";
					}
					die(header("Location: " .$url));
				}
				
				/*-- end of input validation*/
				
			}
		}else{ //no role selected
			die(header("Location: SignUp.php"));
		}
		
		
	}
	
	function validate($string, $type){
		$result = False;
		if($type == "name"){
			if(preg_match("/^[A-Za-z]{1,30}$/", $string)){
				$result = True;
			}else{
				$result = False;
				//die(header("Location: SignUp.php?error=invalid_name"));
			}
		}elseif($type == "username"){
			if(preg_match("/^[A-Za-z0-9]{1,34}$/", $string)){
				//proceed checks
				$result = True;
			}else{
				//die(header("Location: SignUp.php?error=invalid_username"));
				$result = False;
			}
		}elseif($type == "password"){
			/*-- Policy: at least 1 uppercase
			//-- 		 at least 1 lowercase
			//--		 at least 1 number
			//--		 at least 15 characters
			*/
			
			$uppercase = preg_match('/[A-Z]/', $string);
			$lowercase = preg_match('/[a-z]/', $string);
			$number = preg_match('/[0-9]/', $string);
			
			if($uppercase && $lowercase && $number && strlen($string)>=15){
				//proceed checks
				$result = True;
			}else{
				//die(header("Location: SignUp.php?error=invalid_password"));
				$result = False;
			}
		}elseif($type == "email"){
			if(filter_var($string, FILTER_VALIDATE_EMAIL)){
				//proceed checks
				$result = True;
			}else{
				//die(header("Location: SignUp.php?error=invalid_email"));
				$result = False;
			}
		}
		
		return $result;
	}
?>

<html>
    
    <link href="/css/yourgrade.css" rel="stylesheet" type="text/css">
    
    <script>
        function roleCheck() {
        if (document.getElementById('studentCheck').checked) {
            document.getElementById('spring').disabled = false;
            document.getElementById('summer').disabled = false;
            document.getElementById('fall').disabled = false;
			
			document.getElementById('spring').required = true;
            document.getElementById('summer').required = true;
            document.getElementById('fall').required = true;
			
        }else {
            document.getElementById('spring').disabled = true;
            document.getElementById('summer').disabled = true;
            document.getElementById('fall').disabled = true;
        }
    
    }
    </script>
    
    <div class="form-style-5">
        <div class="form-style-6">
        <h1>Sign Up</h1>
        </div>
        <form action="SignUp.php" method='POST'>
            <fieldset>
				<input type="text" name="f_name" placeholder="First Name *" required=true>
				<input type="text" name="l_name" placeholder="Last Name *" required=true>
				<input type="text" name="uname" placeholder="User Name *" required=true>
				<input type="password" name="pwd" placeholder="Password *" required=true>
        		<input type="email" name="email" placeholder="Your Email *" required=true>
        		<input type="radio" onclick="javascript:roleCheck();" name="role" id="studentCheck" value="student" required=true>Student
        		<input type="radio" onclick="javascript:roleCheck();" name="role" id="facultyCheck" value="faculty" required=true> Faculty<br><br> 
				<input type='radio' id='spring' name='semester' value='Spring' disabled=true>Spring
				<input type='radio' id='summer' name='semester' value='Summer' disabled=true>Summer
				<input type='radio' id='fall' name='semester' value='Fall' disabled=true>Fall<br><br>
            </fieldset>
            <input type="submit" name="signup" value="Sign Up" />
        </form>
		<p><font color="red"> <?php echo nl2br($error_msg); ?> </p>
    </div>    
    
</html>