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
if($page!='menu')
{
	$reponse = mysql_query('SELECT title,shorttitle FROM '.$mysqlTablePages.' ORDER BY id');
	$menuNomCourt=array();
	$menuNomLong=array();
	while ($donnees = mysql_fetch_array($reponse) )
	{
		$menuNomCourt[]=$donnees['shorttitle'];
		$menuNomLong[]=$donnees['title'];
		$iCompt++;
	}
}
else
	$erreur = 403;
?>