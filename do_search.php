<?php
    $url = "show.php?fakecmd";

    if (!empty($_POST["name"]))
        $url .= "&nome=" . urlencode($_POST["name"]); 
    if (!empty($_POST["desc"]))
        $url .= "&desc=" . urlencode($_POST["desc"]); 
    if (!empty($_POST["cat"])) 
        $url .= "&cat=" . urlencode($_POST["cat"]); 

    header("location:$url");
?>
