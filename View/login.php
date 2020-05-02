<?php session_start() ?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="../bottstrap/bootstrap.css">
<?php
if(isset($_SESSION['email'])){
    header("Location:../View/UserView.php");
    session_destroy();
}?>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="registar.php">Registar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="podcasts.php">Podcasts</a>
        </li>
    </ul>
</nav>
<div class="container">
    <form method="post" action="../Controller/USER.php/log">
        <div class="form-group row justify-content-center">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group row justify-content-center">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group row justify-content-center">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
    </form>
    <?php
    if (isset($_SESSION["LoginStatus"])) {
        echo "<div class=\"alert alert-danger\">" . $_SESSION["LoginStatus"] . "</div>";
    }//if
    if(isset($_SESSION["RegistrationStatus"])){
        echo "<div class=\"alert alert-success\">" . $_SESSION["RegistrationStatus"] . "</div>";
    }//if
    session_destroy();
    ?>
</div>
</body>
</html>