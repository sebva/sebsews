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

/*
 * Users file format :
 *  MUST be in comma-separated CSV format
 *  
 *  Columns :
 *  1 username, 2 password hashed in SHA-1, 3 user rank from the constants list in code below
 */

/**
 * This class is for credentials management
 * It performs its job on a CSV file
 * @author Sébastien Vaucher
 *
 */
class Credentials
{
	/**
	 * @var string The path of the file in use.
	 */
	private $file;
	/**
	 * @var array The array containing users info.
	 */
	private $users = array();
	/**
	 * @var ressource A fopen link.
	 */
	private $fileFeed;
	private $sessionIndex;
	
	const RANK_SUPERADMIN = 0;
	const RANK_ADMIN = 1;
	const RANK_USER = 10;
	const RANK_GUEST = 100;
	
	const PATH_TO_FILE_FOLDER = 'config/';
	const DEFAULT_FILE_NAME = 'creds.csv';
	const DEFAULT_SESSION_INDEX = 'login';
	
	/**
	 * 
	 * @param string $file The path of the file being used
	 */
	public function __construct($file = self::DEFAULT_FILE_NAME, $sessionIndex = self::DEFAULT_SESSION_INDEX)
	{
		$this->sessionIndex = $sessionIndex;
		$this->file = self::PATH_TO_FILE_FOLDER . $file;
		
		if(!file_exists($this->file))
			$this->createDefaultFile();
		
		$this->fileFeed = fopen($this->file, 'r', true) or die("Error : The file $file cannot be open !");
		$this->readFile();
	}
	
	public function __destruct()
	{
		$this->writeToFile();
		fclose($this->fileFeed);
	}
	
	private function readFile()
	{
		rewind($this->fileFeed);
		$this->users = array();
		while(!feof($this->fileFeed))
		{
			$line = fgetcsv($this->fileFeed, 0, ',');
			if(!empty($line[0]))
				$this->users[] = $line;
		}
	}
	
	private function createDefaultFile()
	{
		$this->users = array('admin', 'admin', 1);
		@mkdir(self::PATH_TO_FILE_FOLDER, 777);
		
		touch($this->file);
		chmod($this->file, 777);
		$this->writeToFile();
	}
	
	private function userExists($user)
	{
		foreach($this->users as $userEntry)
		{
			if($userEntry[0] == $user)
				return true;
		}
		return false;
	}
	
	public function checkLogin($user, $pass, $setSessionOrNot = false)
	{
		foreach($this->users as $userEntry)
		{
			if($userEntry[0] == $user && $userEntry[1] == sha1($pass))
			{
				if($setSessionOrNot)
					$this->setSession($userEntry[0]);
				return $userEntry[3];
			}
		}
		if($setSessionOrNot)
			$this->setSession('guest');
		return RANK_GUEST;
	}
	
	public function addUser($user, $pass, $rank)
	{
		if($this->userExists($user))
			return false;
		
		$this->users[] = array($user, $pass, $rank);	
		
		fseek($this->fileFeed, 0, SEEK_END);
		fwrite($this->fileFeed, "\n");
		fputcsv($this->fileFeed, array($user, $pass, $rank), ',');
		
	}
	
	public function delUser($user)
	{
		$hasBeenDone = false;
		foreach ($this->users as $key => $value)
		{
			if($value[0] == $user)
			{
				unset($this->users[$key]);
				$hasBeenDone = true;
			}
		}
		return $hasBeenDone;
	}
	
	private function writeToFile()
	{
		$this->changeFileMode('w');
		foreach($this->users as $userEntry)
		{
			fputcsv($this->fileFeed, $userEntry, ',');
		}
		$this->changeFileMode('r+');
	}
	
	private function changeFileMode($mode = 'r+')
	{
		if(is_resource($this->fileFeed))
			fclose($this->fileFeed);
		$this->fileFeed = fopen($this->file, $mode, true) or die("Error : The file $this->file cannot be open with mode $mode !");
	}
	
	private function setSession($user)
	{
		if($user == 'guest')
		{
			$_SESSION[$this->sessionIndex]['username'] = $user;
			$_SESSION[$this->sessionIndex]['rank'] = RANK_GUEST;
			return true;
		}
		foreach($this->users as $userEntry)
		{
			if($userEntry[0] == $user)
			{
				$_SESSION[$this->sessionIndex]['username'] = $userEntry[0];
				$_SESSION[$this->sessionIndex]['rank'] = $userEntry[2];
				return true;
			}
		}
		return false;
	}
	
	public function delSession()
	{
		unset($_SESSION[$this->sessionIndex]);
	}
}
?>