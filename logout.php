<?php
Session_start();
Session_destroy();
die(header('Location: LogIn.html'));
?>