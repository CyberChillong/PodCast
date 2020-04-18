<?php

require_once "dbConnection.php";

class User
{
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new dbConnection();
    }

    public function login($email, $password)
    {
        $password = hash("md5", $password);
        $query = "SELECT * FROM USERS WHERE EMAILS='" . $email . "' AND PASSWORD='" . $password . "'";
        return $this->dbConnection->executarQuerySelect($query) === false ? false : "Utilizador Encontrado";
    }

    public function registar($email, $password, $username)
    {
        if ($this->login($email, $password) === false) {
            $password = hash("md5", $password);
            $query = sprintf("INSERT INTO USERS (EMAILS,USERNAME,PASSWORD) VALUES('%s','%s','%s')", $email, $username, $password);
            return $this->dbConnection->executarQuerySelect($query) === false ? false : "SUCESSO";
        } else {
            return "Utilizador já registado";
        }
    }
}