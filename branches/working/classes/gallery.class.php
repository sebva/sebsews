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
 * Class for image gallery gestion
 * @author seb.vaucher
 */
class Gallery
{
	private $images=array();
	private $cache;
	
	public function __construct($page)
	{
		$this->cache = new Cache($page.'.images', Cache::NORMAL);
		
		if($this->cache->isExpired())
		{
			$db = new DB();
			$reponse = $db->query('SELECT images FROM '.$db->dbConfig['tables']['pages'].' WHERE shorttitle="'.$page.'"');
			if(!empty($reponse[0]['images']))
			{
				$this->images=explode('$^', preg_replace('#^\^(.*)\$$#', "$1", $reponse[0]['images']));
				$this->cache->setCache(serialize($this->images));
			}
		}
		else
		{
			$this->images = unserialize($this->cache->getCache());
		}
	}
	
	/**
	 * Returns the images array
	 * @return array The images array
	 */
	public function getArray()
	{
		return $this->images;
	}
	
	/**
	 * Returns an HTML table
	 * @param $nbColumns The number of columns
	 */
	public function getHtml($nbColumns)
	{
		$text = '<table border="0" style="margin:auto;"><caption><span style="font-size:1.65em">Galerie d\'images :</span></caption><tbody><tr>';
		if(empty($this->images))
			$text.='<td>&nbsp;</td>';
		else
		{
			$iCompt=0;
			foreach($this->images as $element)
			{
				if($iCompt>=$nbColumns){
					$text.='</tr><tr>';
					$iCompt=0;
				}
					$text.='<td><a href="upload/'.$element.'" rel="lightbox[galerie]"><img src="upload/thumbs/thumb_'.(preg_replace('#^(.+)\.[a-zA-Z]{3}$#', "$1", $element)).'.jpg" alt="image" /></a></td>';
					$iCompt++;
			}
		}
		$text.='</tr></tbody></table>';
		return $text;
	}
	
}
?>