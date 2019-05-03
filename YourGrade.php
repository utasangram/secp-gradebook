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
        $uname = $_SESSION['user'];
		$queryforID = "SELECT Uid FROM User WHERE uname='$uname'";

		if($resultqueryforID = $conn->query($queryforID)){
			$userId = $resultqueryforID->fetch_object()->Uid;
		}else{
			echo "error getting uid";
		}

        $queryforGrade = "SELECT * FROM Enroll E, Course C Where E.Cid = C.Cid AND Uid = $userId";
        $resultqueryforGrade = $conn->query($queryforGrade);

        $userdetailquerry = "SELECT year,semester FROM User where Uid = $userId";
        $resultuserdetail = $conn->query($userdetailquerry);
        $userdatarow = mysqli_fetch_row($resultuserdetail);

        echo '<div class="form-style-5">';
        echo '<div class="form-style-6">';
        echo '<h1>Your Grades</h1>';
        echo '</div>';
        echo '<form>';
        echo '<fieldset>';
        echo '<p>Year: '.$userdatarow[0].'</p>';
        echo '<p>Semester: '.$userdatarow[1].'</p>';
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
            $fid = $row['Fid'];
            $cnumdetailquerry = "SELECT name FROM User where Uid = $fid";
            $resultcnumdetail = $conn->query($cnumdetailquerry);
            $cnumresult = mysqli_fetch_row($resultcnumdetail);
            echo '<td>' .$cnumresult['0']. '</td>';
            echo '<td>' . $row['grade'] .'</td>';
            echo '</tr>';
         }
        echo '</table>';
        echo '</fieldset>';
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
