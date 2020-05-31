<?php
namespace Models;
class PodcastModel {
    public $id;
    public $title;
    public $description;
    public $date;
    public $source;

    public function __construct($pId, $pTitle, $pDescription, $pDate, $pSource)
    {
        $this->id=$pId;
        $this->title=$pTitle;
        $this->description=$pDescription;
        $this->date=$pDate;
        $this->source=$pSource;

    }//__construct










}//PodcastModel