<?php
class Text
{
	public static function getText($page)
	{
		require('cache.class.php');
		$cache = new Cache($page, Cache::NORMAL);
		
		if($cache->isExpired())
		{
			require('db.class.php');
			
			$db = new DB();
			$reponse = $db->query('SELECT text FROM '.$db->dbConfig['tables']['pages'].' WHERE shorttitle = \''.$page.'\'');
			
			$cache->setCache($reponse[0]['text']);
			return $reponse[0]['text'];
		}
		else
			return $cache->getCache();
	}
}
?>