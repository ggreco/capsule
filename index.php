<?php session_start(); ?>
<html>
<head>
<title>Collezione di Capsule di Gaspare Greco</title>
</head>
<body>
<h1>Collezione di Capsule di <u>Gaspare Greco</u></h1>

<?php 
    $db = new SQLite3("db/capsule.db");
    $result = $db->query("select strftime('%d/%m/%Y', Modifica) from capsule order by datetime(Creazione) desc limit 1;");
    if ($row = $result->fetchArray())
        print "ultimo aggiornamento <b> $row[0] </b>, ";
    $result = $db->query("select count(*) from capsule;");
    if ($row = $result->fetchArray())
        print "totale capsule <b>$row[0]</b>";
?>
<p/>
<h4>
<ul>
<li><a href="show.php" >Tutte le capsule</a> (attenzione!! sono migliaia di capsule)</li>
<p/>
<li><a href="show.php?nome=piemonte" >Piemonte</a></li>
<li><a href="show.php?nome=veneto" >Veneto</a></li>
<li><a href="show.php?nome=lombardia" >Lombardia</a></li>
<p/>
<li><a href="show.php?last_added&limit=18" >Ultime aggiunte</a></li>
<li><a href="search.php" >Ricerca</a></li>
<li><a href="show.php?doppie" >Doppie</a></li>
</ul>
</h4>

<h3>
<?php
    if (array_key_exists("username", $_SESSION) == false) 
        print '<a href="login.php">Esegui login per modificare il DB</a>';
    else
        print '<a href="insert.php">Inserimento</a> - <a href="logout.php">Logout</a>';
?>
</h3>
</body>
</html>
