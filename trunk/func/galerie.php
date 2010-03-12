<?php
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