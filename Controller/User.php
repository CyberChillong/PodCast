<?php

require_once "../Models/UserModel.php";
require_once "../Lib/dbUser.php";
class USER
{

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
        if ($resultado !== false) {
            $_SESSION['RegistrationStatus'] = "Your registrations was made whit success, now you can made the login";
           header("Location:../../View/login.php");
        }else{
            $_SESSION['RegistrationStatus'] = "You have failed the registration process please repeat and verify your data";
            header("Location:../../View/registar.php");
        }
    }//registar

    private function login()
    {
        session_start();
        $email = strtolower($_POST['email']);
        $password = strtolower($_POST['password']);
        $password = hash("md5", $password);
        $aResults = $this->dbUser->authentication( $email, $password);
        if (count($aResults) > 0 ) {
            var_dump($aResults);
            $_SESSION['UserModel'] = new UserModel($aResults[0], $aResults[1],$email);
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
