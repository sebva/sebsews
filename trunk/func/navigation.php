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
$navigation = '<a href="http://'.$domaine.$repertoire.'/">'.$nom.'</a> &raquo; ';
if(isset($news)){
	$navigation .= '<a href="http://'.$domaine.$repertoire;
	$navigation .= ($rewrite=='on') ? '/news.html' :'/?page=news';
	$navigation .= '">News</a> &raquo; <a href="http://'.$domaine.$repertoire;
	$navigation .= ($rewrite=='on') ? '/news/' : '/?page=news&amp;categorie=';
	$navigation .= $categorieshort.'">'.$categorie.'</a> &raquo; <a href="http://'.$domaine.$repertoire; 
	$navigation .= ($rewrite=='on') ?'/news/':'/?page=news&amp;news=';
	$navigation .= $news.'">'.preg_replace('#^News - .* - (.*)$#',"$1",$title).'</a>';
} else if(isset($categorie)){
	$navigation .='<a href="http://'.$domaine.$repertoire;
	$navigation .= ($rewrite=='on') ?'/news.html':'/?page=news';
	$navigation .='">News</a> &raquo; <a href="http://'.$domaine.$repertoire;
	$navigation .= ($rewrite=='on') ?'/news/':'/?page=news&amp;categorie=';
	$navigation .= $categorieshort.'">'.$categorie.'</a>';
}else{
	$navigation .= '<a href="http://'.$domaine.$repertoire.'/';
	$navigation .= ($rewrite=='on') ? '' : '?page=' ;
	$navigation .= $page;
	$navigation .= ($rewrite=='on') ?'.html':'';
	$navigation .='">'.$title.'</a>'; }
$navigation=stripslashes($navigation);
?>