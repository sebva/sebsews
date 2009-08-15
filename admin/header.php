<?php 
require_once('../config.php');
mysql_connect($mysqlHost, $mysqlUser, $mysqlPassword);
mysql_set_charset ('UTF8');
mysql_select_db($mysqlDb);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<title><?php echo $nom?> :: Administration</title>		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<link rel="stylesheet" type="text/css" href="style.css" media="all" />
		<link rel="icon" href="http://<?php echo $domaine.$repertoire?>/favicon.png" />
		<link rel="Shortcut Icon" href="http://<?php echo $domaine.$repertoire?>/favicon.png" />
	</head>
	<body>
	<div id="conteneur">		
			<div id="header_extensible">				
				<div id="header"></div>				
			</div>				
			<div id="contenu">
			<?php if(strpos($_SERVER["PHP_SELF"], 'news_')!==false){?>
				<h2>Gestion des news</h2>
			<?php }elseif(strpos($_SERVER["PHP_SELF"], 'page_')!==false){?>
				<h2>Gestion des pages</h2>
			<?php }elseif(strpos($_SERVER["PHP_SELF"], 'template_')!==false){?>
				<h2>Changement de template</h2>
			<?php }else{?>
				<h2>Accueil administration</h2>
			<?php }?>