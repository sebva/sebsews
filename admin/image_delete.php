<?php require('header.php');
echo '<p>';
if(isset($_POST['confirm'])&&isset($_POST['page'])) {
	if($_POST['confirm'] == 1) {
		//Suppression
		echo '<br/>Lecture de MySQL...';
			if($reponse = mysql_query('SELECT images FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\'') or die(mysql_error()))
				echo '<span style="color:green;">OK !</span>';
			else
				echo '<span style="color:red;">Erreur !</span>';
		$donnees = mysql_fetch_array($reponse);
		$images=$donnees['images'];
		$images=preg_replace('#^\^(.*)\$$#', "$1", $images);
		$imagesArray = explode('$^', $images);
		$iComptParam=0;
		foreach($imagesArray as $element)
		{
			if($_POST[$iComptParam] != 1)
			{
				$final .= '^'.$element.'$';
			}
			$iComptParam++;
		}
		echo '<br/>Écriture dans MySQL...';
			if(mysql_query('UPDATE '.$mysqlTablePages.' SET images=\''.$final.'\' WHERE shorttitle=\''.$_POST['page'].'\'') or die(mysql_error()))
				echo '<span style="color:green;">OK !</span>';
			else
				echo '<span style="color:red;">Erreur !</span>';
		echo '<br/>Suppression des fichiers...';
		$iComptParam=0;
		foreach($imagesArray as $element)
		{
			if($_POST[$iComptParam] == 1)
			{
				if(unlink('../upload/'.$element)  && unlink('../upload/thumbs/thumb_'.preg_replace("#^(.+)\.[a-z]{3}$#", "$1", $element).'.jpg'))
					echo '<span style="color:green;">OK !</span>';
				else
					echo '<span style="color:red;">Erreur !</span>';
			}
			$iComptParam++;
		}		
			
	} else {
		echo 'Vous n\'avez pas confirmé l\'action : Suppression annulée !';
	}
}
else if(isset($_POST['page'])) {
	//Liste des images de la page ?>
	<form action="image_delete.php" method="post">
		<?php $reponse = mysql_query('SELECT images FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\'');
		$donnees = mysql_fetch_array($reponse);
		$images=$donnees['images'];
		$images=preg_replace('#^\^(.*)\$$#', "$1", $images);
		$imagesArray = explode('$^', $images);
		echo '<table border="1"><caption>Images à supprimer</caption><tbody><tr>';
		$iCompt=0;
		$iComptParam=0;
		foreach($imagesArray as $element)
		{
			if($iCompt==5){
				echo '</tr><tr>';
				$iCompt=0;
			} 		
				echo '<td><img src="http://'.$domaine.$repertoire.'/upload/thumbs/thumb_'.(preg_replace('#^(.+)\.[a-zA-Z]{3}$#', "$1", $element)).'.jpg" alt="image" /><input type="checkbox" value="1" name="'.$iComptParam.'" /></td>';
				$iCompt++;
				$iComptParam++;
		}?>
		</tr></tbody></table>		
		<input type="hidden" name="page" value="<?php echo $_POST['page']; ?>" />
		<label>Je confirme vouloir supprimer : <input type="checkbox" value="1" name="confirm" />
		<input type="submit" value="Supprimer !" />
		</form>
<?php }
else {
	//Liste des pages
	?>
	Choisissez la page dans laquelle l'image à suprimer est contenue
	<form action="image_delete.php" method="post"><select name="page"><?php
	$reponse = mysql_query('SELECT shorttitle,title FROM '.$mysqlTablePages.' WHERE menuseul=0 AND images!=\'\' ORDER BY id');
	while ($donnees = mysql_fetch_array($reponse) )
	{
		echo '<option value="'.$donnees['shorttitle'].'">'.stripslashes($donnees['title']).'</option>';
	} ?>
	</select>
	<input type="submit" value="Suite &gt;&gt;" />
	</form><?php
}
echo '<br/><a href="page_index.php">Retour accueil gestion des pages</a></p>';
require('footer.php'); ?>