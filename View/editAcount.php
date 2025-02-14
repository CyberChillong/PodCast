<!DOCTYPE html>
<html>
<?php
require "../Models/UserModel.php";
session_start();
if (isset($_SESSION['UserModel'])===false) {
    header("Location:../View/login.php");
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
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
    <form method="post" action="../Controller/USER.php/edit">
        <div class="form-group row justify-content-center">
            <label for="username">Username:</label>
            <?php
            echo '<input type="text" name="username" class="form-control text-center" required value='.$_SESSION["UserModel"]->username.'>';
            ?>
        </div>
        <div class="form-group row justify-content-center">
            <label for="email">Email:</label>
            <?php
            echo '<input type="email" name="email" class="form-control text-center" required value='.$_SESSION["UserModel"]->emails.'>';
            ?>
        </div>
        <div class="form-group row justify-content-center">
            <label for="password">Password:</label>
            <?php
            echo '<input type="password" name="password" class="form-control text-center" required value='.$_SESSION["UserModel"]->password.'>';
            ?>
        </div>
        <div class="form-group row justify-content-center">
            <input type="submit" value="Submit" class="btn btn-primary" required>
        </div>
    </form>
    <?php
    if (isset($_SESSION["EditAccountStatus"])) {
        echo "<div class=\"alert alert-danger\">" . $_SESSION["EditAccountStatus"] . "</div>";
    }//if
    ?>
</div>
</body>
</html>
<script src="javascript.js"></script>