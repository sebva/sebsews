<?php
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
		require('cache.class.php');
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
			require('db.class.php');
			$db = new DB();
			$reponse = $db->query('SELECT title,shorttitle FROM '.$db->dbConfig['tables']['pages'].' ORDER BY id');
			unset($db);
			$this->cache->setCache(serialize($reponse));
			return $reponse;
		}
	}
}
?>