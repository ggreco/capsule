<?php

session_start();

if (array_key_exists("username", $_POST)) {
   $_SESSION["username"] = $_POST["username"];
}

if (array_key_exists("username", $_SESSION) == false) {
    header("location:login.php");
    return;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>
<?php

function query_capsule($id)
{
    $db = new SQLite3('db/capsule.db');
    if (!$db) return NULL;
    
    $result = $db->query("select * from capsule where Id = $id");

    if (!$result) return NULL;

    $row = $result->fetchArray();

    $db->close();

    if (!$row)
        return NULL;

    return $row;
}

function insert_modify_capsule() {
       $is_update = ($_POST["id"] != "-1");
      
       if ($is_update)
           print "Operazione di modifica";
       else
           print "Operazione di inserimento";

       print "</title></head><body>";

       $db = new SQLite3('db/capsule.db');
       if ($db) {
           $name = SQLite3::escapeString($_POST["name"]);
           $desc = SQLite3::escapeString($_POST["desc"]);
           $cat = SQLite3::escapeString($_POST["cat"]);
           $reg = SQLite3::escapeString($_POST["reg"]);

           $q = $_POST["quant"];
           $v = $_POST["val"];

           if ($is_update) {
               $id = $_POST["id"];

               $db->exec("UPDATE capsule SET Nome='$name',Descrizione='$desc',Categoria='$cat',Valore=$v,Quantita=$q,Modifica=datetime('now'),regione='$reg' WHERE Id=$id");
               print "<h1>Record aggiornato correttamente</h1>";
           }
           else {
               $d = "imgs/" . date("ymd");
               if (!file_exists($d))
                   mkdir($d);
               
               $pic =  $d . "/img_" . date("His") . "_" . (rand()%1000000);

               if (stripos($_FILES['image']['name'], ".gif"))
                   $pic .= ".gif";
               else if (stripos($_FILES['image']['name'], ".png"))
                   $pic .= ".png";
               else
                   $pic .= ".jpg";
                   
               if (!move_uploaded_file($_FILES['image']['tmp_name'], $pic)) {
                   print "<h1>Impossibile aggiungere immagine ($pic), inserimento fallito.</h1>";
               }
               else {
                   $db->exec("INSERT INTO capsule (Nome,Descrizione,Categoria,Valore,Foto,Quantita,regione) VALUES ('$name', '$desc', '$cat', $v, '$pic', $q, '$reg')");

                   print "<h1>Record aggiunto correttamente</h1>";
               }
           }
           $db->close();
       }
       else print "<h1>Impossibile accedere al DB</h1>";

       print "<p/>
           <a href=\"insert.php\">Nuovo inserimento</a> -
           <a href=\"index.php\">Pagina principale</a> -
           <a href=\"show.php\">Vista globale</a>
           </body></html>";
}

    if (array_key_exists("id", $_POST) &&
        array_key_exists("name", $_POST)) {
       insert_modify_capsule();       
       return;
    }

    $id = -1;

    if (array_key_exists("id", $_GET)) {
        print "Modifica capsula";
        $id = $_GET["id"];
    }
    else
        print "Inserimento nuove capsule";

    print "</title></head><body>";
    
    $cap = NULL;

    if ($id != -1)
        $cap = query_capsule($id);

    if ($id == -1 || $cap == NULL) {
        print "<p/><h1>Inserisci nuova capsula</h1><p/>";
        $name = "";
        $desc = "";
        $cat = "";
        $quant = 1;
        $val = 1;
        $op = "Inserisci";
        $reg ="";
    }
    else {
        print "<p/><h1>Modifico dati capsula</h1><p/>";

        $name = $cap["Nome"];
        $reg = $cap["regione"];
        $desc = $cap["Descrizione"];
        $cat = $cap["Categoria"];
        $quant = $cap["Quantita"];
        $val = $cap["Valore"];
        $op = "Modifica";
    }

print "<form enctype=\"multipart/form-data\" method=\"post\" action=\"insert.php\"><table>";

print "<input name=\"id\" type=\"hidden\" value=\"$id\">";

print "
<tr><td>Nome</td><td><input name=\"name\" type=\"text\" value=\"$name\"></td></tr>
<tr><td>Regione</td><td><input name=\"reg\" type=\"text\" value=\"$reg\"> (usare nazione per capsule straniere)</td></tr>
<tr><td>Descrizione</td><td><input name=\"desc\" type=\"text\" value=\"$desc\"></td></tr>
<tr><td>Categoria</td><td><input name=\"cat\" type=\"text\" value=\"$cat\"></td></tr>
<tr><td>Valore</td><td><input name=\"val\" type=\"text\" value=\"$val\"></td></tr>
<tr><td>Quantit&agrave;</td><td><input name=\"quant\" type=\"text\" value=\"$quant\"> (usare &gt;1 per indicare le doppie)</td></tr>";

if ($id == -1)
    print "<tr><td>Foto</td><td><input name=\"image\" type=\"file\"></td></tr>";

print "<tr><td colspan=\"2\"><center><input type=\"submit\" value=\"$op\">";

if ($id != -1)
     print " - <input type=\"button\" value=\"Rimuovi\" onclick=\"location.href='remove.php?id=$id'\">";

print "</center></td></tr>
</table>
</form>
</body>
</html>
";

?>
