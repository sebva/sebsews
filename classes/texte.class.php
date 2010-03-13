<?php
$reponse = mysql_query('SELECT * FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$page.'\'');
while ($donnees = mysql_fetch_array($reponse) )
{	
	$title=$donnees['title'];
	$text=$donnees['text'];		
	$images=$donnees['images'];
}
require('func/galerie.php');
if($text==''&&$title=='')
	$erreur = 404;
?>