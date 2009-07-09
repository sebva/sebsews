<? require 'header.php';
if(isset($_POST['texte'])&&isset($_POST['page'])){
	if(mysql_query('UPDATE '.$mysqlTablePages.' SET text=\''.mysql_real_escape_string($_POST['texte']).'\' WHERE shorttitle=\''.$_POST['page'].'\''))
		echo 'Page '.$_POST['page'].' mise à jour correctement !';
	else
		echo 'Erreur, page non mise à jour';
}else{?>
	<form action="page_edit.php" method="post">
	<input type="hidden" name="page" value="<?echo $_POST['page']?>" />
	<?
	include_once("fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('texte') ;
	$oFCKeditor->BasePath = 'fckeditor/' ;
	$oFCKeditor->Height = 420;
	$reponse = mysql_query('SELECT * FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\'');
		while ($donnees = mysql_fetch_array($reponse) )
		{
			$oFCKeditor->Value =stripslashes($donnees['text']);
		}
	$oFCKeditor->Create() ;
	?><input type="submit" value="Envoyer"/></form>
<?}
echo '<br/><a href="page_index.php">Retour page précédente</a>';
require 'footer.php'; ?>