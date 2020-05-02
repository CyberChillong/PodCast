<?php

require_once "../Models/UserModel.php";
require_once "../Lib/dbUser.php";
class USER
{
    private $model;
    private $dbUser;

    public function __construct()
    {
        $this->dbUser=new dbUser();
    }

    private function registar()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $username = strtolower($_POST['username']);
        $password=hash("md5", strtolower($password));
        $email=strtolower($email);
        $resultado = $this->dbUser->makeUserRegistration($username, $password ,$email);
        var_dump($resultado);
        if ($resultado !== false) {
            $_SESSION['LoginStatus'] = "Your registrations was made whit success, now you can made the login";
           header("Location:../../View/login.php");
        }
    }

    public function login()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $password = hash("md5", $password);
        $oPasswordHash = $this->dbUser->authentication(strtolower($email));
        $verificaçãoDePassword=$oPasswordHash === $password ? true:false;
        if ($verificaçãoDePassword !== false) {
            $_SESSION['email']= $email;
            header("Location:../../View/UserView.php");
        }else{
            $_SESSION['LoginStatus'] = "Your Logging has fail verify your credentials our create an account";
            header("Location:../../View/login.php");

        }

    }


    public function escolha()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "reg") {
            $this->registar();
        } else if ($pathInfo[1] === "log") {
            $this->login();
        }
    }
}
$u = new User();
$u->escolha();
