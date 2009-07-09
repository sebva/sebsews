<?php  require 'header.php';?>
<ul>

<!--Editer-->
<li><form action="news_edit.php" method="post"><label>News à éditer : </label><select name="news">
<?php 
$reponse = mysql_query('SELECT title,id FROM '.$mysqlTableNews.' ORDER BY date');
while ($donnees = mysql_fetch_array($reponse) )
{
	echo '<option value="'.$donnees['id'].'">'.stripslashes($donnees['title']).'</option>';
}?>
</select><br/>
<input type="submit" value="&Eacute;diter la news"/></form></li>

<!--Créer-->
<li><a href="news_create.php">Créer une news</a></li>

<!--Supprimer-->
<li><form action="news_delete.php" method="post"><label>News à supprimer : </label><select name="news">
<?php 
$reponse = mysql_query('SELECT title,id FROM '.$mysqlTableNews.' ORDER BY date');
while ($donnees = mysql_fetch_array($reponse) )
{
	echo '<option value="'.$donnees['id'].'">'.stripslashes($donnees['title']).'</option>';
}?>
</select><br/>
<label>&Ecirc;tes-vous vraiement et absolument s&ucirc;r de vouloir supprimer une news (cette action est irréversible) ?&nbsp;</label><input type="checkbox" name="confirm" value="yes" /><br/>
<input type="submit" value="Supprimer la news"/></form></li>

<li><a href="index.php">Retour accueil administration</a></li>
</ul>
<?php  require 'footer.php'; ?>