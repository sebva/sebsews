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
// news.php

require('header.php');

switch($_GET['action'])
{
	default:
	case 'index':
		//Index ?>
			<ul>

			<!--Editer-->
			<li><form action="news.php?action=edit" method="post"><label>News à éditer : </label><select name="news">
			<?php 
			$reponse = mysql_query('SELECT title,id FROM '.$mysqlTableNews.' ORDER BY date');
			while ($donnees = mysql_fetch_array($reponse) )
			{
				echo '<option value="'.$donnees['id'].'">'.stripslashes($donnees['title']).'</option>';
			}?>
			</select><br/>
			<input type="submit" value="Éditer la news"/></form></li>

			<!--Créer-->
			<li><a href="news.php?action=create">Créer une news</a></li>

			<!--Supprimer-->
			<li><form action="news.php?action=delete" method="post"><label>News à supprimer : </label><select name="news">
			<?php 
			$reponse = mysql_query('SELECT title,id FROM '.$mysqlTableNews.' ORDER BY date');
			while ($donnees = mysql_fetch_array($reponse) )
			{
				echo '<option value="'.$donnees['id'].'">'.stripslashes($donnees['title']).'</option>';
			}?>
			</select><br/>
			<label>Êtes-vous vraiement et absolument sûr de vouloir supprimer une news (cette action est irréversible) ?&nbsp;</label><input type="checkbox" name="confirm" value="yes" /><br/>
			<input type="submit" value="Supprimer la news"/></form></li>

			<li><a href="index.php">Retour accueil administration</a></li>
			</ul><?php
		//Fin Index
		break;
		
	case 'create':
		//Créer
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
				<form action="news.php?action=create" method="post">
				<p><label>Auteur de la news</label><input type="text" name="auteur"/><br/>
				<label>Titre de la news</label><input type="text" name="title"/><br/>
				<label>Catégorie de la news</label><input type="text" name="categorie"/><br/>
				La news doit-elle être affichée directement lors du listage des news ou affichée limitée à 100 caractères et avoir un lien vers la news complète ?<br/>
				<label>Affichée directement</label><input type="radio" name="link" value="0"/>
				<label>Limitée à 100 caractères avec lien</label><input type="radio" name="link" value="1" checked="checked" /></p>
				<textarea name="text"></textarea>
				<script type="text/javascript">
					CKEDITOR.replace( 'text' );
				</script>
				<input type="submit" value="Envoyer"/></form>
			<?php }
			echo '<br/><a href="news.php">Retour page précédente</a>';
		//Fin créer
		break;
		
	case 'edit':
		//Éditer
			if(isset($_POST['texte'])&&isset($_POST['news'])){
				if(mysql_query('UPDATE '.$mysqlTableNews.' SET text=\''.mysql_real_escape_string($_POST['texte']).'\' WHERE id=\''.$_POST['news'].'\''))
					echo 'News n&deg; '.$_POST['news'].' mise à jour correctement !';
				else
					echo 'Erreur, news non mise à jour';
			}else{?>
				<form action="news.php?action=edit" method="post">
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
			echo '<br/><a href="news.php">Retour page précédente</a>';
		//Fin éditer
		break;
		
	case 'delete':
		//Supprimer
			if($_POST['confirm']=='yes')
			{
				echo 'Suppression de la news n°'.$_POST['news'].'...';
				if(mysql_query('DELETE FROM '.$mysqlTableNews.' WHERE id = \''.$_POST['news'].'\''))
					echo '<span style="color:green;">OK !</span>';
				else
					echo '<span style="color:red;">Erreur !</span>';
			}
			else
				echo 'Vous n\'avez pas confirmé l\'action ! Action annulée<br/><span style="color:red;">Erreur !</span>';
			echo '<br/><a href="news.php">Retour page précédente</a>';
		//Fin supprimer
		break;
}

require('footer.php'); ?>