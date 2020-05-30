<?php


namespace Controller;
require "../vendor/autoload.php";
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
    //função para usar obter os podcasts com a api encontrada para o efeito
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

    //function para obter as listas de podcasts do utilizador
    private function getListsOfUser()
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idUser = $_SESSION['UserModel']->id;
            $result = $this->dbList->getUserLists($idUser);
            $_SESSION['UserListOfPodcasts'] = $result;
            $arrayWithNumberOfPodcastOfLists = [];
            foreach ($result as $individualList) {
                $countOfNumberOfPodcastsOnList = $this->dbListPodcast->countPodCastFromLists($individualList->strId);
                $countOfNumberOfPodcastsOnList = $countOfNumberOfPodcastsOnList[0]['COUNT'];
                array_push($arrayWithNumberOfPodcastOfLists, $countOfNumberOfPodcastsOnList);
            }
            $_SESSION['UsernumberOfPodcastsOnTheList'] = $arrayWithNumberOfPodcastOfLists;
            header("Location:../../View/ListsOfPodcasts.php");
        } else {
            header("Location:../../View/index.php");

        }
    }

    //function para obter os podcasts da lista selecionada pelo utilizador
    private function getMyPodcastsOfList()
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idOfList = (integer)$_POST["l"];
            $resultWithTheIdsOfPodcastsOnTheList=$this->dbListPodcast->getPodCastFromLists($idOfList);
            $arrayComOsPodcasts = [];
            foreach ($resultWithTheIdsOfPodcastsOnTheList as $podcastOfList) {
                $podcastFromDatabase = $this->dbPodcast->getPodCastFromId($podcastOfList['PODCAST_ID']);
                $podcastModel = new PodcastModel(0, $podcastFromDatabase[0]["TITLE"], $podcastFromDatabase[0]["DESCRIPTION"], $podcastFromDatabase[0]["DATES"], $podcastFromDatabase[0]["SOURCES"]);
                array_push($arrayComOsPodcasts, $podcastModel);
            }
            $_SESSION['UserPodcastOfList'] = $arrayComOsPodcasts;
            header("Location:../../View/listOfPodcasts.php");
        } else {
            header("Location:../../View/index.php");
        }
    }

    //função para inserir no historico do utilizador o podcast que vai ouvir
    private function insertHistoric($pathInfo,$pNameOfList)
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $pathToAudioWhitoutHTTP = null;
            for ($i = 3; $i < count($pathInfo); $i++) {
                if ($i + 1 >= count($pathInfo)) {
                    $pathToAudioWhitoutHTTP .= $pathInfo[$i];
                } else {
                    $pathToAudioWhitoutHTTP .= $pathInfo[$i] . "/";
                }
            }
            $pathToAudioWhitHTTP = "http://" . $pathToAudioWhitoutHTTP . "";
            $verificationIfPodcastExistsOnDataBase = $this->dbPodcast->getPodCastFromSource($pathToAudioWhitHTTP);

            if (count($verificationIfPodcastExistsOnDataBase) === 0) {
                /*if para decidir se a variavel de sessão para obter a informação do podcast é o UserPodcastOfList que é o caso de
                quando o utilizador faz o ouvir pela página de uma lista de podcasts sua ou Podcast no caso do utilizador
                ter carregado no botão ouvir na página do index que é a página de pesquisa de podcasts
                */
               if ($_SESSION['whereItComes'] === "l") {
                    $nameOfTheSessionVariable='UserPodcastOfList';
                } else {
                   $nameOfTheSessionVariable='Podcast';

                }
                $counterOfTheAtualPodcast = 0;
                while ($_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->source !== $pathToAudioWhitHTTP && $counterOfTheAtualPodcast < (count($_SESSION['Podcast']) - 1)) {
                    $counterOfTheAtualPodcast++;
                }
                $title = $_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->title;
                $description = $_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->description;
                $publicationDate = $_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->date;
                $this->dbPodcast->insertPodcast($title, $description, $publicationDate, $pathToAudioWhitHTTP);
            }
            $verificationIfPodcastExists = $this->dbPodcast->getPodCastFromSource($pathToAudioWhitHTTP);
            $podcastID = $verificationIfPodcastExists[0]["ID"];
            $idUser = $_SESSION['UserModel']->id;
            $verificationIfListOfUserIfCreated = $this->dbList->getUserListByUserIDAndName($idUser, $pNameOfList);
           if (count($verificationIfListOfUserIfCreated) === 0) {
                $this->dbList->createList($idUser, $pNameOfList);
               $verificationIfListOfUserIfCreated = $this->dbList->getUserListByUserIDAndName($idUser, $pNameOfList);
            }
            $_SESSION["pathOfPodcastAddedToHistList"]=$pathToAudioWhitHTTP;
            $idOfHystoricListId = $verificationIfListOfUserIfCreated[0]->strId;
            $todayDate = date("Y-m-d H:i:s");
            $this->dbListPodcast->InsertPodcastOnList($idOfHystoricListId, $podcastID, $todayDate);
            $pathOfThePageWhereTheRequestWasMade=$_SERVER["HTTP_REFERER"];
            $pathOfThePageWhereTheRequestWasMade=explode("/View",$pathOfThePageWhereTheRequestWasMade);
            $pathToListenerPodcast=$pathOfThePageWhereTheRequestWasMade[0]."/View/listenerPodcast.php";
            header("Location:".$pathToListenerPodcast);
        }

    }

    public function choice()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "ser") {
            $this->searchPodcast();
        } else if ($pathInfo[1] === "getList") {
            $this->getListsOfUser();
        } else if ($pathInfo[1] === "getListPodcast") {
            $this->getMyPodcastsOfList();
        } else if ($pathInfo[1] === "hist") {
            $this->insertHistoric($pathInfo,'Historico');
        }
    }


}

$u = new Podcast();
$u->choice();