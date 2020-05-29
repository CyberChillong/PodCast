<?php


namespace DAO;

use Library\dbConnection;
require_once "../Library/dbConnection.php";
//require_once "../Models/ListsModel.php";
class dbPodcast {

    private $db;
    public function __construct()
    {
        $this->db = new dbConnection();
    }//__construct

    public function insertPodcast ($pTitle, $pAuthor, $pDate, $pSources){

        $this->db->insertUpdateDeleteDB(sprintf("INSERT INTO PODCASTS (TITULO, AUTHOR, DATES, SOURCES) VALUES ('%s', '%s', '%s', '%s');",
        $pTitle, $pAuthor, $pDate, $pSources));


    }//insertPodcast

    public function getPodCastFromId($podcastId){

        return $this->db->selectAllFromDB(sprintf("SELECT TITULO,AUTHOR,DATES,SOURCES FROM PODCASTS WHERE ID= %s",$podcastId));

    }//getPodcastFromId
    public function getPodCastFromSource($podcastSource){

        return $this->db->selectAllFromDB('SELECT ID FROM PODCASTS WHERE SOURCES= '.$podcastSource.'');

    }//getPodcastFromId


}//dbPodcast

$o = new dbPodcast();
$o->insertPodcast("blockchain", "Soldier Dario", "2020/05/23", "kfsv7/sg6&sd/sdgfbdsf");