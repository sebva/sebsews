<?php  require 'header.php';
if(isset($_POST['texte'])&&isset($_POST['page'])){
	if(mysql_query('UPDATE '.$mysqlTablePages.' SET text=\''.mysql_real_escape_string($_POST['texte']).'\' WHERE shorttitle=\''.$_POST['page'].'\''))
		echo 'Page '.$_POST['page'].' mise à jour correctement !';
	else
		echo 'Erreur, page non mise à jour';
}else{?>
	<form action="page_edit.php" method="post">
	<input type="hidden" name="page" value="<?php echo $_POST['page']?>" />
	<textarea name="texte">	
	<?php
	$reponse = mysql_query('SELECT * FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\'');
	$donnees = mysql_fetch_array($reponse);
	echo stripslashes($donnees['text']);?>
	</textarea>
	<script type="text/javascript">
		CKEDITOR.replace( 'texte' );
	</script>
	<input type="submit" value="Envoyer"/></form>
<?php }
echo '<br/><a href="page_index.php">Retour page précédente</a>';
require 'footer.php'; ?>