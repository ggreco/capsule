<?php
session_start();

$error = "";

if (array_key_exists("username", $_POST)) {
    $db = new SQLite3('db/capsule.db');

    if ($result = $db->query("SELECT Password from users where User='$_POST[username]'")) {        
        $row = $result->fetchArray();

        if (!$row || $row["Password"] != $_POST["password"]) {
            $error = "PASSWORD O NOME UTENTE NON VALIDI";
        }
        else {
            $_SESSION["username"] = $_POST["username"];
            header("location:index.php");
            return;
        }
    }
    else
        $error = "UTENTE NON TROVATO";

    $db->close();
}


?>
<html>
<head>
<title>Login utente</title>
</head>
<body>
<?php
if ($error != "") {
    print("<h1>$error</h1>");
}
?>

<h3>Per eseguire l'operazione selezionata &egrave; necessario eseguire il login</h3>

<form method="post" action="login.php">
<table>
<tr><td>Utente</td><td><input name="username" type="text"></td></tr>
<tr><td>Password</td><td><input name="password" type="password"></td></tr>
<tr><td colspan="2"><center><input type="submit" value="Entra"></center></td></tr>
</table>
</form>
</body>
</html>
