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
        date_default_timezone_set("Europe/Lisbon");
        $this->anchorfm = new Library\anchorfm();
        $this->dbList = new dbList();
        $this->dbListPodcast = new dbListPodcast();
        $this->dbPodcast = new dbPodcast();
    }

    //função para usar obter os podcasts com a api encontrada para o efeito
    private function searchPodcast($pSearch)
    {
        if (session_status() !== 2) {
            session_start();
        }
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
    //Função usada para criar ou alterar o valor da variavel de sessão que vai ficar com um array das listas que o podcast
    // não está ainda
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
        if (session_status() !== 2) {
            session_start();
        }
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
        if (session_status() !== 2) {
            session_start();
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        if (isset($_SESSION['UserModel']) !== false) {
            if (isset($_POST["l"])) {
                $_SESSION['idOfList'] = (integer)$_POST["l"];
            }
            $idOfList = $_SESSION['idOfList'];
            $_SESSION['NameOfCurrentList'] = $this->dbList->getListNameById($idOfList);
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
    private function insertOnList($pathToAudio, $pNameOfList)
    {
        if (session_status() !== 2) {
            session_start();
        }
        $pathOfThePageWhereTheRequestWasMade = $_SERVER["HTTP_REFERER"];
        $pathOfThePageWhereTheRequestWasMade = explode("/View", $pathOfThePageWhereTheRequestWasMade);
        if (isset($_SESSION['UserModel']) !== false) {
            $verificationIfPodcastExistsOnDataBase = $this->dbPodcast->getPodCastFromSource($pathToAudio);
            $nameOfTheSessionVariable = null;
            if (count($verificationIfPodcastExistsOnDataBase) === 0) {
                if ($_SESSION['whereItComes'] === "l") {
                    $nameOfTheSessionVariable = 'UserPodcastOfList';
                } else if ($_SESSION['whereItComes'] === "i") {
                    $nameOfTheSessionVariable = 'Podcast';
                }
                $counterOfTheAtualPodcast = 0;
                while ($_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->source !== $pathToAudio && $counterOfTheAtualPodcast < (count($_SESSION[$nameOfTheSessionVariable]) - 1)) {
                    $counterOfTheAtualPodcast++;
                }
                $title = $_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->title;
                $description = $_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->description;
                $publicationDate = $_SESSION[$nameOfTheSessionVariable][$counterOfTheAtualPodcast]->date;
                $this->dbPodcast->insertPodcast($title, $description, $publicationDate, $pathToAudio);

            }
            $verificationIfPodcastExists = $this->dbPodcast->getPodCastFromSource($pathToAudio);
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
                $_SESSION["pathOfPodcastAddedToHistList"] = $pathToAudio;
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
    //função para criar uma nova lista
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
    //função para mudar o nome da lista
    private function changeListName($strListId, $strListName)
    {
        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $idUser = $_SESSION['UserModel']->id;
            $verificationIfListOfUserIfCreated = $this->dbList->getUserListByUserIDAndName($idUser, $strListName);
            if (count($verificationIfListOfUserIfCreated) === 0) {
                $this->dbList->nameUpdate($strListId, $strListName);
                $_SESSION['listDeleted'] = true;
            } else {
                $_SESSION['listDeleted'] = false;
            }
        } else {
            $_SESSION['listDeleted'] = false;
        }
        $this->getListsOfUser();

    }//changeListName
    //função para desativar a lista
    private function deactivateList($strListId)
    {

        $this->dbList->deactivateList($strListId);
        $this->getListsOfUser();
    }//changeListName

    //função para ativar a lista
    private function activateList($strListName)
    {

        session_start();
        if (isset($_SESSION['UserModel']) !== false) {
            $UserId = $_SESSION['UserModel']->id;
            $this->dbList->activateList($strListName, $UserId);
            $this->getListsOfUser();
        }//if

    }//changeListName

    //função para desativar um podcast da lista
    public function deletePodCast($pStrListId, $pStrPodcastSource)
    {
        $oPodcastId = $this->dbPodcast->getPodCastFromSource($pStrPodcastSource);
        $this->dbListPodcast->deletePodCast((int)$pStrListId, (int)$oPodcastId[0]['ID']);
        $this->getMyPodcastsOfList();
    }//deletePodCast


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
            $pathToAudio = null;
            for ($i = 2; $i < count($pathInfo); $i++) {
                if ($i + 1 >= count($pathInfo)) {
                    $pathToAudio .= $pathInfo[$i];
                } else if ($i === 2) {
                    $pathToAudio .= $pathInfo[$i] . "//";
                } else {
                    $pathToAudio .= $pathInfo[$i] . "/";
                }
            }
            $this->insertOnList($pathToAudio, 'Historic');
        } else if ($pathInfo[1] === "newList") {
            $this->createNewList($pathInfo[2]);
        } else if ($pathInfo[1] === "insertList") {
            $this->insertOnList($_POST['p'], $_POST['selectList']);
        } else if ($pathInfo[1] === "changeName") {
            $this->changeListName($pathInfo[2], $pathInfo[3]);
        } else if ($pathInfo[1] === "DeactivateList") {
            $this->deactivateList($pathInfo[2]);
        } else if ($pathInfo[1] === "ActivateList") {
            $this->activateList($pathInfo[2]);
        } else if ($pathInfo[1] === "deletePodcast") {
            $pathToAudio = null;
            for ($i = 4; $i < count($pathInfo); $i++) {
                if ($i + 1 >= count($pathInfo)) {
                    $pathToAudio .= $pathInfo[$i];
                } else {
                    $pathToAudio .= $pathInfo[$i] . "/";
                }
            }
            $this->deletePodCast($pathInfo[2], "http://" . $pathToAudio);
        }
    }
}

$o = new Podcast();
$o->choice();
