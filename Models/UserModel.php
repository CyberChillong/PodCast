<?php

Class UserModel{
    public $id;
    public $username;
    public $emails;

    public function __construct($pId, $pUsername, $email)
    {
    $this->id=$pId;
    $this->username=$pUsername;
    $this->emails=$email;
    }//__construct



}//UserModel
