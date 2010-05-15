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
if(file_exists('config.php')){
	require('config.php');
	require('func/cache.php');
	//bof Prérequis	
		$page=$_GET['page'];	//$page est le nom court de la page
		if($page=='') $page='index';	//La page par défaut est index
		$erreur = 200; //Aucune erreur par défaut
		//bof Connexion à MySQL
			mysql_connect($mysqlHost, $mysqlUser, $mysqlPassword);
			mysql_set_charset ('UTF8');
			mysql_select_db($mysqlDb);
		//eof Connexion à MySQL	
	//eof Prérequis

	//bof Génération du texte dont menu	
		include('func/menu.php');	//Inclusion de la fonction du menu	
		//bof Texte
			if(file_exists('pages/'.$page.'.php'))					
				require('pages/'.$page.'.php');
			else
				require('func/texte.php');
		//eof Texte
		
		/*Navigation : */ require('func/navigation.php');
	//eof Génération du texte dont menu

	//bof Fin de page
		mysql_close();	//Fermeture de la connexion à MySQL
		$text=stripslashes($text);	//On retire d'éventuels \' venant de MySQL
		$title=stripslashes($title);
		$text=preg_replace('#\$domaine#', $domaine, $text);
		$text=preg_replace('#\$repertoire#', $repertoire, $text);
	//eof Fin de page

	//bof Redirection si faux domaine
		if($_SERVER['SERVER_NAME']!=$domaine){
			header("Status: 301 Moved Permanently", false, 301);
			header("Location: http://".$domaine.$_SERVER["REQUEST_URI"]);
			exit;
		}
	//eof Redirection si faux domaine

	//bof Gestion des erreurs HTTP
		if($erreur!=200)
			require('func/erreur.php');
	//eof Gestion des erreurs HTTP

	//bof Headers HTTP
		$type = 'text/html';
		//if(strpos($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml')!==false) $type='application/xhtml+xml';
		header ('Content-type:'.$type.'; charset=utf-8');
		header ('Content-Style-Type: text/css');
	//bof Headers HTTP

	require('templates/loader.php');
} else {
	header("Status: 301 Moved Permanently", false, 301);
	if(preg_match('#.*/#', $_SERVER["REQUEST_URI"])) $chemin=$_SERVER["REQUEST_URI"].'install/';
	else $chemin=preg_replace('#^(.*)[^/].(php|html)$#', "$1", $_SERVER['REQUEST_URI']).'install/';
	header("Location: http://".$_SERVER['HTTP_HOST'].$chemin);
	exit;
}
?>