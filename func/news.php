<?php
$title='News';
if(isset($_GET['news'])){
	//Affichage de la news en question
	$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' WHERE id = '.$_GET['news']);
	while ($donnees = mysql_fetch_array($reponse) )
	{
		$news=$donnees['id'];
		$categorie = $donnees['categorie'];
		$title.=' - '.$donnees['categorie'].' - <!--Debut titre-->'.$donnees['title'].'<!--Fin titre-->';
		$text=$donnees['text'];
		$timestamp = strtotime($donnees['date']);
		setlocale(LC_TIME, "fr_CH");
		$text.='<p id="auteurnews">&Eacute;crit le '.strftime('%A %d %B %Y',$timestamp).' par '.$donnees['auteur'].' <img src="http://'.$domaine.$repertoire.'/func/emailimage.php?text='.strtolower(str_replace(' ', '.', $donnees['auteur'])).'" alt="e-mail de '.$donnees['auteur'].'" /></p><p class="noprint"><a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news.html':'/?page=news';$text.='">Retour à la liste des news</a></p>';
	}
}else if(isset($_GET['categorie'])){
	//Affichage par catégories
	if($_GET['categorie']=='tout'){
	//Affichage de toute les news
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
			$categorie = 'Tout';
		}	
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
		}
	}
	if($text=='')
		$text = '<p>Il n\'y a aucune news à afficher</p>';
}else{

	//News
	$title .= ' - Catégories';
	$text = '<ul>';
	$reponse = mysql_query('SELECT categorie,categorieshort FROM '.$mysqlTableNews.' GROUP BY categorie') or die(mysql_error());
	while ($donnees = mysql_fetch_array($reponse) )
	{
		$text.='<li><a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news/':'/?page=news&amp;categorie=';$text.=$donnees['categorieshort'].'">'.$donnees['categorie'].'</a> (';
		$reponse2 = mysql_query('SELECT categorieshort FROM '.$mysqlTableNews.' WHERE categorieshort = \''.$donnees['categorieshort'].'\' ORDER BY date');
		$iCompt=0;
		while ($donnees2 = mysql_fetch_array($reponse2) )
		{
			$iCompt++;
		}
		$text.=$iCompt.' news)</li>';
	}
	$iCompt=0;
	$reponse3 = mysql_query('SELECT categorieshort FROM '.$mysqlTableNews);
	while ($donnees3 = mysql_fetch_array($reponse3) )
	{
		$iCompt++;
	}
	$text.='<li><a href="http://'.$domaine.$repertoire;$text.=($rewrite=='on') ?'/news/tout':'/?page=news&amp;categorie=tout';$text.='">Tout</a> ('.$iCompt.' news)</li>';
	$text .= '</ul>';
}
if($text==''&&$title=='News')					
	$erreur = 404;
	?>