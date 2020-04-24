<?php

require_once "../Lib/dbConnection.php";

class dbUser
{
    private $db;

    function __construct()
    {
        $this->db = new dbConnection();
    }

    function authentication($pStrEmail)
    {
        $strPasswordHash = "";
        $strQuery = sprintf("SELECT PASSWORD FROM USERS WHERE EMAILS= '%s'", strtolower($pStrEmail));
        $oQueryResults = $this->db->selectDB($strQuery);
        if ($oQueryResults != null) { //if the query result is different than null
            foreach ($oQueryResults as $Result) {
                $strPasswordHash = $Result; //get the password hash from Query results
            }//foreach
            return $strPasswordHash; //return the password Hash
        }//if
        else {
            return false; // if the query result is null means that email account does not exist
        }//else
    }//authentications

    function isEmailUnique($pStrEmail)
    {
        $strQuery = sprintf("SELECT EMAILS FROM USERS WHERE EMAILS='%s'", strtolower($pStrEmail));
        if ($this->db->selectDB($strQuery) != null) {
            return false;
        }//if
        else {
            return true;
        }//else
    }//isEmailUnique

    function isUsernameUnique($pStrUsername)
    {
        $strQuery = sprintf("SELECT USERNAME FROM USERS WHERE USERNAME='%s'", strtolower($pStrUsername));
        if ($this->db->selectDB($strQuery) != null) {
            return false;
        }//if
        else {
            return true;
        }//else
    }//isUsernameUnique

    function isPasswordUnique($pStrPasswordHash)
    {
        $strQuery = sprintf("SELECT PASSWORD FROM USERS WHERE PASSWORD='%s'", $pStrPasswordHash);
        if ($this->db->selectDB($strQuery) != null) {
            return false;
        }//if
        else {
            return true;
        }//else
    }//isPasswordUnique

    function makeUserRegistration($pStrUsername, $pStrPasswordHash, $pStrEmail)
    {
        $bIsThisPasswordUnique = $this->isPasswordUnique($pStrPasswordHash);
        $bIsThisUsernameUnique = $this->isUsernameUnique($pStrUsername);
        $bIsThisEmailUnique = $this->isEmailUnique($pStrEmail);
        if (($bIsThisPasswordUnique && $bIsThisEmailUnique && $bIsThisUsernameUnique) === true) {
            $strQuery = sprintf("INSERT INTO USERS(USERNAME, PASSWORD ,EMAILS ) VALUES ('%s', '%s','%s') ",
                strtolower($pStrUsername), $pStrPasswordHash, strtolower($pStrEmail));
            $this->db->insertUpdateDeleteDB($strQuery);
            return true;
        }//if
        else {
            return false;
        }//else
    }//makeUserRegistration
}