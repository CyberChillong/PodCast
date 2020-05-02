<?php
class PodcastModel {
    public $id;
    public $title;
    public $author;
    public $date;
    public $source;

    public function __construct($pId, $pTitle, $pAuthor, $pDate, $pSource)
    {
        $this->id=$pId;
        $this->title=$pTitle;
        $this->author=$pAuthor;
        $this->date=$pDate;
        $this->source=$pSource;

    }//__construct










}//PodcastModel