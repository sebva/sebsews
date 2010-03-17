<?php
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