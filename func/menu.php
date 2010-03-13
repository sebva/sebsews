<?php
if($page!='menu')
{
	$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' ORDER BY id');
	$menuNomCourt=array();
	$menuNomLong=array();
	while ($donnees = mysql_fetch_array($reponse) )
	{
		$menuNomCourt[]=$donnees['shorttitle'];
		$menuNomLong[]=$donnees['title'];
		$iCompt++;
	}
}
else
	$erreur = 403;
?>