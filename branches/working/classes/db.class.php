<?php
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