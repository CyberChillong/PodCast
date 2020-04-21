<?php
session_start();
echo "<h1>Estás Logado ".$_SESSION['email']." Parabéns</h1>";
session_destroy();




