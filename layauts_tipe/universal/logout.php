<?php
session_start();
session_unset();
session_destroy();

header("Location: /Proyecto_Grado/index.php");
?>