<?php
session_start();
session_destroy();
header("Location: ../src/Home.php");
exit();
?>
