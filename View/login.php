<?php
session_start();
?>
<!DOCTYPE html>
<html>
<?php
if (isset($_SESSION['UserModel'])) {
    header("Location:../View/index.php");
} ?>
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
            <a href="./UserPanel.php" class="nav-item nav-link">Edit Account</a>
            <a href="./logout.php" class="nav-item nav-link">Logout</a>
            <a href="../Controller/User.php/deac" class="nav-item nav-link">Deactivate Account</a>
            </div>';
        }
        ?>
</nav>
<div class="container">
    <form method="post" action="../Controller/USER.php/log">
        <div class="form-group row justify-content-center">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control text-center" required>
        </div>
        <div class="form-group row justify-content-center">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control text-center" required>
        </div>
        <div class="form-group row justify-content-center">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
    </form>
    <?php
    if (isset($_SESSION["LoginStatus"])) {
        echo "<div class=\"alert alert-danger\">" . $_SESSION["LoginStatus"] . "</div>";
    }//if
    if (isset($_SESSION["RegistrationStatus"])) {
        echo "<div class=\"alert alert-success\">" . $_SESSION["RegistrationStatus"] . "</div>";
    }//if
    ?>
</div>
</body>
</html>