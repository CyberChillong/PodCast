<?php
    session_start();
    //$_SESSION['UserModel']=null;
    session_destroy();
    header("Location:../View/index.php");




