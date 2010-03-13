<?php
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
}