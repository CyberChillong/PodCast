<?php session_start()?>
<!DOCTYPE html>
<html>
<body>
<form method="post" action="../Controller/USER.php/log">
    Email:<br>
    <input type="text" name="email">
    <br><br>
    Password:<br>
    <input type="password" name="password">
    <br><br>
    <input type="submit" value="Submit">
</form>
<?php
if(isset($_SESSION["LoginStatus"])){
echo "<p>".$_SESSION["LoginStatus"]."</p>";
}//if
session_destroy();
?>
</body>
</html>