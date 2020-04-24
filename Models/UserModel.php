<?php

Class UserModel{
public $id;
public $username;
public $password;
public $emails;

public function __construct($model)
{
    $this->id=$model["ID"];
    $this->username=$model["USERNAME"];
    $this->password=$model["PASSWORD"];
    $this->emails=$model["EMAILS"];

}
}