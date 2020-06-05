<?php
namespace Controller;
require "../vendor/autoload.php";
use DAO\dbuser;
use Models\UserModel;
class User
{
    private $dbUser;
    public function __construct()
    {
        $this->dbUser = new dbuser();
    }

    private function registar()
    {
        if (session_status() !== 2) {
            session_start();
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];
        $username = strtolower($_POST['username']);
        $password = hash("md5", $password);
        if($this->dbUser->isPasswordUnique($password)&&$this->dbUser->isUsernameUnique($username)&&$this->dbUser->isEmailUnique($email)){
        $resultado = $this->dbUser->makeUserRegistration($username, $password, $email);
        if ($resultado === true) {
            $_SESSION['RegistrationStatus'] = "Your registrations was made whit success, now you can made the login";
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/login.php";
            header("Location:" . $pathToListenerPodcast);
        } else {
            $_SESSION['RegistrationStatus'] = "You have failed the registration process please repeat and verify your data";
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/registar.php";
            header("Location:" . $pathToListenerPodcast);
        }
        }else {
            $_SESSION['RegistrationStatus'] = "You have failed the registration process because your data is already being used";
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/registar.php";
            header("Location:" . $pathToListenerPodcast);
        }
    }//registar

    public function login()
    {
        if (session_status() !== 2) {
            session_start();
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];
        $passwordHash = hash("md5", $password);
        $aResults = $this->dbUser->authentication($email, $passwordHash);
        if (count($aResults) > 0) {
            $_SESSION['UserModel'] = new UserModel($aResults[0], $aResults[1], $email, $password);
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/index.php";
            header("Location:" . $pathToListenerPodcast);
        } else {
            $_SESSION['LoginStatus'] = "Your Logging has fail verify your credentials our create an account our account is desable";
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/login.php";
            header("Location:" . $pathToListenerPodcast);

        }

    }

    private function editar()
    {
        if (session_status() !== 2) {
            session_start();
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];
        $username = strtolower($_POST['username']);
        $passwordHash = hash("md5", $password);
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
        $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/editAcount.php";
        header("Location:" . $pathToListenerPodcast);
    }//editarConta


    private function desativarUser(){
        if (session_status() !== 2) {
            session_start();
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        $id = $_SESSION["UserModel"]->id;
        $this->dbUser->deactiveUser($id);
        session_destroy();

        $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/index.php";
        header("Location:" . $pathToListenerPodcast);
        header("Location:../../View/index.php");
    }
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
        } else if ($pathInfo[1] === "deac") {
            $this->desativarUser();
        }
    }
}

$u = new User();
$u->escolha();
