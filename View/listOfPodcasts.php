<?php
require "../Models/UserModel.php";
require "../Models/PodcastModel.php";
session_start();
$_SESSION['whereItComes'] = 'l';
if (isset($_SESSION['listCreated']) !== false) {
    if ($_SESSION['listCreated'] === true) {
        echo '<script>alert("New list created")</script>';
    } else {
        echo '<script>alert("A list with the name given already exists on your account")</script>';
    }
    $_SESSION['listCreated'] = NULL;
}
if (isset($_SESSION['podcastAddedToList'])) {
    if ($_SESSION['podcastAddedToList'] !== null) {
        echo '<script>alert("Added To List")</script>';

    }
    $_SESSION['podcastAddedToList'] = NULL;
}
if(isset($_SESSION['Podcast'])){
    $_SESSION['Podcast']=null;
}
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav">
            <a href="index.php" class="nav-item nav-link active">Home</a>
            <a href="./about.php" class="nav-item nav-link">About</a>
        </div>
        <?php
        if (isset($_SESSION['UserModel']) === false) {
            echo '<div class="navbar-nav ml-auto">
            <a href="./registar.php" class="nav-item nav-link">Sign in</a>
            <a href="./login.php" class="nav-item nav-link">Login</a>
        </div>';
        } else {
            echo '<div class="navbar-nav ml-auto">
            <a href="../Controller/Podcast.php/getList" class="nav-item nav-link">My Lists of Podcasts</a>
            <a  id="createList" class="nav-item nav-link" onclick="redirect()">New List</a>
            <a href="./UserPanel.php" class="nav-item nav-link">Edit Account</a>
            <a href="./logout.php" class="nav-item nav-link">Logout</a>
            <a href="../Controller/User.php/deac" class="nav-item nav-link">Deactivate Account</a>
            </div>';
        }
        ?>
</nav>
<div class="container">
    <?php
    if (isset($_SESSION['UserPodcastOfList'])) {
        if ($_SESSION['UserPodcastOfList'] !== null) {
            $numeroDePaginas = ((count($_SESSION['UserPodcastOfList']) - 1) / 20);
            if (is_integer($numeroDePaginas) === false || $numeroDePaginas === 0) {
                $numeroDePaginas = (integer)($numeroDePaginas + 1);
            }
            $obterNumeroDoUrl = $_SERVER["QUERY_STRING"];
            $obterNumeroDoUrl = explode("=", $obterNumeroDoUrl);

            if (count($obterNumeroDoUrl) === 2) {
                $numeroDaPaginaAtual = (integer)$obterNumeroDoUrl[1];
            } else {
                $numeroDaPaginaAtual = 1;
            }
            $tabela = ' <table class="table">
        <thead>
        <tr>
        <th scope="col">Title</th>
        <th scope="col">Publication Date</th>
        <th scope="col">Description</th>
        <th scope="col">User Option(s)</th>
        </tr>
        </thead>
        <tbody>';
            $podcast = null;
            $numeroQueOForTemDeChegar = null;
            $numeroDePodcastsRecolhidos = count($_SESSION['UserPodcastOfList']) - 1;
            if ($numeroDePodcastsRecolhidos < $numeroDaPaginaAtual * 20) {
                $numeroQueOForTemDeChegar = $numeroDePodcastsRecolhidos;
            } else {
                $numeroQueOForTemDeChegar = $numeroDaPaginaAtual * 20;
            }
            for ($i = (($numeroDaPaginaAtual - 1) * 20); $i <= $numeroQueOForTemDeChegar; $i++) {
                $podcast = $_SESSION['UserPodcastOfList'][$i];
                $tabela .= '<tr>' . '<td scope="row">' . $podcast->title . '</td >' . '<td >' . $podcast->date . '</td >' . '<td >' . $podcast->description . '</td >' . '<td ><form method="post" action="listenerPodcast.php">
        <div class="row justify-content-center">
          <div class="col-6">
                <input name="p" style="visibility: hidden" value="' . $podcast->source . '"></input>
                <input type="submit" class="btn btn-dark"  value="Listen" >
            </div>
        </div>
    </form></td >';
                if (isset($_SESSION["UserModel"]) !== false && isset($_SESSION["ListToAddPodcast"]) !== false) {
                    if (count($_SESSION["ListToAddPodcast"][$i]) > 0) {
                        $tabela .= '<td>
        <form method="post" action="../Controller/Podcast.php/insertList/">
        <div class="row justify-content-center">
          <div class="col-6">
          <input name="p" style="visibility: hidden" value="' . $podcast->source . '"/>
                <select name="selectList">';
                        foreach ($_SESSION["ListToAddPodcast"][$i] as $list) {
                            $tabela .= '<option>' . $list . '</option>';
                        }
                        $tabela .= '</select>
                <input type="submit" class="btn btn-dark"  value="add ToList" >
            </div>
        </div>
    </form></td >';
                    }

                }
            }
            $tabela .= "</tr>";
            $tabela .= '</tbody></table>';
            echo $tabela;
            echo '<nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">';
            for ($i = 0; $i < $numeroDePaginas; $i++) {
                if ($i + 1 === $numeroDaPaginaAtual) {
                    echo '<li class="page-item active"><a class="page-link" name="n" href="listOfPodcasts.php?n=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" name="n" href="listOfPodcasts.php?n=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
                }
            }
            echo '</ul></nav>';
        }
    }
    ?>


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

</div>
</body>
</html>
<script src="javascript.js"></script>