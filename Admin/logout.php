<?php

session_start();
unset($_SESSION['time']);
header("Location:login.php");
exit();


?>