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
    private function searchPodcast($pSearch)
    {
        session_start();
        $_SESSION["searchName"] = $pSearch;
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
        if (isset($_SESSION['UserModel']) !== false) {
            $this->setVariableForAdditionToList($resultado);
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/index.php";
        header("Location:" . $pathToListenerPodcast);
    }

    private function setVariableForAdditionToList($arrayWithPodcastSource)
    {
        $aux = 0;
        $arrayWithTheNameOfTheListThatStillDontHaveThePodcast[] = [];
        $idUser = $_SESSION['UserModel']->id;
        $listsOfUser = $this->dbList->getUserLists($idUser);
        foreach ($arrayWithPodcastSource as $podcast) {
            $arrayWithTheNameOfTheListThatStillDontHaveThePodcast[$aux] = [];
            $getPodcastFromDatabase = $this->dbPodcast->getPodCastFromSource($podcast->source);
            foreach ($listsOfUser as $list) {
                if ($list->strName !== "Historic") {
                    if (count($getPodcastFromDatabase) === 0) {
                        array_push($arrayWithTheNameOfTheListThatStillDontHaveThePodcast[$aux], $list->strName);
                    } else {
                        $verificationIfTheAtualListAlreadyHasThePodcast = $this->dbListPodcast->getPodCastFromListsIdAndPodcastId($list->strId, $getPodcastFromDatabase[0]["ID"]);
                        if (count($verificationIfTheAtualListAlreadyHasThePodcast) === 0) {
                            array_push($arrayWithTheNameOfTheListThatStillDontHaveThePodcast[$aux], $list->strName);
                        }
                    }
                }
            }
            $aux++;
        }
        $_SESSION['ListToAddPodcast'] = $arrayWithTheNameOfTheListThatStillDontHaveThePodcast;
    }

    //function para obter as listas de podcasts do utilizador
    private function getListsOfUser()
    {
        session_start();
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
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
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/ListsOfPodcasts.php";
            header("Location:" . $pathToListenerPodcast);
        } else {
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/index.php";
            header("Location:" . $pathToListenerPodcast);

        }
    }

    //function para obter os podcasts da lista selecionada pelo utilizador
    private function getMyPodcastsOfList()
    {
        session_start();
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        if (isset($_SESSION['UserModel']) !== false) {
            if(isset($_POST["l"])){
                $_SESSION['idOfList']=(integer)$_POST["l"];
            }
            $idOfList = $_SESSION['idOfList'];
            $resultWithTheIdsOfPodcastsOnTheList = $this->dbListPodcast->getPodCastFromLists($idOfList);
            $arrayComOsPodcasts = [];
            foreach ($resultWithTheIdsOfPodcastsOnTheList as $podcastOfList) {
                $podcastFromDatabase = $this->dbPodcast->getPodCastFromId($podcastOfList['PODCAST_ID']);
                $podcastModel = new PodcastModel(0, $podcastFromDatabase[0]["TITLE"], $podcastFromDatabase[0]["DESCRIPTION"], $podcastFromDatabase[0]["DATES"], $podcastFromDatabase[0]["SOURCES"]);
                array_push($arrayComOsPodcasts, $podcastModel);
            }
            $_SESSION['UserPodcastOfList'] = $arrayComOsPodcasts;
            $this->setVariableForAdditionToList($arrayComOsPodcasts);
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/listOfPodcasts.php";
            header("Location:" . $pathToListenerPodcast);
        } else {
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/index.php";
            header("Location:" . $pathToListenerPodcast);
        }
    }

    //função para inserir no historico do utilizador o podcast que vai ouvir
    private function insertOnList($pathToAudioWhitHTTP, $pNameOfList)
    {
        session_start();
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        if (isset($_SESSION['UserModel']) !== false) {
            $verificationIfPodcastExistsOnDataBase = $this->dbPodcast->getPodCastFromSource($pathToAudioWhitHTTP);
            $nameOfTheSessionVariable = null;
            if (count($verificationIfPodcastExistsOnDataBase) === 0) {
                if ($_SESSION['whereItComes'] === "l") {
                    $nameOfTheSessionVariable = 'UserPodcastOfList';
                } else if ($_SESSION['whereItComes'] === "i") {
                    $nameOfTheSessionVariable = 'Podcast';
                }
                $counterOfTheAtualPodcast = 0;
                while ($_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->source !== $pathToAudioWhitHTTP && $counterOfTheAtualPodcast < (count($_SESSION[$nameOfTheSessionVariable]) - 1)) {
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
            $idOfHystoricListId = $verificationIfListOfUserIfCreated[0]->strId;
            $todayDate = date("Y-m-d H:i:s");
            $this->dbListPodcast->InsertPodcastOnList($idOfHystoricListId, $podcastID, $todayDate);
            if ($pNameOfList === "Historic") {
                $_SESSION["pathOfPodcastAddedToHistList"] = $pathToAudioWhitHTTP;
                $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/listenerPodcast.php";
                header("Location:" . $pathToListenerPodcast);
            } else {
                $nameOfView = $pathOfThePageWhereTheRequestWasMade[1];
                $_SESSION["podcastAddedToList"] = true;
                if ($_SESSION['whereItComes'] === "i") {
                    $this->searchPodcast($_SESSION["searchName"]);
                } else if ($nameOfView === "/listOfPodcasts.php") {
                    $this->getMyPodcastsOfList();
                } else {
                    header("Location:" . $pathOfThePageWhereTheRequestWasMade);
                }
            }
        } else {
            $pathToListenerPodcast = $pathOfThePageWhereTheRequestWasMade[0] . "/View/index.php";
            header("Location:" . $pathToListenerPodcast);
        }

    }

    private function createNewList($listName)
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idUser = $_SESSION['UserModel']->id;
            $verificationIfListOfUserIfCreated = $this->dbList->getUserListByUserIDAndName($idUser, $listName);
            if (count($verificationIfListOfUserIfCreated) === 0) {
                $this->dbList->createList($idUser, $listName);
                $_SESSION['listCreated'] = true;
            } else {
                $_SESSION['listCreated'] = false;
            }
        } else {
            $_SESSION['listCreated'] = false;
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $nameOfView = explode("View/", $pathOfThePageWhereTheRequestWasMade);
        $nameOfView = $nameOfView[1];
        if ($nameOfView === "index.php") {
            $this->searchPodcast($_SESSION["searchName"]);
        } else if ($nameOfView === "ListsOfPodcasts.php") {
            $this->getListsOfUser();
        } else if ($nameOfView === "listOfPodcasts.php") {
            $this->getMyPodcastsOfList();
        } else {
            header("Location:" . $pathOfThePageWhereTheRequestWasMade);
        }
    }

    private function changeListName($strListName, $strListId)
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idUser = $_SESSION['UserModel']->id;
            if ($idUser !== null && $strListId !== "" && $strListName !== "") {
                $this->dbList->nameUpdate($strListId, $strListName);
            }//if
        }//if
    }//changeListName

    public function choice()
    {
        $pathInfo = $_SERVER['PATH_INFO'];
        $pathInfo = explode("/", $pathInfo);
        if ($pathInfo[1] === "ser") {
            $this->searchPodcast($_POST["searchExpression"]);
        } else if ($pathInfo[1] === "getList") {
            $this->getListsOfUser();
        } else if ($pathInfo[1] === "getListPodcast") {
            $this->getMyPodcastsOfList();
        } else if ($pathInfo[1] === "hist") {
            $pathToAudioWhitoutHTTP = null;
            for ($i = 2; $i < count($pathInfo); $i++) {
                if ($i + 1 >= count($pathInfo)) {
                    $pathToAudioWhitoutHTTP .= $pathInfo[$i];
                } else if ($i === 2) {
                    $pathToAudioWhitoutHTTP .= $pathInfo[$i] . "//";
                } else {
                    $pathToAudioWhitoutHTTP .= $pathInfo[$i] . "/";
                }
            }
            $this->insertOnList($pathToAudioWhitoutHTTP, 'Historic');
        } else if ($pathInfo[1] === "newList") {
            $this->createNewList($pathInfo[2]);
        } else if ($pathInfo[1] === "insertList") {
            $this->insertOnList($_POST['p'], $_POST['selectList']);
        } else if ($pathInfo[1] === "changeName") {
            $this->changeListName($pathInfo[2], $pathInfo[3]);
        }
    }
}

$u = new Podcast();
$u->choice();