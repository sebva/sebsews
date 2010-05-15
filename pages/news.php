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
$title='News';
if(isset($_GET['news'])){
	//Affichage de la news en question
	$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' WHERE id = '.$_GET['news']);
	$donnees = mysql_fetch_array($reponse);
	
	$news=$donnees['id'];
	$categorie = $donnees['categorie'];
	$categorieshort = $donnees['categorieshort'];
	$title.=' - '.$donnees['categorie'].' - '.$donnees['title'];
	$text=$donnees['text'];
	$timestamp = strtotime($donnees['date']);
	setlocale(LC_TIME, "fr_CH");
	$text.='<p id="auteurnews">&Eacute;crit le '.strftime('%A %d %B %Y',$timestamp).' par '.$donnees['auteur'].' dans la catégorie ';
	$text.='<a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news/':'/?page=news&amp;categorie=';$text.=$donnees['categorieshort'].'">'.$donnees['categorie'].'</a>';	
	$text.='</p><p class="noprint"><a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news.html':'/?page=news';$text.='">Retour à la liste des news</a></p>';	
}else if(isset($_GET['categorie'])){
	//Affichage par catégories
	if($_GET['categorie']=='categories'){
		//News par catégories
		$title .= ' - Catégories';
		$categorie = 'Catégories';
		$categorieshort = 'categories';
		$text = '<ul>';
		$reponse = mysql_query('SELECT categorie,categorieshort FROM '.$mysqlTableNews.' GROUP BY categorie') or die(mysql_error());
		while ($donnees = mysql_fetch_array($reponse) )
		{
			$text.='<li><a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news/':'/?page=news&amp;categorie=';$text.=$donnees['categorieshort'].'">'.$donnees['categorie'].'</a> (';
			//bof Magouille pour compter les news par catégorie !!TODO: A mettre au propre !
				$reponse2 = mysql_query('SELECT categorieshort FROM '.$mysqlTableNews.' WHERE categorieshort = \''.$donnees['categorieshort'].'\' ORDER BY date');
				$iCompt=0;
				while ($donnees2 = mysql_fetch_array($reponse2) )
				{
					$iCompt++;
				}
				$text.=$iCompt.' news)</li>';
			//eof Magouille pour compter les news par catégorie
		}
		$iCompt=0;
		$reponse3 = mysql_query('SELECT categorieshort FROM '.$mysqlTableNews);
		while ($donnees3 = mysql_fetch_array($reponse3) )
		{
			$iCompt++;
		}
		$text.='<li><a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news.html':'/?page=news';$text.='">Tout</a> ('.$iCompt.' news)</li>';
		$text .= '</ul>';
	}else{
		$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' WHERE categorieshort = \''.$_GET['categorie'].'\' ORDER BY date');
		while ($donnees = mysql_fetch_array($reponse) )
		{
			$timestamp = strtotime($donnees['date']);
			$text.='<p>';
			setlocale(LC_TIME, "fr_CH");
			$text.='(<em>'.strftime('%A %d %B %Y',$timestamp).' par '.$donnees['auteur'].'</em>) ';
			if($donnees['link']==1){$text.='<a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news/':'/?page=news&amp;news=';$text.=$donnees['id'].'">';}
			$text.=$donnees['title'];
			if($donnees['link']==1) $text.='</a>';
			if($donnees['link']==1)
				$texttemp = substr(strip_tags($donnees['text']),0,100);
			else
				$texttemp = strip_tags($donnees['text'],'<a>');
			$text.='<br/><em>'.$texttemp;
			if($donnees['link']==1) $text.='&nbsp;[...]';
			$text.='</em></p>';
			$title = 'News - '.$donnees['categorie'];
			$categorie = $donnees['categorie'];
			$categorieshort = $donnees['categorieshort'];
		}
	}
	if($text=='')
		$text = '<p>Il n\'y a aucune news à afficher</p>';
}else{	
	//Affichage de toutes les news
		$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' ORDER BY date');
		while ($donnees = mysql_fetch_array($reponse) )
		{
			$timestamp = strtotime($donnees['date']);
			$text.='<p>';
			setlocale(LC_TIME, "fr_CH");
			$text.='(<em>'.strftime('%A %d %B %Y',$timestamp).' par '.$donnees['auteur'].'</em>) ';
			if($donnees['link']==1){$text.='<a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news/':'/?page=news&amp;news=';$text.=$donnees['id'].'">';}
			$text.=$donnees['title'];
			if($donnees['link']==1) $text.='</a>';
			if($donnees['link']==1)
				$texttemp = substr(strip_tags($donnees['text']),0,100);
			else
				$texttemp = strip_tags($donnees['text'],'<a>');
			$text.='<br/><em>'.$texttemp;
			if($donnees['link']==1) $text.='&nbsp;[...]';
			$text.='</em></p>';
			$title = 'News - Tout';
			//$categorie = 'Tout';
			//$categorieshort = '';
		}
		$text.='<a href="http://'.$domaine.$repertoire;
		$text.=($rewrite=='on') ?'/news/categories':'/?page=news&amp;categorie=categories';
		$text.='">Affichage par catégories</a>';
}
if($text==''&&$title=='News')					
	$erreur = 404;
if(!isset($categorieshort)) $categorieshort=strtolower($categorie);
	?>