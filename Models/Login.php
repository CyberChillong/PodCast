<?php

class Login extends Model {

    public function __construct()
    {
        parent::__construct();
        Session::init();


    }//__construct

    function credentialVerification(){

        $user_name = $_POST['user_name'];
        $password=md5($_POST['password']);



    }//run


}//Login
