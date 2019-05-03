<?php

    ini_set("session.cookie_secure", 1);
	ini_set("session.cookie_httponly", 1);
	ini_set("session.use_cookies", 1);
	ini_set("session.use_only_cookies", 1);
	//ini_set("session.entropy_file", 1);
	ini_set("session.entropy_length", 16);

	session_start();

    if(isset($_SESSION['user'])) {


        include('dbConnect.php');
        include('Logging_activities.php');
        $uname = $_SESSION['user'];
        if(isset($_POST['uid'])){
          $Uid = $_POST['uid'];
        } else {
          $errormsg="User didnt select any choice";
          logInfoError($uname,$errormsg);
          session_destroy();
          header("Location: Error.php?errormsg=$errormsg");
        }
    		$queryforID = "SELECT Uid FROM User WHERE uname='$uname'";

    		if($resultqueryforID = $conn->query($queryforID)){
    			$Fid = $resultqueryforID->fetch_object()->Uid;
    		}else{
    			echo "error getting uid";
    		}

        $Cid = $_SESSION['cid'];
        $_SESSION['fid'] = $Fid;
        $_SESSION['uid'] = $Uid;

        $queryforGrade = "SELECT * FROM Enroll E, Course C Where E.Cid = C.Cid AND Uid = $Uid AND C.Cid = $Cid";
        $resultqueryforGrade = $conn->query($queryforGrade);

        $userdetailquerry = "SELECT year,semester,name FROM User where Uid = $Uid";
        $resultuserdetail = $conn->query($userdetailquerry);
        $userdatarow = mysqli_fetch_row($resultuserdetail);

        echo '<div class="form-style-5">';
        echo '<div class="form-style-6">';
        echo '<h1>Edit Grades</h1>';
        echo '</div>';
        echo "<form action='editGradeAPI.php' method='POST'>";
        echo '<fieldset>';
        echo '<p>Student ID: '.$Uid.'</p>';
        echo '<p>Student Name: '.$userdatarow[2].'</p>';
        echo '<p>Student Year: '.$userdatarow[0].'</p>';
        echo '<p>Student Semester: '.$userdatarow[1].'</p>';
        echo '<table style="width:100%">';
        echo '<tr>';
        echo '<th>Course No.</th>';
        echo '<th>Subject</th>';
        echo '<th>Faculty</th>';
        echo '<th>Grades</th>';
        echo '</tr>';
         while($row = mysqli_fetch_array($resultqueryforGrade)){
            echo '<tr style="width:100%">';
            echo '<td>' . $row['cnum'] . '</td>';
            echo '<td>' . $row['name'] .'</td>';
            $cnumdetailquerry = "SELECT name FROM User where Uid = $Fid";
            $resultcnumdetail = $conn->query($cnumdetailquerry);
            $cnumresult = mysqli_fetch_row($resultcnumdetail);
            echo '<td>' .$cnumresult['0']. '</td>';
            if(isset($row['grade']))
            {
              echo '<td><input type="text" style="width:100%; color:#292d30;" name="gradeField" pattern="[A-Z]{1}" placeholder="Input Grade *" value="'.$row['grade'].'"></td>';
            }else {
              echo '<td><input type="text" style="width:100%; color:#292d30;" name="gradeField" pattern="[A-Z]{1}" placeholder="Input Grade *"></td>';
            }
            //echo '<td>' . $row['grade'] .'</td>';
            echo '</tr>';
         }
        echo '</table>';
        echo '</fieldset>';
  			echo '<input type="submit" name="Submit" value="Submit Changes" />';
        echo '</form>';
        echo "<form action='logout.php' method='POST'>";
  			echo '<input type="submit" name="logout" value="Logout" />';
  			echo '</form>';
        echo '</div>';

    }else {
        die(header("Location: LogIn.html"));
    }
?>

<style>
<?php include 'css/yourgrade.css'; ?>
</style>
