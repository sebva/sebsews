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
function Cache($fonction, $duree = 120)
{
	@mkdir('_cache');
	$file = '_cache/'.$fonction.'.cache.html';

	if(!file_exists($file))
	{
		eval('$text = '.$fonction.';');
		file_put_contents($file, $text);
	}
	else
	{
		if(time() - filemtime($file) > $duree)
		{
			eval('$text = '.$fonction.';');
			file_put_contents($file, $text);
		}
		else
			$text = file_get_contents($file);
	}
	
	return $text;
}
?>