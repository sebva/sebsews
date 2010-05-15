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
 * Manages the configuration (read-write)
 * @author seb.vaucher
 *
 */
class Config
{
	/**
	 * Contains the whole configuration, please do not access the config through this !
	 * @var array
	 */
	public $config = array();
	
	public function __construct()
	{
		$this->getConfigPhp();
	}
	
	/**
	 * Return an array containing the database config
	 * <i>array(host, user, passwd, db, tables(array(pages, news)))</i>
	 * @return array DbConfig
	 */
	public function getDbConfig()
	{
		return array(
			'host' => $this->config['mysqlHost'],
			'user' => $this->config['mysqlUser'],
			'passwd' => $this->config['mysqlPassword'],
			'db' => $this->config['mysqlDb'],
			'tables' => array(
				'pages' => $this->config['mysqlTablePages'],
				'news' => $this->config['mysqlTableNews'])
			);
	}
	
	/**
	 * Reads config.php and fetches it
	 * ! config.php use will be depracated !
	 * @deprecated
	 * @todo Deprecate config.php
	 */
	private function getConfigPhp()
	{
		$flux = fopen('config.php', 'r');
		while(!feof($flux))
		{
			$line = trim(fgets($flux));
			if($line[0]=='$')
			{
				$temp = explode('=', $line);
				$paramName = preg_replace('#^\$(.+)$#', "$1", trim($temp[0]));
				$paramValue = preg_replace('#^\'(.*)\'.*$#', "$1", trim($temp[1]));
				unset($temp);
				$this->config[$paramName]=$paramValue;
			}
		}
		fclose($flux);
	}
	
	/**
	 * Reads config.json and fetches it
	 * config.json will be the config file for next release
	 * @todo Use config.json instead of config.php
	 */
	private function getConfigJson()
	{
		
	}
}