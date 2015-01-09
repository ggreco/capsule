<html>
<head>
    <title>Ricerca</title>
</head>
<body>
    <h3>Compila uno o pi&ugrave; dei campi seguenti per eseguire una ricerca tra le capsule</h3>
    Non &egrave; necessario inserire il campo intero, ma solo una parte, ad esempio con "pie" in <b>Zona</b> verranno selezionate le capsule del PIEMONTE.
    <p/>
    NOTA: lasciare tutti i campi vuoti moster&agrave; tutte le capsule del database!
    <p/>
    <form method="post" action="do_search.php">
        <table>
            <tr><td>Zona</td><td><input name="name" type="text"></td></tr>
            <tr><td>Descrizione</td><td><input name="desc" type="text"></td></tr>
            <tr><td>Categoria</td><td><input name="cat" type="text"></td></tr>
            <tr><td colspan="2"><center><input type="submit" value="Cerca"></center></td></tr>
        </table>
    </form>
    <p/>
    <h3><a href="index.php">Torna alla pagina principale</a></h3>
</body>
</html>
