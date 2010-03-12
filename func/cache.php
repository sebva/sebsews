<?php
function Cache($fonction, $duree = 120)
{
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