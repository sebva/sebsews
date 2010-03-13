<?php //image.php

require('header.php');

switch($_GET['action'])
{
	default:
	case 'index':
		//Index
			?><ul>
				<li><a href="image.php?action=add">Ajouter une image à une page</a></li>
				<li><a href="image.php?action=delete">Supprimer une/des image(s) d'une page</a></li>
			</ul><?php
		//Fin index
		break;
	
	case 'add':
		//Ajouter
			echo '<p>';
			if(!isset($_POST['page'])&&!isset($_FILES['image'])) { ?>	
					Vous pouvez joindre des images pour chaque page créée<br/>
					<strong>Attention : pour que la fonction soit possible, il faut que l'écriture soit activée dans le répertoire upload/ (chmod 755 ou 777)</strong><br/>Les Jpeg, Gif et Png sont acceptés</p>
					<p>
					<form action="image.php?action=add" method="post" enctype="multipart/form-data"><label>Page ciblée : </label><select name="page">
					<?php 
					$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' WHERE menuseul=0 ORDER BY id');
					while ($donnees = mysql_fetch_array($reponse) )
					{
						echo '<option value="'.$donnees['shorttitle'].'">'.stripslashes($donnees['title']).'</option>';
					}?>
					</select><br/>
					<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
					 Fichier : <input type="file" name="image" accept="image/jpeg,image/gif,image/png" >
					<input type="submit" value="Envoyer"/></form></p>
			<?php } else {
				echo 'Traitement du fichier reçu...';
				$dossier = '../upload/';
				// Aucun préfixe
				// fonctionne uniquement avec PHP 5 et plus récent
				$fichier = md5(uniqid());
				$taille_maxi = 1000000;
				$taille = filesize($_FILES['image']['tmp_name']);
				$extensions = array('.jpg', '.gif', '.png');
				$extension = strtolower(preg_replace('#^.*(\.[a-zA-Z]{3})$#', "$1", $_FILES['image']['name'])); 
				//Début des vérifications de sécurité...
				if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
				{
					 $erreur = 'type';
				}
				if($taille>$taille_maxi)
				{
					 $erreur = 'big';
				}
				if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
				{
					if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier.$extension)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
					{
						echo '<span style="color:green;">OK !</span>
						<br/>Lecture de MySQL...';
						if($reponse = mysql_query('SELECT images FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\'') or die(mysql_error()))
							echo '<span style="color:green;">OK !</span>';
						else
							echo '<span style="color:red;">Erreur !</span>';
							
						$donnees = mysql_fetch_array($reponse);
						$imagesActuel = $donnees['images'];
						echo '<br/>Écriture dans MySQL...';
						if(mysql_query('UPDATE '.$mysqlTablePages.' SET images=\''.$imagesActuel.'^'.$fichier.$extension.'$\' WHERE shorttitle=\''.$_POST['page'].'\'') or die(mysql_error()))
							echo '<span style="color:green;">OK !</span>';
						else
							echo '<span style="color:red;">Erreur !</span>';
						
						echo '<br/>Création du thumbnail...';
						if($extension == '.png')
							$source = imagecreatefrompng('../upload/'.$fichier.'.png'); // La photo est la source
						if($extension == '.gif')
							$source = imagecreatefromgif('../upload/'.$fichier.'.gif'); // La photo est la source
						if($extension == '.jpg')
							$source = imagecreatefromjpeg('../upload/'.$fichier.'.jpg'); // La photo est la source
						$destination = imagecreatetruecolor(125, 100); // On crée la miniature vide

						// Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
						$largeur_source = imagesx($source);
						$hauteur_source = imagesy($source);
						$largeur_destination = imagesx($destination);
						$hauteur_destination = imagesy($destination);

						// On crée la miniature
						imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

						// On enregistre la miniature
						imagejpeg($destination, '../upload/thumbs/thumb_'.$fichier.'.jpg');
						echo '<span style="color:green;">OK !</span>';

					}
					else //Sinon (la fonction renvoie FALSE).
					{
						echo '<span style="color:red;">Erreur !</span> Impossible de déplacer le fichier vers upload/. Vérifiez chmod 755 ou 777 !';
					}
				}
				else
				{
					echo '<span style="color:red;">Erreur ! </span>';
					if($erreur=='type') echo 'Type de fichier interdit.';
					if($erreur=='big') echo 'Fichier trop grand.';
				}
			}
			echo '</p><p><a href="image.php">Retour à l\'accueil de la gestion des images</a></p>';
		//Fin ajouter
		break;
		
	case 'delete':
		//Supprimer
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
				<form action="image.php?action=delete" method="post">
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
				<form action="image.php?action=delete" method="post"><select name="page"><?php
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
		//Fin supprimer
		break;
}

require('footer.php');