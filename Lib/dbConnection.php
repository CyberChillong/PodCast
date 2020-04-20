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
        if($oQueryResults!=null){
            foreach ($oQueryResults as $Result){
                $strPasswordHash = $Result;
            }//foreach
            return $strPasswordHash;
        }//if
        else{
            return false;
        }//else
    }//authentications


}//dbConnection

//$con = new dbConnection();
//var_dump($con->authentication("mail@mail.com"));