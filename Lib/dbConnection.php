<?php
Class dbConnection {
    private $CONNECTION_STRING;
    const DATABASE_USER = "sysdba";
    const DATABASE_USER_PASSWORD = "masterkey";
    private $oDatabaseConnection ;

    function __construct()
    {
        $caminho = $_SERVER['SCRIPT_FILENAME'];
        $caminho = dirname($caminho);
        $caminho = dirname($caminho);
        $this->CONNECTION_STRING= $caminho."/BD/Podcast.FDB";
    }//checkUserExistence

   public function insertUpdateDeleteDB($query){
    $this->oDatabaseConnection = ibase_connect($this->CONNECTION_STRING,self::DATABASE_USER,self::DATABASE_USER_PASSWORD);
    ibase_query($this->oDatabaseConnection, $query);;
    ibase_close($this->oDatabaseConnection);
   }
   public function selectDB($query){
       $this->oDatabaseConnection = ibase_connect($this->CONNECTION_STRING,self::DATABASE_USER,self::DATABASE_USER_PASSWORD);
       $resultado= ibase_fetch_assoc(ibase_query($this->oDatabaseConnection,$query));
       ibase_close($this->oDatabaseConnection);
       return $resultado;
   }

}//dbConnection



