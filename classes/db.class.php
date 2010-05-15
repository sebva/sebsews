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
 * Manages DB connection and queries
 * @author seb.vaucher
 *
 */
class DB
{
	/**
	 * Contains DB config
	 * It's possible to access DB config from that
	 * @var array
	 */
	public $dbConfig = array();
	
	public function __construct()
	{
		$config = new Config();
		$this->dbConfig = $config->getDbConfig();
		$this->connect();
	}
	
	public function __destruct()
	{
		mysql_close();
	}
	
	/**
	 * Connects to the database
	 */
	private function connect()
	{
		mysql_connect($this->dbConfig['host'], $this->dbConfig['user'], $this->dbConfig['passwd']) or die(mysql_error());
		mysql_set_charset ('UTF8');
		mysql_select_db($this->dbConfig['db']);
	}
	
	/**
	 * Query the database
	 * @param string $sql An SQL request
	 * @return array The result of the query
	 */
	public function query($sql)
	{
		$reponse = mysql_query($sql) or die(mysql_error());
		$return = array();
		while ($donnees = mysql_fetch_array($reponse) )
		{
			$return[] = $donnees;
		}
		return $return;
	}
}