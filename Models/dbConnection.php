<?php


class dbConnection
{
    const caminho = "C:/xampp/htdocs/trabalhoPodcast/BaseDeDados/PODCAST.FDB";
    const user = "sysdba";
    const pass = "masterkey";
    private $connect;

    public function __construct()
    {
    }
    public function executarQueryUPDATEDELETE($query){
        $this->connect = ibase_connect(self::caminho, self::user, self::pass);
        $sth = ibase_query($this->connect, $query);
        ibase_close($this->connect);
        return ibase_fetch_assoc($sth) === false ? false : true;
    }
    public function executarQuerySelect($query){
        $this->connect = ibase_connect(self::caminho, self::user, self::pass);
        $sth = ibase_query($this->connect, $query);
        ibase_close($this->connect);
        $count = 0;
        while ($row[$count] = ibase_fetch_assoc($sth)){
            $count++;
        }
        return $row;
    }
}