<?php
require_once "../Models/UserModel.php";
session_start();
$strResponse = sprintf("O user %s com o id %s e email %s efetou login com sucesso",
    $_SESSION['UserModel']->username,
    $_SESSION['UserModel']->id,
    $_SESSION['UserModel']->emails);
echo "<h1>$strResponse</h1>";
session_destroy();




