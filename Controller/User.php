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
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['username'];
        $resultado = $this->model->registar($email, $password, $username);
        IF ($resultado !== false && $resultado !== "Erro na Base de Dados") {
           header("Location:../../View/login.php");
        }
    }

    private function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $resultado=$this->model->login($email, $password);
        var_dump($resultado);
        IF ($resultado !== false) {
            header("Location:../../View/index.php");
        }else{
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