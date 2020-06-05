<?php


namespace DAO;

use Library\dbConnection;

require_once "../Library/dbConnection.php";
require_once "../Models/ListsModel.php";

class dbListPodcast{
    private $db;
    public function __construct()
    {
         $this->db = new dbConnection();
    }//__construct

    public function InsertPodcastOnList($pListId, $pPodcastId,$date){
        $this->db->insertUpdateDeleteDB(sprintf("INSERT INTO LISTPODCAST (LIST_ID, PODCAST_ID, ACTIVE, DATEINSERTION) VALUES ('%s', '%s',1, '%s' );",$pListId,$pPodcastId,$date));
    }//createList

    public function getPodCastFromLists($ListId){

         return $this->db->selectAllFromDB(sprintf("SELECT PODCAST_ID FROM LISTPODCAST WHERE lIST_ID= %s AND ACTIVE = 1 ORDER BY DATEINSERTION DESC",$ListId));

    }//getUserLists
    public function getPodCastFromListsIdAndPodcastId($ListId,$podcastId){

        return $this->db->selectAllFromDB(sprintf("SELECT ID FROM LISTPODCAST WHERE lIST_ID= %s AND PODCAST_ID=%s AND ACTIVE = 1",$ListId,$podcastId));

    }//getUserLists

    public function deletePodCast($pStrListId,$pStrPodcastId){

         $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTPODCAST SET ACTIVE=0 WHERE LIST_ID= %s AND PODCAST_ID = %s",$pStrListId,$pStrPodcastId));

    }//getUserLists
    public function countPodCastFromLists($ListId){

        return $this->db->selectAllFromDB(sprintf("SELECT count(ID) FROM LISTPODCAST WHERE lIST_ID= %s AND ACTIVE = 1",$ListId));

    }//getUserLists





}//dbListPodCast

