<?php  require 'header.php';
if(isset($_POST['texte'])&&isset($_POST['news'])){
	if(mysql_query('UPDATE '.$mysqlTableNews.' SET text=\''.mysql_real_escape_string($_POST['texte']).'\' WHERE id=\''.$_POST['news'].'\''))
		echo 'News n&deg; '.$_POST['news'].' mise à jour correctement !';
	else
		echo 'Erreur, news non mise à jour';
}else{?>
	<form action="news_edit.php" method="post">
	<input type="hidden" name="news" value="<?echo $_POST['news']?>" />
	<textarea name="texte"><?php 	
	$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' WHERE id = \''.$_POST['news'].'\'');
	$donnees = mysql_fetch_array($reponse);
	echo stripslashes($donnees['text']);
	?></textarea>
	<script type="text/javascript">
		CKEDITOR.replace( 'texte' );
	</script>
	<input type="submit" value="Envoyer"/></form>
<?php }
echo '<br/><a href="news_index.php">Retour page précédente</a>';
require 'footer.php'; ?>