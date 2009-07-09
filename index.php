<?php
include_once('config.php');
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
		if(file_exists('func/'.$page.'.php'))					
			require('func/'.$page.'.php');
		else
			require('func/texte.php');
	//eof Texte
//eof Génération du texte dont menu

//bof Fin de page
	mysql_close();	//Fermeture de la connexion à MySQL
	$text=stripslashes($text);	//On retire d'éventuels \' venant de MySQL
	$title=stripslashes($title);
	$text=preg_replace('#\$domaine#', $domaine, $text);
	$text=preg_replace('#\$repertoire#', $repertoire, $text);
	require 'base.php';	//On inclut le template de base de la page
//eof Fin de page
?>