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
 * This class is for cache management
 *
 * @author seb.vaucher
 */
class Cache
{
	const SHORT = 45;
	const NORMAL = 120;
	const LONG = 600;
	
	private $file;
	private $duration = NORMAL;
	
	
	public function __construct($file, $duration)
	{
		$this->file = "_cache/$file.cache";
		$this->duration = $duration;
	}
	
	/**
	 * Gets the cache age
	 * @return int The cached file age
	 */
	private function getAge()
	{
		return time() - filemtime($this->file);
	}
	
	/**
	 * Check if the cache is outdated
	 * @return bool True if it worked, otherwise false
	 */
	public function isExpired()
	{
		if(!file_exists($this->file)) return true;
		if($this->getAge() > $this->duration)
			return true;
		else
			return false;
	}
	
	/**
	 * Writes $text into the cached file
	 * @param string $text
	 * @return bool True if it worked, otherwise false
	 */
	public function setCache($text)
	{
		@mkdir('_cache');
	
		return(file_put_contents($this->file, $text));
	}
	
	/**
	 * Get the cached file
	 * @return mixed The cached file, or false if an error occured
	 */
	public function getCache()
	{
		if(file_exists($this->file))
			return file_get_contents($this->file);
		else
			return false;
	}
	
	/**
	 * Deletes the cached file
	 * @return bool True if it worked, otherwise false
	 */
	public function delCache()
	{
		return(@unlink($this->file));
	}
}
?>