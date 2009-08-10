<?php
require_once('config.php');
//bof Prérequis	
	$page=$_GET['page'];	//$page est le nom court de la page
	if($page=='') $page='accueil';	//La page par défaut est accueil
	$erreur = 200; //Aucune erreur par défaut
	$ok='ok';	//!!!Obligatoire!!! Sinon génère une erreur 403	
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

if($ok!='ok')
	$erreur = 403; //Si demande directe du fichier base.php

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
?>