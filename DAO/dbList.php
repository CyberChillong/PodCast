<?php
namespace DAO;
use Library\dbConnection;
use Models\ListsModel;


require_once "../Library/dbConnection.php";
require_once "../Models/ListsModel.php";

class dbList {
    private $db;

    public function __construct(){
        $this -> db = new dbConnection();
    }//__construct

    public function createList($pUserId, $pName){
        $this->db->insertUpdateDeleteDB(sprintf("INSERT INTO LISTS (NAME, USERS_ID,ACTIVE) VALUES ('%s', '%s',1 );",$pName,$pUserId));
    }//createList

    public function getUserLists($UserId){
        $UserListObjects = [];
        $Rows = $this->db->selectAllFromDB(sprintf("SELECT ID, NAME FROM LISTS WHERE USERS_ID= %s AND ACTIVE=1",$UserId));
        foreach ($Rows as $row){
            array_push($UserListObjects, new ListsModel($row["ID"],$row["NAME"]));
        }//foreach
        return $UserListObjects;
    }//getUserLists
    public function getUserListByUserIDAndName($UserId,$Name){
        $UserListObjects = [];
       $Rows = $this->db->selectAllFromDB(sprintf("SELECT ID FROM LISTS WHERE USERS_ID= %s AND NAME= '%s'",$UserId,$Name));
       if(count($Rows)>0) {
       foreach ($Rows as $row){
            array_push($UserListObjects, new ListsModel($row["ID"],$Name));
        }//foreach
       }
        return $UserListObjects;

    }//getUserLists

    public function isListActive($pStrListId){
       $List = $this->db->selectDB(sprintf("SELECT ACTIVE FROM LISTS WHERE ID= %s ;", $pStrListId));
       var_dump($List);
       if($List['ACTIVE'] === 1 ){
           return true;
       }//if
        else {
            return false;
        }//else
    }//isListActive

    public function getListNameById($pStrListId){
        return $this->db->selectDB(sprintf("SELECT NAME FROM LISTS WHERE ID = %s",$pStrListId));
    }//getListNameById

    public function nameUpdate($pListId, $pNewName){
        $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTS SET NAME = '%s' WHERE ID = '%s';",$pNewName,$pListId));

    }//NameUpdate

    public function deactivateList($pListId){
        $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTS SET Active = 0 WHERE ID = %s",$pListId));
        $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTPODCAST SET Active = 0 WHERE LIST_ID = %s",$pListId));
    }//deactivateList


    public function activateList($pStrListName, $pStrUserId){
        $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTS SET ACTIVE = 1 WHERE USERS_ID = %s AND NAME= '%s' ",$pStrUserId,$pStrListName));
        $aListId = $this->db->selectDB(sprintf("SELECT ID FROM LISTS WHERE USERS_ID = %s AND NAME= '%s'",$pStrUserId,$pStrListName));
        $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTPODCAST SET Active = 1 WHERE LIST_ID = %s",$aListId['ID']));
    }//activateList

}//List
