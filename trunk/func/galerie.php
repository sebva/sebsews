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
	if($images != '')
	{
		$images=preg_replace('#^\^(.*)\$$#', "$1", $images);
		$imagesArray = explode('$^', $images);
		$text .= '<table border="0" style="margin:auto;"><caption><span style="font-size:1.65em">Galerie d\'images :</span></caption><tbody><tr>';
		$iCompt=0;
		foreach($imagesArray as $element)
		{
			if($iCompt==3){
				$text.='</tr><tr>';
				$iCompt=0;
			} 		
				$text.='<td><a href="http://'.$domaine.$repertoire.'/upload/'.$element.'" rel="lightbox[galerie]"><img src="http://'.$domaine.$repertoire.'/upload/thumbs/thumb_'.(preg_replace('#^(.+)\.[a-zA-Z]{3}$#', "$1", $element)).'.jpg" alt="image" /></a></td>';
				$iCompt++;		
		}
		$text.='</tr></tbody></table>';
	}
?>