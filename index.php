<?php
$pathOfThePageWhereTheRequestWasMade=$_SERVER["HTTP_REFERER"];
$pathOfThePageWhereTheRequestWasMade=explode("/View",$pathOfThePageWhereTheRequestWasMade);
$pathToListenerPodcast=$pathOfThePageWhereTheRequestWasMade[0]."/View/index.php";
header("Location:".$pathToListenerPodcast);
?>
