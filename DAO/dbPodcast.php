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

    public function insertPodcast ($pTitle, $pDESCRIPTION, $pDate, $pSources){
        $this->db->insertUpdateDeleteDB(sprintf("INSERT INTO PODCASTS (TITLE, DESCRIPTION, DATES, SOURCES,ACTIVE) VALUES ('%s', '%s', '%s', '%s','%s');",
        $pTitle, $pDESCRIPTION, $pDate, $pSources,1));


    }//insertPodcast

    public function getPodCastFromId($podcastId){

        return $this->db->selectAllFromDB(sprintf("SELECT TITLE,DESCRIPTION,DATES,SOURCES FROM PODCASTS WHERE ID= %s",$podcastId));

    }//getPodcastFromId
    public function getPodCastFromSource($podcastSource){
        return $this->db->selectAllFromDB("SELECT ID FROM PODCASTS WHERE SOURCES= '".$podcastSource."'");

    }//getPodcastFromId


}//dbPodcast
