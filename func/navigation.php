<?php
$navigation = '<a href="http://'.$domaine.$repertoire.'/">'.$nom.'</a> &raquo; ';
				if(isset($news)){
					$navigation .= '<a href="http://'.$domaine.$repertoire;
					$navigation .= ($rewrite=='on') ? '/news.html' :'/?page=news';
					$navigation .= '">News</a> &raquo; <a href="http://'.$domaine.$repertoire;
					$navigation .= ($rewrite=='on') ? '/news/' : '/?page=news&amp;categorie=';
					$navigation .= strtolower($categorie).'">'.$categorie.'</a> &raquo; <a href="http://'.$domaine.$repertoire; 
					$navigation .= ($rewrite=='on') ?'/news/':'/?page=news&amp;news=';
					$navigation .= $news.'">'.preg_replace('#^.*<\!--Debut titre-->(.*)<\!--Fin titre-->$#',"$1",$title).'</a>';
				} else if(isset($categorie)){
					$navigation .='<a href="http://'.$domaine.$repertoire;
					$navigation .= ($rewrite=='on') ?'/news.html':'/?page=news';
					$navigation .='">News</a> &raquo; <a href="http://'.$domaine.$repertoire;
					$navigation .= ($rewrite=='on') ?'/news/':'/?page=news&amp;categorie=';
					$navigation .= strtolower($categorie).'">'.$categorie.'</a>';
				}else{
					$navigation .= '<a href="http://'.$domaine.$repertoire.'/';
					$navigation .= ($rewrite=='on') ? '' : '?page=' ;
					$navigation .= $page;
					$navigation .= ($rewrite=='on') ?'.html':'';
					$navigation .='">'.$title.'</a>'; }?>