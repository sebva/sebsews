<?php
/**
 * Class for image gallery gestion
 * @author seb.vaucher
 */
class Gallery
{
	private $images=array();
	private $cache;
	
	public function __construct($page)
	{
		$this->cache = new Cache($page.'.images', Cache::NORMAL);
		
		if($this->cache->isExpired())
		{
			$db = new DB();
			$reponse = $db->query('SELECT images FROM '.$db->dbConfig['tables']['pages'].' WHERE shorttitle="'.$page.'"');
			if(!empty($reponse[0]['images']))
			{
				$this->images=explode('$^', preg_replace('#^\^(.*)\$$#', "$1", $reponse[0]['images']));
				$this->cache->setCache(serialize($this->images));
			}
		}
		else
		{
			$this->images = unserialize($this->cache->getCache());
		}
	}
	
	/**
	 * Returns the images array
	 * @return array The images array
	 */
	public function getArray()
	{
		return $this->images;
	}
	
	/**
	 * Returns an HTML table
	 * @param $nbColumns The number of columns
	 */
	public function getHtml($nbColumns)
	{
		$text = '<table border="0" style="margin:auto;"><caption><span style="font-size:1.65em">Galerie d\'images :</span></caption><tbody><tr>';
		if(empty($this->images))
			$text.='<td>&nbsp;</td>';
		else
		{
			$iCompt=0;
			foreach($this->images as $element)
			{
				if($iCompt>=$nbColumns){
					$text.='</tr><tr>';
					$iCompt=0;
				}
					$text.='<td><a href="upload/'.$element.'" rel="lightbox[galerie]"><img src="upload/thumbs/thumb_'.(preg_replace('#^(.+)\.[a-zA-Z]{3}$#', "$1", $element)).'.jpg" alt="image" /></a></td>';
					$iCompt++;
			}
		}
		$text.='</tr></tbody></table>';
		return $text;
	}
	
}
?>