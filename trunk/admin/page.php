<?php
/*
  	Séb's Easy Website, a tiny CMS for your personal website
	Copyright (C) 2010 Sébastien Vaucher
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// page.php

require('header.php');

switch($_GET['action'])
{
	default:
	case 'index':
		//Index ?>
			<ul>
	
			<!--Editer-->	
			<li><form action="page.php?action=edit" method="post"><label>Page à éditer : </label><select name="page">
			<?php 
			$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' WHERE menuseul=0 ORDER BY id');
			while ($donnees = mysql_fetch_array($reponse) )
			{
				echo '<option value="'.$donnees['shorttitle'].'">'.stripslashes($donnees['title']).'</option>';
			}?>
			</select><br/>
			<input type="submit" value="&Eacute;diter la page"/></form></li>
			
			<!--Créer-->
			<li><a href="page.php?action=create">Créer une page</a></li>
			
			<!--Supprimer-->	
			<li><form action="page.php?action=delete" method="post"><label>Page à supprimer : </label><select name="page">
			<?php 
			$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' ORDER BY id');
			while ($donnees = mysql_fetch_array($reponse) )
			{
				if($donnees['shorttitle']!='news'&&$donnees['shorttitle']!='index') echo '<option value="'.$donnees['shorttitle'].'">'.stripslashes($donnees['title']).'</option>';
			}?>
			</select><br/>
			<label>&Ecirc;tes-vous vraiement et absolument s&ucirc;r de vouloir supprimer une page (cette action est irréversible) ?&nbsp;</label><input type="checkbox" name="confirm" value="yes" /><br/>
			<input type="submit" value="Supprimer la page"/></form></li>
			
			<!--Ajouter image-->
			<li><a href="image.php">Ajouter/supprimer des images à une page</a></li>
			
			<!--News-->
			<li><a href="index.php">Retour accueil administration</a></li>
			</ul> <?php
		//Fin index
		break;
		
		
	case 'create':
		//Créer
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
				<form action="page.php?action=create" method="post">
				<p><label>Nom court de la page (sans espaces ni accents ou caractères spéciaux) </label><input type="text" name="shorttitle"/><br/>
				<label>Nom complet de la page</label><input type="text" name="title"/><br/>
				<label>Entrée seulement pour menu (sans texte) <strong>!!! Un fichier portant le nom court et l'extension .php doit être placé dans le répertoire pages !!!</strong></label><input type="checkbox" name="menuseul" /></p>
				<textarea name="text"></textarea>
				<script type="text/javascript">
					CKEDITOR.replace( 'text' );
				</script>
				<p><input type="submit" value="Envoyer"/></p></form>
			<?php }
			echo '<br/><a href="page.php">Retour page précédente</a>';
		//Fin créer
		break;
		
	case 'edit':
		//Editer
			
			if(isset($_POST['texte'])&&isset($_POST['page'])){
				if(mysql_query('UPDATE '.$mysqlTablePages.' SET text=\''.mysql_real_escape_string($_POST['texte']).'\' WHERE shorttitle=\''.$_POST['page'].'\''))
					echo 'Page '.$_POST['page'].' mise à jour correctement !';
				else
					echo 'Erreur, page non mise à jour';
			}else{?>
				<form action="page.php?action=edit" method="post">
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
			echo '<br/><a href="page.php">Retour page précédente</a>';
		//Fin éditer
		break;
		
	case 'delete':
		//Supprimer
			if($_POST['confirm']=='yes')
			{
				echo 'Suppression de la page '.$_POST['page'].'...';
				if(mysql_query('DELETE FROM '.$mysqlTablePages.' WHERE shorttitle = \''.$_POST['page'].'\''))
					echo '<span style="color:green;">OK !</span>';
				else
					echo '<span style="color:red;">Erreur !</span>';
			}
			else
				echo 'Vous n\'avez pas confirmé l\'action ! Action annulée<br/><span style="color:red;">Erreur !</span>';
			echo '<br/><a href="page.php">Retour page précédente</a>';
		//Fin supprimer
		break;
}

require('footer.php'); ?>