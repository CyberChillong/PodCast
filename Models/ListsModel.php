<?php
namespace Models;
class ListsModel {
    public $strId;
    public $strName;


    public function __construct($pStrId, $pStrName)
    {
        $this->strId = $pStrId;
        $this->strName = $pStrName;

    }//__construct


}//ListsModel