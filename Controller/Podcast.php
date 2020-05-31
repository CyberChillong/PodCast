<?php


namespace Controller;
require "../vendor/autoload.php";
require_once "../Library/conteudoXML.php";
require "../Models/UserModel.php";
require "../Models/PodcastModel.php";

use DAO\dbList;
use DAO\dbListPodcast;
use DAO\dbPodcast;
use Library;
use Models\PodcastModel;

class Podcast
{
    private $anchorfm;
    private $dbList;
    private $dbListPodcast;
    private $dbPodcast;

    public function __construct()
    {
        $this->anchorfm = new Library\anchorfm();
        $this->dbList = new dbList();
        $this->dbListPodcast = new dbListPodcast();
        $this->dbPodcast = new dbPodcast();
    }

    private function searchPodcast()
    {
        session_start();
        $pSearch = $_POST["searchExpression"];
        $pSearch = explode(" ", $pSearch);
        $searchQuery = null;
        for ($i = 0; $i < count($pSearch); $i++) {
            if ($i + 1 === count($pSearch)) {
                $searchQuery .= $pSearch[$i];
            } else {
                $searchQuery .= $pSearch[$i] . "+";
            }
        }
        $resultado = $this->anchorfm->getXmlResponse($searchQuery);

        $_SESSION['Podcast'] = $resultado;
        header("Location:../../View/index.php");
    }



    private function getListsOfPodcastsOfUser()
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idUser = $_SESSION['UserModel']->id;
            $resultado = $this->dbList->getUserLists($idUser);
            $_SESSION['UserListOfPodcasts'] = $resultado;
            $arrayWithNumberOfPodcastOfLists = [];
            foreach ($resultado as $lista) {
                $countOfNumberOfPodcastsOnList = $this->dbListPodcast->cointPodCastFromLists($lista->strId);
                $countOfNumberOfPodcastsOnList = $countOfNumberOfPodcastsOnList[0]['COUNT'];
                array_push($arrayWithNumberOfPodcastOfLists, $countOfNumberOfPodcastsOnList);
            }
            $_SESSION['UserNumberOfPodcastsOnTheList'] = $arrayWithNumberOfPodcastOfLists;
            header("Location:../../View/myListOfPodcasts.php");
        } else {
            header("Location:../../View/index.php");

        }
    }

    public function getListPodcast(){
        session_start();





    }//getListPodcast

    private function getMyPodcastsOfList()
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idDaLista = (integer)$_POST["l"];
            $resultadoComListaDosIdsDosPodcastsNaLista = $this->dbListPodcast->getPodCastFromLists($idDaLista);
            $arrayComOsPodcasts = [];
            foreach ($resultadoComListaDosIdsDosPodcastsNaLista as $podcastOfList) {
                $podcastFromDatabase = $this->dbPodcast->getPodCastFromId($podcastOfList['PODCAST_ID']);
                $podcastModel = new PodcastModel(0, $podcastFromDatabase[0]["TITLE"], $podcastFromDatabase[0]["DESCRIPTION"], $podcastFromDatabase[0]["DATES"], $podcastFromDatabase[0]["SOURCES"]);
                array_push($arrayComOsPodcasts, $podcastModel);
            }
            $_SESSION['UserPodcastOfList'] = $arrayComOsPodcasts;
            header("Location:../../View/podcastList.php");
        } else {
            header("Location:../../View/index.php");
        }
    }

    private function insertHistoric($pathInfo)
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $caminhoParaOAudio = null;
            for ($i = 3; $i < count($pathInfo); $i++) {
                if ($i + 1 >= count($pathInfo)) {
                    $caminhoParaOAudio .= $pathInfo[$i];
                } else {
                    $caminhoParaOAudio .= $pathInfo[$i] . "/";
                }
            }
            $caminhoParaOAudio = "'https://" . $caminhoParaOAudio . "'";
            $verificationIfPodcastExists = $this->dbPodcast->getPodCastFromSource($caminhoParaOAudio);
            if (count($verificationIfPodcastExists) === 0) {
                //Falta fazer insert na base de dados do podcasts
               
                $atualPodcast=0;
                while($_SESSION['Podcast'][$atualPodcast]->linkOriginal!==$caminhoParaOAudio){
                    $atualPodcast++;
                }
                echo var_dump($_SESSION['Podcast'][$atualPodcast]->linkOriginal);
                echo var_dump($caminhoParaOAudio);
            } else {
                $podcastID = $verificationIfPodcastExists[0]["ID"];
                $idUser = $_SESSION['UserModel']->id;
                $verificationIfListofHistoryOfUserIfCreated =$this->dbList->getUserListByUserIDAndName($idUser, "'Historico'");
                if (count($verificationIfListofHistoryOfUserIfCreated) === 0) {
                   $this->dbList->createList($idUser, "'Historico'");
                }
                 $verificationIfListofHistoryOfUserIfCreated = $this->dbList->getUserListByUserIDAndName($idUser, "'Historico'");
                $idOfHystoricList = $verificationIfListofHistoryOfUserIfCreated[0]->strId;
                $this->dbListPodcast->InsertPodcastOnList($idOfHystoricList, $podcastID);
                $_SESSION["pathOfPodcastAddedToHistList"]=$caminhoParaOAudio;
             //   header("Location:../../podcast.php");
            }
        }
    }

    public function escolha()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "ser") {
            $this->searchPodcast();
        } else if ($pathInfo[1] === "getList") {
            $this->getListsOfPodcastsOfUser();
        } else if ($pathInfo[1] === "getListPodcast") {
            $this->getMyPodcastsOfList();
        } else if ($pathInfo[1] === "hist") {
            $this->insertHistoric($pathInfo);
        }
    }


}

$u = new Podcast();
$u->escolha();