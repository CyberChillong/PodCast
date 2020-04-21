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
        $oPasswordHash = $this->dbConnection->authentication(strtolower($email));
        return $oPasswordHash === $password ? true:false;
    }

    public function userRegistration($email, $password, $username)
    {

        return $this->dbConnection->makeUserRegistration($username,hash("md5", strtolower($password)),strtolower($email));

    }
}