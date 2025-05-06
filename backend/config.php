<?php
session_start();
session_unset();
session_destroy();
header("Location: ../front/inicio.php");

exit();

?>