<?php 
session_start();
$can_modify = array_key_exists("username", $_SESSION);
?>
<html>
<head>
<script type="text/javascript" src="js/lightbox.js"></script>
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen">
<title>Collezione di Capsule di Gaspare Greco</title>
</head>
<body>
<?php

$dbhandle = new SQLite3('db/capsule.db');

// CREATE TABLE capsule(Id INTEGER PRIMARY KEY, Nome TEXT, Descrizione TEXT, Categoria TEXT, Valore INTEGER, Foto TEXT, Quantita INTEGER);
// CREATE TABLE users(Id INTEGER PRIMARY KEY, User VARCHAR(30), Password VARCHAR(32), Role TEXT);

function draw_capsule($row, $can_modify)
{
    $name = mb_convert_encoding($row["Nome"], 'HTML-ENTITIES', 'utf-8');
    $reg = mb_convert_encoding($row["regione"], 'HTML-ENTITIES', 'utf-8');
    $cat = mb_convert_encoding($row["Categoria"], 'HTML-ENTITIES', 'utf-8');
    $desc = mb_convert_encoding($row["Descrizione"], 'HTML-ENTITIES', 'utf-8');

    print("
      <td width=\"13%\">
      <div align=\"center\"><a href=\"{$row["Foto"]}\" rel=\"lightbox\"><img style=\"border: 0px solid ; width: 90px; height: 90px;\" alt=\"$name\" src=\"{$row["Foto"]}\"></a></div>
      </td>
      <td width=\"20%\"><strong>$name</strong>
      ");

    if ($row["Quantita"] > 1)
        print " <span style=\"background-color:red;color:yellow;padding-left:2px;padding-right:2px;\">x{$row["Quantita"]}</span> ";

    print "<br>$reg<br>$cat<br><br>$desc (Val {$row["Valore"]})";

    if ($can_modify)
        print "<a style=\"font-size:10px;float:right;\" href=\"insert.php?id={$row["Id"]}\">[Modifica]</a>";

    print "</td>";
}

function draw_pages($start, $total, $limit, &$vec)
{
    $pages = ceil($total/$limit);

    $actual = $start / $limit + 1;

    if ($pages <= 1)
        return;

    $baseparams = "show.php?";

    foreach ($vec as $key => $val) {
        if ($key == "first" || $key == "limit")
            continue;
        $baseparams .= "$key=$val&";
    }

    print "<center><h3>";
    if ($actual > 1) {
        $idx = ($actual - 2) * $limit;
        $params = "{$baseparams}first=$idx&limit=$limit&total=$total";
        print "<a href=\"$params\">&lt;</a> ";
    }

    for ($i = 1; $i <= $pages; $i++) {
        if ($i == $actual) 
            print "[" . $i . "] ";
        else {
            $idx = ($i - 1) * $limit;
            $params = "{$baseparams}first=$idx&limit=$limit&total=$total";

            print "<a href=\"$params\">[$i]</a> ";
        }
    }
    if ($actual < $pages) {
        $idx = $actual * $limit;
        $params = "{$baseparams}first=$idx&limit=$limit&total=$total";
        print "<a href=\"$params\">&gt;</a> ";
    }

    print "</center></h3>";
}


$stm = " from capsule";

if (array_key_exists("alfabetico", $_GET))
    $stm .= " WHERE nome like '{$_GET["alfabetico"]}%'";
else if (array_key_exists("nome", $_GET) ||
    array_key_exists("cat", $_GET) ||
    array_key_exists("reg", $_GET) ||
    array_key_exists("doppie", $_GET) ||
    array_key_exists("desc", $_GET) ||
    array_key_exists("valore_min", $_GET) ||
    array_key_exists("valore_max", $_GET))
    $stm .= " WHERE 1 = 1";

$selection = false;

$nopage = array_key_exists("nopage", $_GET);

if (array_key_exists("nome", $_GET)) {
    $n = SQLite3::escapeString($_GET["nome"]);
    $stm .= " and Nome like '%$n%'";
}
if (array_key_exists("reg", $_GET)) {
    $n = SQLite3::escapeString($_GET["reg"]);
    $stm .= " and regione like '%$n%'";
}
if (array_key_exists("desc", $_GET)) {
    $n = SQLite3::escapeString($_GET["desc"]);
    $stm .= " and Descrizione like '%$n%'";
}
if (array_key_exists("cat", $_GET))
    $stm .= " and Categoria like '%{$_GET["cat"]}%'";
if (array_key_exists("doppie", $_GET))
    $stm .= " and Quantita > 1";
if (array_key_exists("valore_min", $_GET))
    $stm .= " and Valore >= {$_GET["valore_min"]}";
if (array_key_exists("valore_max", $_GET))
    $stm .= " and Valore <= {$_GET["valore_max"]}";

if (array_key_exists("last_added", $_GET)) {
    $stm .= " order by datetime(Creazione) desc LIMIT {$_GET["limit"]}";
    $nopage = true;
}
else if (array_key_exists("last_modified", $_GET)) {
    $stm .= " order by datetime(Modifica) desc LIMIT {$_GET["limit"]}";
    $nopage = true;
}
else
	$stm .= " order by Nome";

$total = -1;
$first = 0;
$limit = 30; // <- NUMERO da cambiare per alterare il limite

// se c'e' total deve esserci anche first
if (array_key_exists("total", $_GET)) {
    $total = $_GET["total"];
    $first = $_GET["first"];
}

if (array_key_exists("limit", $_GET))
    $limit = $_GET["limit"];

if ($total == -1) {
    $result = $dbhandle->query("select count(*) " . $stm);
    if ($row = $result->fetchArray()) 
        $total = $row[0];

    if ($total < $limit)
        $limit = -1;
}

if (!$nopage) {
    if (array_key_exists("limit", $_GET)) {
        if ($total > 0) 
            $stm .= " LIMIT " . $first . "," . $_GET["limit"];
        else
            $stm .= " LIMIT " . $_GET["limit"];
    }
    else if ($limit > 0 && $total > 0) {
            $stm .= " LIMIT " . $first . "," . $limit;
    }
}

$result = $dbhandle->query("select * " . $stm);

if (!$result)
    die("Cannot execute query.");

$number = 0;

if ($total > 0) {
    if (!$nopage)
        draw_pages($first, $total, $limit, $_GET);
    print('<table align="center" border="1" bordercolor="#000000" cellpadding="2" cellspacing="0" width="80%">
        <font face="Arial, Helvetica, sans-serif" size="2">');
}

while ($row = $result->fetchArray()) {
    if (($number % 3) == 0) {
        if ($number != 0)
            print('</tr>');
        print('<tr>');
    }

    draw_capsule($row, $can_modify);
    $number++;
}

if ($total > 0) {
    print "</tr></font></table>";
    
    if (!$nopage)
        draw_pages($first, $total, $limit, $_GET);
    print "<center><br><h3>Totale capsule trovate: $total ($number visualizzate in questa pagina)</h3></center>";
}

$dbhandle->close();
?>
<p/>
<center>
<h3>

<?php
if (!$can_modify)
    print "<a href=\"login.php\">Login</a>";
else
    print "<a href=\"insert.php\">Inserisci nuova capsula</a>";
?>

- <a href="index.php">Pagina principale</a>

</h3>
</center>
</body>
