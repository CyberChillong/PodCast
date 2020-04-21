<?php

require_once "../Models/User.php";

class USER
{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();

    }

    private function registar()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $username = strtolower($_POST['username']);
        $resultado = $this->model->userRegistration($email, $password ,$username);
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
        $resultado=$this->model->login($email, $password);
        var_dump($resultado);
        if ($resultado !== false) {
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
$u->login();
