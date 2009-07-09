<?
require_once('config.php'); //Inclusion des réglages

//bof Redirection si faux domaine
	if($_SERVER['SERVER_NAME']!=$domaine){
		header("Status: 301 Moved Permanently", false, 301);
		header("Location: http://".$domaine.$_SERVER["REQUEST_URI"]);
		exit;
	}
//bof Redirection si faux domaine

if($ok!='ok')
	$erreur = 403; //Si demande directe du fichier base.php

//bof Gestion des erreurs HTTP
	if($erreur!=200)
		require('func/erreur.php');
//eof Gestion des erreurs HTTP

//bof Headers HTTP
	$type = 'text/html';
	if(strpos($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml')!==false)
		$type='application/xhtml+xml';
	header ('Content-type:'.$type.'; charset=utf-8');
	header ('Content-Style-Type: text/css');
//bof Headers HTTP
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="<?echo $type?>; charset=utf-8" />
		<title><? echo $nom .' :: '. $title ?></title>
		<meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="DC.Language" content="fr" />
		<meta name="Generator" content="Notepad ++" />
		<?if ($googleWebmasterToolsMetaTag !=''){?><meta name="verify-v1" content="<?echo $googleWebmasterToolsMetaTag?>" /><?}?>
		<link rel="icon" href="http://<?echo $domaine.$repertoire?>/favicon.png" />
		<link rel="Shortcut Icon" href="http://<?echo $domaine.$repertoire?>/favicon.png" />
        <link rel="stylesheet" type="text/css" media="all" href="http://<?echo $domaine.$repertoire?>/style.css" />
		<link rel="alternate" type="application/rss+xml" title="<?echo $nom?> :: News" href="http://<?echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>" />
		<!--Sortie des Frames--><script type="text/javascript">
			<!--
			if (window !=top ) {top.location=window.location;}
		   //-->
		</script>
		<?if(!in_array($_SERVER['REMOTE_ADDR'], $googleAnalyticsBlacklist) && $erreur == 200 && $googleAnalyticsCode != ''){?>
		<!--Google Analytics-->
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("<?echo $googleAnalyticsCode?>");
			pageTracker._trackPageview();
			} catch(err) {}
		</script>
		<?}?>
	</head>
	<body>
		<div id="conteneur">
			<div id="header_extensible">
				<div id="header">
				</div>
			</div>			
			<p id="navigation">				
				&nbsp;&nbsp;<a href="http://<?echo $domaine.$repertoire?>/"><?echo $nom?></a> &raquo; 
				<?if(isset($news)){?>
					<a href="http://<?echo $domaine.$repertoire; echo ($rewrite=='on') ? '/news.html' :'/?page=news';?>">News</a> &raquo; <a href="http://<?echo $domaine.$repertoire;echo ($rewrite=='on') ? '/news/' : '/?page=news&amp;categorie=';echo strtolower($categorie).'">'.$categorie?></a> &raquo; <a href="http://<?echo $domaine.$repertoire; echo ($rewrite=='on') ?'/news/':'/?page=news&amp;news=';echo $news?>"><?echo preg_replace('#^.*<\!--Debut titre-->(.*)<\!--Fin titre-->$#',"$1",$title)?></a>
				<?}else if(isset($categorie)){?>
					<a href="http://<?echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.html':'/?page=news';?>">News</a> &raquo; <a href="http://<?echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news/':'/?page=news&amp;categorie=';echo strtolower($categorie).'">'.$categorie?></a>
				<?}else{?>
					<a href="http://<?echo $domaine.$repertoire?>/<? echo ($rewrite=='on') ? '' : '?page=' ;echo $page;echo ($rewrite=='on') ?'.html':'';?>"><? echo $title ?></a><?}?>				
			</p>
			<div id="centre">
				<div id="menu">
					<div class="bouton">+ Menu
					</div>
					<ul id="menutitre">						
						<? echo $menu ?>
					</ul>
				</div>
				<div id="texte">
					<h1 class="soustitre"><? echo $title ?></h1>
					<div class="texte">
						<!--bof Texte--><? echo $text ?><!--eof Texte-->
					</div>
				</div>
				<p id="footer">
					<!--Copyright-->&copy; Copyright <?echo date(Y)?> - <a href="http://<?echo $domaine.$repertoire?>"><?echo $nom?></a>&nbsp;-&nbsp;
					<!--Veuillez SVP laisser cette indication, Merci--><a href="http://sebseasywebsite.sourceforge.net/">Powered by Séb's EasyWebSite</a><span class="finfooter">&nbsp;-&nbsp;
					<!--Administration--><a href="http://<?echo $domaine.$repertoire?>/admin/">Administration</a>&nbsp;-&nbsp;
					<!--RSS--><a href="http://<?echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>"><img src="http://<?echo $domaine.$repertoire?>/images/feedicon.png" alt="Flux RSS" height="20" width="20" /></a>
					<!--Valid RSS--><a href="http://validator.w3.org/feed/check.cgi?url=http%3A//<?echo $domaine.$repertoire;echo ($rewrite=='on') ?'/news.xml':'/rss.php';?>"><img src="http://<?echo $domaine.$repertoire?>/images/valid-rss.png" alt="Valid RSS" height="20" width="59" /></a>
					<!--Valid XHTML--><a href="http://validator.w3.org/check?uri=referer"><img src="http://<?echo $domaine.$repertoire?>/images/valid-xhtml11.png" alt="XHTML 1.1 Valide" height="20" width="59" /></a>
					<!--Valid CSS--><a href="http://jigsaw.w3.org/css-validator/check/referer"><img width="59" height="20" src="http://<?echo $domaine.$repertoire?>/images/valid-css2.png" alt="CSS 2.1 Valide !" /></a></span>
				</p>
			</div>
		</div>
	</body>
</html>
