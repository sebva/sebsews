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
require 'header.php';?>
	<ul>
		<li><a href="page.php">Gestion des pages</a></li>
		<li><a href="news.php">Gestion des news</a></li>
		<li><a href="settings.php">Préférences générales</a></li>
		<?php if ($googleAppsDomain!=''){?><li><a href="https://www.google.com/a/<?php echo $googleAppsDomain?>/ServiceLogin">Google Apps</a></li><?php }?>
	</ul>
<?php require 'footer.php'; ?>