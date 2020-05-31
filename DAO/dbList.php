<?php
namespace DAO;
use Library\dbConnection;
use Models\ListsModel;


require_once "../Library/dbConnection.php";
require_once "../Models/ListsModel.php";
//use Library;
//use Models;

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
        $Rows = $this->db->selectAllFromDB(sprintf("SELECT ID, NAME FROM LISTS WHERE USERS_ID= '%s'",$UserId));
        foreach ($Rows as $row){
            array_push($UserListObjects, new ListsModel($row["ID"],$row["NAME"]));
        }//foreach
        return $UserListObjects;
    }//getUserLists
    public function getUserListByUserIDAndName($UserId,$Name){
        $UserListObjects = [];

       $Rows = $this->db->selectAllFromDB(sprintf("SELECT ID FROM LISTS WHERE USERS_ID= %s AND NAME='%s'",$UserId,$Name));
       if(count($Rows)>0) {
       foreach ($Rows as $row){

            array_push($UserListObjects, new ListsModel($row["ID"],$Name));
        }//foreach
       }
        return $UserListObjects;

    }//getUserLists

    public function nameUpdate($pListId, $pNewName){
        $this->db->insertUpdateDeleteDB(sprintf("UPDATE LISTS SET NAME = '%s' WHERE ID = %s;",$pNewName,$pListId));

    }//NameUpdate



}//List
