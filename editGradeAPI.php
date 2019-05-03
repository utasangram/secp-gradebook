<?php
ini_set("session.cookie_secure", 1);
ini_set("session.cookie_httponly", 1);
ini_set("session.use_cookies", 1);
ini_set("session.use_only_cookies", 1);
//ini_set("session.entropy_file", 1);
ini_set("session.entropy_length", 16);

session_start();
  $UpdatedGrade = $_POST['gradeField'];
  if(isset($_SESSION['user']) && isset($_SESSION['uid']) && isset($_SESSION['cid']) && isset($_SESSION['fid'])) {
    include('dbConnect.php');
    include('Logging_activities.php');
    $uname = $_SESSION['user'];
    $Uid = $_SESSION['uid'];
    $Cid = $_SESSION['cid'];
    $Fid = $_SESSION['fid'];

    $sql = "UPDATE Enroll SET grade='$UpdatedGrade' WHERE Uid='$Uid' AND Cid='$Cid'";

    if ($conn->query($sql) === TRUE) {
      echo ("<script LANGUAGE='JavaScript'>
          window.alert('Succesfully Updated');
          window.location.href='FacultyPortal.php';
          </script>");
    } else {
        logInfoError($uname,$conn->error);
    }

  }else {
      die(header("Location: LogIn.html"));
  }
?>
