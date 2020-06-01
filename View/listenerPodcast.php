<?php
require "../Models/UserModel.php";
session_start();
if (isset($_SESSION['UserModel']) !== false) {
    if (isset($_SESSION["pathOfPodcastAddedToHistList"]) !== false) {
        if ($_SESSION["pathOfPodcastAddedToHistList"] === "0" || $_SESSION["pathOfPodcastAddedToHistList"] === NULL) {
            $pathToAudio = $_POST["p"];
            header("Location:../Controller/Podcast.php/hist/" . $pathToAudio);
        } else {
            $pathToAudio = $_SESSION["pathOfPodcastAddedToHistList"];
            $_SESSION["pathOfPodcastAddedToHistList"] = "0";
        }
    } else {
        $pathToAudio = $_POST["p"];
        header("Location:../Controller/Podcast.php/hist/" . $pathToAudio);
    }
} else {
    $pathToAudio = $_POST["p"];
}
if (isset($_SESSION['listCreated']) !== false) {
    if ($_SESSION['listCreated'] === true) {
        echo '<script>alert("New list created")</script>';
    } else {
        echo '<script>alert("A list with the name given already exists on your account")</script>';
    }
    $_SESSION['listCreated'] = NULL;
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
    <?php echo '<audio controls><source src="' . $pathToAudio . '" type="audio/mpeg"></audio>'; ?>

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