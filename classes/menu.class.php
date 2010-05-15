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
 * This class manages the menu
 * @author seb.vaucher
 *
 */
class Menu
{
	private $cache;
	public function __construct()
	{
		$this->cache = new Cache('menu', Cache::LONG);
	}
	
	/**
	 * Get the generated menu as an array
	 * @return array An array
	 */
	public function getMenu()
	{
		if(!$this->cache->isExpired())
		{
			$cached = $this->cache->getCache();
			return unserialize($cached);
		}
		else
		{
			$db = new DB();
			$reponse = $db->query('SELECT title,shorttitle FROM '.$db->dbConfig['tables']['pages'].' ORDER BY id');
			unset($db);
			$this->cache->setCache(serialize($reponse));
			return $reponse;
		}
	}
}
?>