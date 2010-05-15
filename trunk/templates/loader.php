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
if(!isset($_GET['css'])){
	require_once('litetemplate.class.php');
	$newtpl = new liteTemplate();
	/*$newtpl -> cache_activate = true;
	$newtpl -> cache_compression = true;*/
	$newtpl -> file('templates/'.$template.'/base.tpl');
	$newtpl -> assignTag('menu','1',array('nomCourt'=>$menuNomCourt,'nomLong'=>$menuNomLong));
	$newtpl -> assign(array(
		'titre'=>$title,
		'nomsite'=>$nom,
		'texte'=>$text,
		'chemin'=>'http://'.$domaine.$repertoire.'/',
		'chemintpl'=>'http://'.$domaine.$repertoire.'/templates/'.$template.'/',
		'cheminimages'=>'http://'.$domaine.$repertoire.'/templates/'.$template.'/images/',
		'navigation'=>$navigation));
	$newtpl -> view();
}
else if($_GET['css']=='lightbox'){
	header('Content-Type:text/css');
	require_once('litetemplate.class.php');	
	$newtpl = new liteTemplate();
	$newtpl -> cache_activate = true;
	$newtpl -> cache_compression = true;
	require('../config.php');
	$newtpl -> file('../templates/lightbox/css/lightbox.css');
	$newtpl -> assign(array('cheminimages'=>'http://'.$domaine.$repertoire.'/templates/lightbox/images/'));
	$newtpl -> view();
}
else{
	header('Content-Type:text/css');
	require_once('litetemplate.class.php');	
	$newtpl = new liteTemplate();
	$newtpl -> cache_activate = true;
	$newtpl -> cache_compression = true;
	require('../config.php');
	$newtpl -> file('../templates/'.$template.'/style.css');
	$newtpl -> assign(array(
		'chemin'=>'http://'.$domaine.$repertoire.'/',
		'chemintpl'=>'http://'.$domaine.$repertoire.'/templates/'.$template.'/',
		'cheminimages'=>'http://'.$domaine.$repertoire.'/templates/'.$template.'/images/'));
	$newtpl -> view();
}
foreach($newtpl -> error as $cle=>$valeur)
    {
    echo $cle.' : '.$valeur.'<br>';
    } 
?>