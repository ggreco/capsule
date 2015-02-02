<html>
<head>
    <title>Ricerca</title>
</head>
<body>
    <h3>Compila uno o pi&ugrave; dei campi seguenti per eseguire una ricerca tra le capsule</h3>
    Non &egrave; necessario inserire il campo intero, ma solo una parte, ad esempio con "pie" in <b>Regione</b> verranno selezionate le capsule del PIEMONTE.
    <p/>
    NOTA: lasciare tutti i campi vuoti mostrer&agrave; tutte le capsule del database!
    <p/>
    <form method="post" action="do_search.php">
        <table>
            <tr><td>Azienda</td><td><input name="name" type="text"></td></tr>
            <tr><td>Regione o Nazione</td><td><input name="reg" type="text"></td></tr>
            <tr><td>Descrizione (colore per ICRF)</td><td><input name="desc" type="text"></td></tr>
            <tr><td>Codice CCC (numero del registro per ICRF)</td><td><input name="cat" type="text"></td></tr>
            <tr><td>Valore</td>
                <td>
                    <select name="val"> 
                        <option value="0">Tutte</option>
                        <option value="2">Fino a 2</option>
                        <option value="3">Da 3 a 4</option>
                        <option value="5">Da 5 a 6</option>		
                        <option value="7">7 e oltre</option>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2"><center><input type="submit" value="Cerca"></center></td></tr>
        </table>
    </form>
    <p/>
    <h3><a href="index.php">Torna alla pagina principale</a></h3>
</body>
</html>
