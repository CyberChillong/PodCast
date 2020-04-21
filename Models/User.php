<?php

require_once "../Lib/dbConnection.php";

class UserModel
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
        return count($this->dbConnection->executarQuerySelect($query)) === 2? "Utilizador Encontrado":false;
    }

    public function registar($email, $password, $username)
    {
        if ($this->login($email, $password) === false) {
            $password = hash("md5", $password);
            $query = sprintf("INSERT INTO USERS (EMAILS,USERNAME,PASSWORD) VALUES('%s','%s','%s')", $email, $username, $password);
            return $this->dbConnection->executarQueryUPDATEDELETEINSERT($query) === false ? "Erro na Base de Dados" : true;
        } else {
            return false;
        }
    }
}