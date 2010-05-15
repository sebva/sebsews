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