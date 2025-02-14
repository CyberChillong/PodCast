<?php
namespace DAO;
use Library;
class dbuser
{
    private $db;

    public function __construct()
    {
        $this->db = new Library\dbConnection();
    }//__construct

    function authentication($pStrEmail, $pStrPassword)
    {
        $aUserInfo = [];
        $strQuery = sprintf("SELECT ID, USERNAME FROM USERS WHERE EMAILS= '%s' AND PASSWORD = '%s' AND ACTIVE=1 ", $pStrEmail,$pStrPassword);
        $oQueryResults = $this->db->selectDB($strQuery);
        if ($oQueryResults != null) { //if the query result is different than null
            foreach ($oQueryResults as $Result) {
                array_push($aUserInfo, $Result); //get the user info ID, USERNAME, EMAIL
            }//foreach
        }//if
        return $aUserInfo;
    }//authentications

    function updateUserEmail($pUserId ,$pNewEmail){
        $strQuery = sprintf("UPDATE USERS SET EMAILS= '%s' WHERE ID = '%f'", $pNewEmail, $pUserId);
        $this->db->insertUpdateDeleteDB($strQuery);
    }//updateUserEmail

    function deactiveUser($pUserId ){
        $strQuery = sprintf("UPDATE USERS SET ACTIVE= 0 WHERE ID = '%f'" , $pUserId);
        $this->db->insertUpdateDeleteDB($strQuery);
    }//updateUserEmail

    function updateUserPassword($pUserId,$pNewPasswordHash){
        $strQuery = sprintf("UPDATE USERS SET PASSWORD= '%s' WHERE ID = '%f'", $pNewPasswordHash, $pUserId);
        $this->db->insertUpdateDeleteDB($strQuery);
    }//updateUserPassword
    function updateUserUsername($pUserId,$pNewUsername){
        $strQuery = sprintf("UPDATE USERS SET USERNAME= '%s' WHERE ID = '%f'", $pNewUsername, $pUserId);
        $this->db->insertUpdateDeleteDB($strQuery);
    }//updateUserPassword

    function getUserFieldsUsingId($pUserId){
        $aResults = [];
        $strQuery = sprintf("SELECT EMAILS, USERNAME FROM USERS WHERE ID = '%f'",$pUserId);
        $oQueryResults = $this->db->selectDB($strQuery);
        if($oQueryResults != null){
            foreach ($oQueryResults as $Result){
                array_push($aResults, $Result);
            }//foreach
        }//if
        return $aResults;
    }//updateUserEmailUsingId

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
            $strQuery = sprintf("INSERT INTO USERS(USERNAME, PASSWORD ,EMAILS, ACTIVE) VALUES ('%s', '%s','%s',1) ",
                $pStrUsername, $pStrPasswordHash, $pStrEmail);
            $this->db->insertUpdateDeleteDB($strQuery);
            return true;
        }//if
        else {
            return false;
        }//else
    }//makeUserRegistration
}

