<?php
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