<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="../bottstrap/bootstrap.css">
<?php
require_once "../Models/UserModel.php";
session_start();
if (isset($_SESSION['listCreated']) !== false) {
    if ($_SESSION['listCreated'] === true) {
        echo '<script>alert("New list created")</script>';
    } else {
        echo '<script>alert("A list with the name given already exists on your account")</script>';
    }
    $_SESSION['listCreated'] = NULL;
}
?>
<style>
    html, body {
        width: 100%;
    }

    .container {
        width: 100%;
    }
</style>
<body><nav class="navbar navbar-expand-sm bg-dark navbar-dark">
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
<div class="container text-center">
    <?php
    if(isset($_SESSION['UserModel'])){
    echo sprintf('<div class="card float-md-left" style="width:200px;">
  <img class="card-img-top" src="Assets/interface.png" alt="Card image" "
  <div class="card-body">
    <h4 class="card-title">%s</h4>
    <a href="./editAcount.php" class="btn btn-dark">Edit Profile</a>
  </div>
   
</div>', $_SESSION["UserModel"]->username);
    }
    ?>
    <div class="container">
    <div class="tab">
        <button class="tablinks btn-dark" onclick="openTab(event, 'UserInfo')">User Info</button>
        <button class="tablinks btn-dark" onclick="openTab(event, 'PlayList')">PlayList</button>
        <button class="tablinks btn-dark" onclick="openTab(event, 'Favourites')">Favourites</button>
    </div>

    <div id="UserInfo" style="visibility: hidden;" class="tabcontent">
        <ul class="list-group" style="width: 50%;">
            <th>
                <h3>User Info</h3>
            </th>
        <?php
            $aLabelValues = ['ID', 'Username', 'Email', 'Password'];
            $counter = 0;
            foreach ($_SESSION['UserModel']  as $value){
                echo sprintf("<li class='list-group-item'>
                <label>%s</label>
                <input class='form-control' type='text' value='%s' disabled>
                </li>", $aLabelValues[$counter],$value );
                $counter++;
            }


        ?>
        </ul>
    </div>

    <div id="PlayList" style="visibility: hidden;" class="tabcontent">
        <h3>Your Play Lists</h3>
        <p>Paris is the capital of France.</p>
    </div>

    <div id="Favourites" style="visibility: hidden;"  class="tabcontent">
        <h3>Your Favourites: </h3>
        <p>Tokyo is the capital of Japan.</p>
    </div>

    <script>
    function openTab(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    document.getElementById(cityName).style.visibility = "visible" ;
    evt.currentTarget.className += " active";
    }
    </script>
    </div>
</body>
</html>
<script src="javascript.js"></script>


