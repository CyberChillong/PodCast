<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="../bottstrap/bootstrap.css">
<?php session_start();
if(isset($_SESSION['email'])){
    header("Location:../View/UserView.php");
    session_destroy();
}?>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="registar.php">Registar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="podcasts.php">Podcasts</a>
        </li>
    </ul>
</nav>
<?php session_destroy()?>
<div class="container">
<form method="post" action="../Controller/USER.php/reg">
    <div class="form-group row justify-content-center">
        <label for="username">Username:</label>
    <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group row justify-content-center">
        <label for="email">Email:</label>
        <input type="text" name="email" class="form-control" required>
    </div>
    <div class="form-group row justify-content-center">
        <label for="password">Password:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group row justify-content-center">
        <input type="submit" value="Submit" class="btn btn-primary" required>
    </div>
</form>
</div>
</body>
</html>