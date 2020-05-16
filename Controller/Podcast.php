<?php


namespace Controller;
require "../vendor/autoload.php";
require_once "../Library/conteudoXML.php";
use Library;
class Podcast
{
    private $anchorfm;

    public function __construct()
    {
    $this->anchorfm=new Library\anchorfm();
    }

    private function searchPodcast()
    {   session_start();
        $pSearch=$_POST["searchExpression"];
        $pSearch=explode(" ",$pSearch);
        $searchQuery=null;
        for ($i=0;$i<count($pSearch);$i++){
            if($i+1===count($pSearch)){
                $searchQuery.=$pSearch[$i];
            }else{
           $searchQuery.=$pSearch[$i]."+";
            }
        }
        $resultado=$this->anchorfm->getXmlResponse($searchQuery);

        $_SESSION['Podcast']=$resultado;
       header("Location:../../View/index.php");
    }

    public function escolha()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "ser") {
            $this->searchPodcast();
        }
    }
}
$u = new Podcast();
$u->escolha();