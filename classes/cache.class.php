<?php
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
		$this->file = "../_cache/$file.cache.html";
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
		@mkdir('../_cache');
	
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