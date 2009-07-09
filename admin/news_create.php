<? require 'header.php';
function NoAccent($texte){
	$acc='ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ';
	$noacc='AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn';
    $texte = strtr($texte,$acc,$noacc); return $texte;
}
if(isset($_POST['link'])&&isset($_POST['title'])&&isset($_POST['auteur'])&&isset($_POST['text'])) {
	//if(mysql_query("INSERT INTO newsite VALUES('', '$_POST['shorttitle']', '', '$_POST['title']', '$_POST['text']')"))
	if(mysql_query("INSERT INTO `".$mysqlDb."`.`".$mysqlTableNews."` (
`id` ,
`date` ,
`link` ,
`auteur` ,
`title` ,
`text` ,
`categorie` ,
`categorieshort`
)
VALUES (
NULL ,
CURRENT_TIMESTAMP , '".$_POST['link']."', '".mysql_real_escape_string($_POST['auteur'])."', '".mysql_real_escape_string($_POST['title'])."', '".mysql_real_escape_string($_POST['text'])."' , '".mysql_real_escape_string($_POST['categorie'])."' , '".mysql_real_escape_string(strtolower(NoAccent($_POST['categorie'])))."'
)"))
		echo 'News créée avec succès !';
	else
		echo 'Erreur, news non créée';
}else{?>
	<form action="news_create.php" method="post">
	<p><label>Auteur de la news</label><input type="text" name="auteur"/><br/>
	<label>Titre de la news</label><input type="text" name="title"/><br/>
	<label>Catégorie de la news</label><input type="text" name="categorie"/><br/>
	La news doit-elle être affichée directement lors du listage des news ou affichée limitée à 100 caractères et avoir un lien vers la news complète ?<br/>
	<label>Affichée directement</label><input type="radio" name="link" value="0"/>
	<label>Limitée à 100 caractères avec lien</label><input type="radio" name="link" value="1" checked="checked" /></p>
	<?
	include_once("fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('text') ;
	$oFCKeditor->BasePath = 'fckeditor/' ;
	$oFCKeditor->Height = 420;
	$oFCKeditor->Value = '';
	$oFCKeditor->Create() ;
	?><input type="submit" value="Envoyer"/></form>
<?}
echo '<br/><a href="news_index.php">Retour page précédente</a>';
require 'footer.php'; ?>