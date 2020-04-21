<?php

Class dbConnection {
    const CONNECTION_STRING = "D:\PodCastDB\PODCASTDB.FDB";
    const DATABASE_USER = "sysdba";
    const DATABASE_USER_PASSWORD = "masterkey";
    public $oDatabaseConnection ;

    function __construct()
    {
        $this->oDatabaseConnection = ibase_connect(self::CONNECTION_STRING,self::DATABASE_USER,self::DATABASE_USER_PASSWORD);
    }//checkUserExistence

    function authentication( $pStrEmail) {
        $strPasswordHash="";
        $strQuery = sprintf("SELECT PASSWORD FROM USERS WHERE EMAILS= '%s'",$pStrEmail);
        $oQueryResults = ibase_fetch_assoc(ibase_query($this->oDatabaseConnection,$strQuery));
        if($oQueryResults!=null){//if the query result is different than null
            foreach ($oQueryResults as $Result){
                $strPasswordHash = $Result; //get the password hash from Query results
            }//foreach
            return $strPasswordHash; //return the password Hash
        }//if
        else{
            return false; // if the query result is null means that email account does not exist
        }//else
    }//authentications

    function isEmailUnique($pStrEmail){
        $strQuery = sprintf("SELECT EMAILS FROM USERS WHERE EMAILS='%s'",$pStrEmail);
        if(ibase_fetch_assoc(ibase_query($this->oDatabaseConnection, $strQuery))!=null){
            return true;
        }//if
        else{
            return false;
        }//else
    }//isEmailUnique

    function isUsernameUnique($pStrUsername){
        $strQuery = sprintf("SELECT USERNAME FROM USERS WHERE USERNAME='%s'",$pStrUsername);
        if(ibase_fetch_assoc(ibase_query($this->oDatabaseConnection, $strQuery))!=null){
            return true;
        }//if
        else{
            return false;
        }//else
    }//isUsernameUnique

    function isPasswordUnique($pStrPasswordHash){
        $strQuery = sprintf("SELECT PASSWORD FROM USERS WHERE PASSWORD='%s'",$pStrPasswordHash);
        if(ibase_fetch_assoc(ibase_query($this->oDatabaseConnection, $strQuery))!=null){
            return true;
        }//if
        else{
            return false;
        }//else
    }//isPasswordUnique
  
  function makeUserRegistration($pStrUsername, $pStrPasswordHash, $pStrEmail ){
        $bIsThisPasswordUnique = $this->isPasswordUnique($pStrPasswordHash);
        $bIsThisUsernameUnique = $this->isUsernameUnique($pStrUsername);
        $bIsThisEmailUnique = $this->isEmailUnique($pStrEmail);
        if(!($bIsThisPasswordUnique  && $bIsThisEmailUnique && $bIsThisUsernameUnique )){
            $strQuery = sprintf("INSERT INTO USERS(USERNAME, PASSWORD ,EMAILS ) VALUES ('%s', '%s','%s') ",
            $pStrUsername, $pStrPasswordHash, $pStrEmail);
            ibase_query($this->oDatabaseConnection, $strQuery);
            return true;
        }//iff
        else{
            return false;
        }//else
    }//makeUserRegistration

}//dbConnection

//$con = new dbConnection();
//var_dump( $con->makeUserRegistration("RMB", "powerred97","mail@gmail.com"));
