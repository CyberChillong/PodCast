<?php

require_once "../Models/UserModel.php";
require_once "../Lib/dbUser.php";

class USER
{

    private $dbUser;

    public function __construct()
    {
        $this->dbUser = new dbUser();
    }

    private function registar()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $username = strtolower($_POST['username']);
        $password = hash("md5", strtolower($password));
        $email = strtolower($email);
        $resultado = $this->dbUser->makeUserRegistration($username, $password, $email);
        if ($resultado === true) {
            $_SESSION['RegistrationStatus'] = "Your registrations was made whit success, now you can made the login";
            header("Location:../../View/login.php");
        } else {
            $_SESSION['RegistrationStatus'] = "You have failed the registration process please repeat and verify your data";
            header("Location:../../View/registar.php");
        }
    }//registar

    private function login()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $passwordHash = hash("md5", $password);
        $aResults = $this->dbUser->authentication($email, $passwordHash);
        if (count($aResults) > 0) {
            $_SESSION['UserModel'] = new UserModel($aResults[0], $aResults[1], $email, $password);
            header("Location:../../View/index.php");
        } else {
            $_SESSION['LoginStatus'] = "Your Logging has fail verify your credentials our create an account";
            header("Location:../../View/login.php");

        }

    }

    private function editar()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $username = strtolower($_POST['username']);
        $passwordHash = hash("md5", strtolower($password));
        $email = strtolower($email);
        $id = $_SESSION["UserModel"]->id;
        $_SESSION['EditAccountStatus'] = null;
        if ($email !== $_SESSION["UserModel"]->emails) {
            if ($this->dbUser->isEmailUnique($email)) {
                $this->dbUser->updateUserEmail($id, $email);
                $_SESSION["UserModel"]->emails=$email;
            } else {
                $_SESSION['EditAccountStatus'] = "You have failed the edit of your account because new email is already being used";
            }
        }
        if ($password !== $_SESSION["UserModel"]->password) {
            if ($this->dbUser->isPasswordUnique($passwordHash)) {
                $this->dbUser->updateUserPassword($id, $passwordHash);
                $_SESSION["UserModel"]->password=$password;
            } else if ($_SESSION['EditAccountStatus'] === null) {
                $_SESSION['EditAccountStatus'] = "You have failed the edit of your account because new password is already being used";
            } else {
                $_SESSION['EditAccountStatus'] = $_SESSION['EditAccountStatus'] . " and new password is already being used";
            }
        }
        if ($username !== $_SESSION["UserModel"]->username) {
            if ($this->dbUser->isUsernameUnique($username)) {
                $this->dbUser->updateUserUsername($id, $username);
                $_SESSION["UserModel"]->username=$username;
            } else if ($_SESSION['EditAccountStatus'] === null) {
                $_SESSION['EditAccountStatus'] = "You have failed the edit of your account because new username is already being used";
            } else {
                $_SESSION['EditAccountStatus'] = $_SESSION['EditAccountStatus'] . " and new username is already being used";
            }
        }
        if($_SESSION['EditAccountStatus']===null){
            $_SESSION['EditAccountStatus'] = "Your edit of account was made whit success";
        }
        header("Location:../../View/editAcount.php");
    }//editarConta


    public function escolha()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "reg") {
            $this->registar();
        } else if ($pathInfo[1] === "log") {
            $this->login();
        } else if ($pathInfo[1] === "edit") {
            $this->editar();
        }
    }
}

$u = new User();
$u->escolha();
