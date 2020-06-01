<?php
namespace Library;
use Library;
class anchorfm
{
    private $oJsonresponse;
    public $aSavedXMLData;
    private $a;
    function __construct()
    {   $this->a=[];
        $this->aSavedXMLData = [];
    }//construct

    public function getXmlResponse($pstrKeyWord)
    {
        $this->oJsonresponse = json_decode(file_get_contents(sprintf("https://itunes.apple.com/search?media=podcast&term=%s", $pstrKeyWord)), "true");
        $aXmlResponse = simplexml_load_string(file_get_contents($this->oJsonresponse["results"][0]["feedUrl"]));
        foreach ($aXmlResponse->channel->item as $aItems) {
            $oRsult = new Library\conteudoXML((string)$aItems->pubDate,(string)$aItems->description,(string)$aItems->title,(string)$aItems->enclosure["url"]);
            array_push($this->aSavedXMLData, $oRsult);
        }
        return $this->aSavedXMLData;
    }
}