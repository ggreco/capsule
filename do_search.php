<?php
    $v = $_POST["val"];

    if ($v == "2") 
        $url = "show.php?valore_max=2";
    else if ($v == "3")
        $url = "show.php?valore_min=3&valore_max=5";
    else
        $url = "show.php?valore_min=$v"; 

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
