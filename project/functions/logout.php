<?php
session_start();
require_once './functies.php';
ErrorHandelerFun();
checkAuthStatus();
session_destroy();
header("Location: ../login.php");
?>