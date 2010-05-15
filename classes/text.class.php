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
 * Manages the pages text
 * @author seb.vaucher
 *
 */
class Text
{
	/**
	 * Return the text of the page $page
	 * @return string The text
	 * @param string $page
	 */
	public static function getText($page)
	{
		if(file_exists('pages/'.$page.'.php'))
			require('pages/'.$page.'.php');
		else
		{
			$cache = new Cache($page.'.text', Cache::NORMAL);
			
			if($cache->isExpired())
			{
				$db = new DB();
				$reponse = $db->query('SELECT text,title FROM '.$db->dbConfig['tables']['pages'].' WHERE shorttitle = \''.$page.'\'');
				
				$return['text'] = $reponse[0]['text'];
				$return['title'] = $reponse[0]['title'];
				$cache->setCache(serialize($return));
				return $return;
			}
			else
				return unserialize($cache->getCache());
		}
	}
}
?>