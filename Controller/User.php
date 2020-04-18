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
        var_dump($resultado);
    }

    private function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $this->model->login($email, $password);

    }

    public function escolha()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "reg") {
            $this->registar();
        } elseif ($pathInfo[1] === "log") {
            $this->login();
        }
    }
}

$u = new User();
$u->escolha();