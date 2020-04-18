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
        $aQueryResults = ibase_fetch_assoc(ibase_query($this->oDatabaseConnection,$strQuery));
        foreach ($aQueryResults as $Result){
            $strPasswordHash = $Result;
        }//foreach
        return $strPasswordHash;
    }//authentications



    function selectUser($pStrEmail){
        $strRect="";
        $strQuery = "SELECT ID FROM USERS";
        $sth = ibase_query($this->oDatabaseConnection, $strQuery);
        $count = 0;
        while($row[$count] = ibase_fetch_assoc($sth)){

            $count++;
        }//while
        for ($i=0; $i < $count ; $i++){
            $strRect.=$row[$i]["ID"].PHP_EOL;
        }
        return $strRect;
    }//selectUser
}//dbConnection

$con = new dbConnection();
echo($con->authentication("mail@mail.com"));