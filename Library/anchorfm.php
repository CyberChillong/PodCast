<?php

namespace Library;

use Models\PodcastModel;

class anchorfm
{
    private $oJsonresponse;
    public $aSavedXMLData;
    private $a;

    function __construct()
    {
        $this->a = [];
        $this->aSavedXMLData = [];
    }//construct

    public function getXmlResponse($pstrKeyWord)
    {
        $this->oJsonresponse = json_decode(file_get_contents(sprintf("https://itunes.apple.com/search?media=podcast&term=%s", $pstrKeyWord)), "true");
        $aXmlResponse = simplexml_load_string(file_get_contents($this->oJsonresponse["results"][0]["feedUrl"]));
        foreach ($aXmlResponse->channel->item as $aItems) {
            $variableToDecideSource = explode("https", $aItems->enclosure["url"]);
            if (count($variableToDecideSource) > 1) {
                $variableToConstructTheStringOfSource = urldecode($variableToDecideSource[2]);
                $source = "http" . $variableToConstructTheStringOfSource;
            } else {
                $source = $aItems->enclosure["url"];
            }
            $oRsult = new PodcastModel(0, (string)$aItems->title, (string)$aItems->description, (string)$aItems->pubDate, (string)$source);
            array_push($this->aSavedXMLData, $oRsult);
        }
        return $this->aSavedXMLData;
    }
}