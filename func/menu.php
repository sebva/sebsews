<?php
if($page!='menu')
{
	$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' ORDER BY id');
	while ($donnees = mysql_fetch_array($reponse) )
	{
		switch($donnees['shorttitle'])		
		{	//S'il y a des sous-menus à créer
			case 'news':
				$menu.='<li id="news">
						<a href="http://'.$domaine.$repertoire;$menu.=($rewrite=='on') ?'/news.html' :'/?page=news';$menu.='">News</a>
						<ul class="sousmenu">
							<li><a href="http://'.$domaine.$repertoire;$menu.=($rewrite=='on') ?'/news.xml':'/rss.php';$menu.='">Flux RSS</a></li>
						</ul>
					</li>';
				break;		
			default:
				$menu.='<li><a href="http://'.$domaine.$repertoire.'/';$menu.=($rewrite=='on') ?'':'?page=';$menu.=$donnees['shorttitle'];$menu.=($rewrite=='on') ?'.html':'';$menu.='">'.$donnees['title'].'</a></li>';
				break;
		}
	}
}
else
	$erreur = 403;
?>