<?php
/**
 * Manages the configuration (read-write)
 * @author seb.vaucher
 *
 */
class Config
{
	public $config = array();
	
	public function __construct()
	{
		getConfigPhp();
	}
	
	/**
	 * Reads config.php and fetches it
	 * ! config.php use will be depracated !
	 * @deprecated
	 */
	private function getConfigPhp()
	{
		$flux = fopen('config.php', 'r');
		while(!feof($flux))
		{
			$line = trim(fgets($flux));
			if($line[0]=='$')
			{
				$paramName = preg_replace('#^\$([a-z_]+)#', "$1", $line);
				$paramValue = preg_replace('#\'(.*)\'.*(//.*)?$#', "$1", $line);
				$this->config[$paramName]=$paramValue;
			}
		}
		fclose($flux);
	}
}