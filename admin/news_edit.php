<? require 'header.php';
if(isset($_POST['texte'])&&isset($_POST['news'])){
	if(mysql_query('UPDATE '.$mysqlTableNews.' SET text=\''.mysql_real_escape_string($_POST['texte']).'\' WHERE id=\''.$_POST['news'].'\''))
		echo 'News n&deg; '.$_POST['news'].' mise à jour correctement !';
	else
		echo 'Erreur, news non mise à jour';
}else{?>
	<form action="news_edit.php" method="post">
	<input type="hidden" name="news" value="<?echo $_POST['news']?>" />
	<?
	include_once("fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('texte') ;
	$oFCKeditor->BasePath = 'fckeditor/' ;
	$oFCKeditor->Height = 420;
	$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' WHERE id = \''.$_POST['news'].'\'');
		while ($donnees = mysql_fetch_array($reponse) )
		{
			$oFCKeditor->Value =stripslashes($donnees['text']);
		}
	$oFCKeditor->Create() ;
	?><input type="submit" value="Envoyer"/></form>
<?}
echo '<br/><a href="news_index.php">Retour page précédente</a>';
require 'footer.php'; ?>