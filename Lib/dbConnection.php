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

    function isEmailUnique($pStrEmailHash){

    }//isEmailUnique



    function isUsernameUnique($pStrUsername){

    }//isUsernameUnique


    function isPasswordUnique($pStrPasswordHash){

    }//isPasswordUnique

    function makeUserRegistration($pStrUsername, $pStrPasswordHash, $pStrEmail ){


    }//makeUserRegistration

}//dbConnection

//$con = new dbConnection();
//var_dump($con->authentication("mail@mail.com"));