<?php
$reponse = mysql_query('SELECT * FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$page.'\'');
while ($donnees = mysql_fetch_array($reponse) )
{	
	$title=$donnees['title'];
	$text=$donnees['text'];		
	$images=$donnees['images'];
}
if($images != '')
{
	$images=preg_replace('#^\^(.*)\$$#', "$1", $images);
	$imagesArray = explode('$^', $images);
	$text .= '<table border="0" style="border:1px solid black;"><caption><span style="font-size:1.65em">Images jointes :</span></caption><tbody><tr>';
	$iCompt=0;
	foreach($imagesArray as $element)
	{
		if($iCompt==3){
			$text.='</tr><tr>';
			$iCompt=0;
		} 		
			$text.='<td><a href="http://'.$domaine.$repertoire.'/upload/'.$element.'"><img src="http://'.$domaine.$repertoire.'/upload/thumbs/thumb_'.(preg_replace('#^(.+)\.[a-zA-Z]{3}$#', "$1", $element)).'.jpg" alt="image" /></a></td>';
			$iCompt++;		
	}
	$text.='</tr></tbody></table>';
}
if($text==''&&$title=='')
	$erreur = 404;
?>