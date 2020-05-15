<?php
namespace Models;
Class UserModel{
    public $id;
    public $username;
    public $emails;
    public $password;

    public function __construct($pId, $pUsername, $email,$password)
    {
    $this->id=$pId;
    $this->username=$pUsername;
    $this->emails=$email;
    $this->password=$password;
    }//__construct




}//UserModel
