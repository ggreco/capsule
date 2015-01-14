<?php
    $url = "show.php?valore_min=" . $_POST["val"]; 

    if (!empty($_POST["name"]))
        $url .= "&nome=" . urlencode($_POST["name"]); 
    if (!empty($_POST["desc"]))
        $url .= "&desc=" . urlencode($_POST["desc"]); 
    if (!empty($_POST["cat"])) 
        $url .= "&cat=" . urlencode($_POST["cat"]); 
    if (!empty($_POST["reg"])) 
        $url .= "&reg=" . urlencode($_POST["reg"]); 

    header("location:$url");
?>
