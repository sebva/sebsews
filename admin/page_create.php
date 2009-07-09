<?php  require 'header.php';
if(isset($_POST['shorttitle'])&&isset($_POST['title'])&&isset($_POST['text'])) {
	//if(mysql_query("INSERT INTO newsite VALUES('', '$_POST['shorttitle']', '', '$_POST['title']', '$_POST['text']')"))
	if(mysql_query("INSERT INTO `".$mysqlDb."`.`".$mysqlTablePages."` (
`id` ,
`shorttitle` ,
`date` ,
`title` ,
`text` ,
`menuseul`
)
VALUES (
NULL , '".$_POST['shorttitle']."',
CURRENT_TIMESTAMP , '".$_POST['title']."', '".$_POST['text']."', '".$_POST['menuseul']."'
)"))
		echo 'Page créée avec succès !';
	else
		echo 'Erreur, page non créée';
}else{?>
	<form action="page_create.php" method="post">
	<p><label>Nom court de la page (sans espaces ni accents ou caractères spéciaux) </label><input type="text" name="shorttitle"/><br/>
	<label>Nom complet de la page</label><input type="text" name="title"/><br/>
	<label>Entrée seulement pour menu (sans texte) <strong>!!! Un fichier portant le nom court doit être placé dans le répertoire func !!!</strong></label><input type="checkbox" name="menuseul" /></p>
	<?php 
	include_once("fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('text') ;
	$oFCKeditor->BasePath = 'fckeditor/' ;
	$oFCKeditor->Height = 420;
	$oFCKeditor->Value = '';
	$oFCKeditor->Create() ;
	?><p><input type="submit" value="Envoyer"/></p></form>
<?php }
echo '<br/><a href="page_index.php">Retour page précédente</a>';
require 'footer.php'; ?>