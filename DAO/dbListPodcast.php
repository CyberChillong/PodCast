<?php


namespace DAO;

use Library\dbConnection;
use Models\ListsModel;

require_once "../Library/dbConnection.php";
require_once "../Models/ListsModel.php";

class dbListPodcast{
    private $db;
    public function __construct()
    {
         $this->db = new dbConnection();
    }//__construct

    public function InsertPodcastOnList($pListId, $pPodcastId){
        $this->db->insertUpdateDeleteDB(sprintf("INSERT INTO LISTPODCAST (LIST_ID, PODCAST_ID) VALUES ('%s', '%s' );",$pListId,$pPodcastId));
    }//createList

    public function getPodCastFromLists($ListId){

         return $this->db->selectAllFromDB(sprintf("SELECT PODCAST_ID FROM LISTPODCAST WHERE lIST_ID= %s",$ListId));

    }//getUserLists





}//dbListPodCast

