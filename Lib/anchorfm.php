<?php
include ("conteudoXML.php");
class anchorfm
{
    private $oJsonresponse;
    public $aSavedXMLData;
    function __construct($pstrKeyWord)
    {
        $this->oJsonresponse = json_decode(file_get_contents(sprintf("https://itunes.apple.com/search?media=podcast&term=%s", $pstrKeyWord)), "true");
        $this->aSavedXMLData = [];
    }//construct

    public function getXmlResponse()
    {
        $aXmlResponse = simplexml_load_string(file_get_contents($this->oJsonresponse["results"][0]["feedUrl"]));
        foreach ($aXmlResponse->channel->item as $aItems) {
            $oRsult = new conteudoXML($aItems->pubDate,$aItems->description,$aItems->title, $aItems->enclosure["url"]);
            array_push($this->aSavedXMLData, $oRsult);
        }
    }
}
$req = new anchorfm("Pedro+Mota+Teixeira");
$req->getXmlResponse();
var_dump($req->aSavedXMLData);