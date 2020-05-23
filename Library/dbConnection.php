<?php
namespace Library;

Class dbConnection {
    private $CONNECTION_STRING;
    const DATABASE_USER = "sysdba";
    const DATABASE_USER_PASSWORD = "masterkey";
    private $oDatabaseConnection ;

    public function __construct()
    {
        $this->CONNECTION_STRING=realpath("../").'/BD/Podcast.FDB';
    }//checkUserExistence

   public function insertUpdateDeleteDB($query){
    $this->oDatabaseConnection = ibase_connect($this->CONNECTION_STRING,self::DATABASE_USER,self::DATABASE_USER_PASSWORD);
    ibase_query($this->oDatabaseConnection, $query);
    ibase_close($this->oDatabaseConnection);
   }
   public function selectDB($query){
       $this->oDatabaseConnection = ibase_connect($this->CONNECTION_STRING,self::DATABASE_USER,self::DATABASE_USER_PASSWORD);
       $result= ibase_fetch_assoc(ibase_query($this->oDatabaseConnection,$query));
       ibase_close($this->oDatabaseConnection);
       return $result;
   }
    public function selectAllFromDB($pquery){
        $arrayDeResultados=array();
        $count = 0;
        $this->oDatabaseConnection = ibase_connect($this->CONNECTION_STRING,self::DATABASE_USER,self::DATABASE_USER_PASSWORD);
        $query=ibase_query($this->oDatabaseConnection,$pquery);
        $resultado=ibase_fetch_assoc($query);
        while ($resultado!==false ){
            $arrayDeResultados[$count]=$resultado;
            $resultado=ibase_fetch_assoc($query);
            $count++;
        } //while
        return $arrayDeResultados;
        ibase_close($this->oDatabaseConnection);

    }
}//dbConnection



