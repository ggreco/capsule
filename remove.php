<?php

session_start();

if (array_key_exists("username", $_SESSION) == false) {
    header("location:login.php");
    return;
}

if (array_key_exists("id", $_GET)) {
    $id = $_GET["id"];
    $db = new SQLite3('db/capsule.db');
    if ($db) {
       $result = $db->query("SELECT Foto FROM capsule WHERE Id=$id");
       if ($row = $result->fetchArray())
           unlink($row["Foto"]);

       $db->exec("DELETE FROM capsule WHERE Id=$id");
       $db->close();
    }
}

header("location:index.php");
return;

?>
