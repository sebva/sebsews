<?php
require('func/cache.php');
echo Cache('getRSS()', 60);

function getRSS()
{
	require_once('config.php');
	header('Content-Type: application/rss+xml; charset=utf-8');
	$return.='<?xml version="1.0" encoding="utf-8"?>'."\n";
	$return.= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
		<channel>
			<title><![CDATA['.$nom.' :: News]]></title>
			<description><![CDATA[News de '.$nom.']]></description>
			<language>fr-ch</language>
			<lastBuildDate>'.date(r).'</lastBuildDate>
			<link>http://'.$domaine.$repertoire;
			$return.= ($rewrite=='on') ?'/news.html':'/?page=news';
			$return.='</link>
			<atom:link href="http://'.$domaine.$_SERVER['REQUEST_URI'].'" rel="self" type="application/rss+xml" />
			<image>
				<title><![CDATA['.$nom.' :: News]]></title>
				<url>http://'.$domaine.$repertoire.'/rsslogo.png</url>
				<link>http://'.$domaine.$repertoire;
				$return.= ($rewrite=='on') ?'/news.html':'/?page=news';
				$return.='</link>
				<width>144</width>
				<height>53</height>
			</image>';

			mysql_connect($mysqlHost, $mysqlUser, $mysqlPassword);
			mysql_set_charset ('UTF8');
			mysql_select_db($mysqlDb);
			$reponse = mysql_query('SELECT * FROM '.$mysqlTableNews.' ORDER BY date desc');
			while ($donnees = mysql_fetch_array($reponse) )
			{
				$timestamp = strtotime($donnees['date']);
				$text= "\n".'<item>
						<title><![CDATA['.$donnees['title'].']]></title>
						<link>http://'.$domaine.$repertoire;$text.= ($rewrite=='on') ?'/news/':'/?page=news&amp;news=';$text.= $donnees['id'].'</link>
						<guid>http://'.$domaine.$repertoire;$text.= ($rewrite=='on') ?'/news/':'/?page=news&amp;news=';$text.= $donnees['id'].'</guid>
						<description><![CDATA['.str_replace('$repertoire', $repertoire, str_replace('$domaine', $domaine, $donnees['text'])).']]></description>
						<pubDate>'.date(r, $timestamp).'</pubDate>
						<author>'.strtolower(str_replace(' ', '.' ,$donnees['auteur'])).'@'.$emailDomaine.' ('.$donnees['auteur'].')</author>
						<category>'.$donnees['categorie'].'</category>
					</item>';
				$return.=stripslashes($text);
			}
		$return.='</channel>
	</rss>';
	return $return;
}