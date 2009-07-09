<? require 'header.php';?>
	<ul>
	
	<!--Editer-->	
	<li><form action="page_edit.php" method="post"><label>Page à éditer : </label><select name="page">
	<?
	$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' WHERE menuseul=0 ORDER BY id');
	while ($donnees = mysql_fetch_array($reponse) )
	{
		echo '<option value="'.$donnees['shorttitle'].'">'.stripslashes($donnees['title']).'</option>';
	}?>
	</select><br/>
	<input type="submit" value="&Eacute;diter la page"/></form></li>	
	
	<!--Créer-->
	<li><a href="page_create.php">Créer une page</a></li>
	
	<!--Supprimer-->	
	<li><form action="page_delete.php" method="post"><label>Page à supprimer : </label><select name="page">
	<?
	$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' ORDER BY id');
	while ($donnees = mysql_fetch_array($reponse) )
	{
		if($donnees['shorttitle']!='news'&&$donnees['shorttitle']!='accueil') echo '<option value="'.$donnees['shorttitle'].'">'.stripslashes($donnees['title']).'</option>';
	}?>
	</select><br/>
	<label>&Ecirc;tes-vous vraiement et absolument s&ucirc;r de vouloir supprimer une page (cette action est irréversible) ?&nbsp;</label><input type="checkbox" name="confirm" value="yes" /><br/>
	<input type="submit" value="Supprimer la page"/></form></li>	
	
	<!--News-->
	<li><a href="index.php">Retour accueil administration</a></li>
	</ul>
<? require 'footer.php'; ?>