<?php
/**
 * Autochargement de classes
 * @param class $classe
 */
function chargerClasse ($classe)
{
	require 'classes/'.strtolower($classe).'.class.php';
}
spl_autoload_register ('chargerClasse');


if(file_exists('config.php')){
	$config = new Config();
	$configOut = $config->config;
	unset($config);
	$title = $configOut['nom'];
	$domaine = $_SERVER['HTTP_HOST'];
	$repertoire = $configOut['repertoire'];
	unset($configOut);
	require('template.php');
	
	//bof Prérequis
		$page=$_GET['page'];	//$page est le nom court de la page
		if($page=='') $page='index';	//La page par défaut est index
	//eof Prérequis

	//bof Génération du texte dont menu
		$menu = new Menu();
		$menuOut = $menu->getMenu();
		unset($menu);
		
		foreach($menuOut as $value)
		{
			$menuNomCourt[]=$value['shorttitle'];
			$menuNomLong[]=$value['title'];
		}
		
		//bof Texte
			$textOut = Text::getText($page);
			$text = $textOut['text'];
			$title .= ' :: '.$textOut['title'];
			unset($textOut);
			
			$gallery = new Gallery($page);
			$text.=$gallery->getHtml(4);
			unset($gallery);
		//eof Texte
		
		require('func/navigation.php');
	//eof Génération du texte dont menu

	//bof Fin de page
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