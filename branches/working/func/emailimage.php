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

header ("Content-type: image/png");
require_once('../config.php');
$image = imagecreate(300,16);

$blanc = imagecolorallocate($image, 255, 255, 255);
$noir = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 4, 0, -1, $_GET['text'].'@'.$emailDomaine, $noir);
imagecolortransparent($image, $blanc);

imagepng($image);
?>
