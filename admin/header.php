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
		<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
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
			<?php }elseif(strpos($_SERVER["PHP_SELF"], 'image_')!==false){?>
				<h2>Gestion des images</h2>
			<?php }else{?>
				<h2>Accueil administration</h2>
			<?php }?>