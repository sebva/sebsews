<?php


/**
 * Classe LiteTemplate
 * Author : telnes
 * Version : V.1.9
 * Date : 23/03/2007
 * LastRelease : 15/07/2007
 * Contribution : ZBrian, Xhark
 *
 */
class LiteTemplate{



/***************************************************************************/
/*                                     Propriétés                          */
/***************************************************************************/


/**
 * nom de la page tpl
 *
 * @var unknown_type
 */
var $tplName;
/**
 * code template
 *
 * @var unknown_type
 */
var $tpl;

/**
 * temps d'éxécution du code
 *
 * @var unknown_type
 */
var $time;

/**
 * répertoire cache par défaut
 *
 * @var unknown_type
 */
var $cache_folder;

/**
 * durée de vie d'un fichier cache
 *
 * @var unknown_type
 */
var $cache_life;

/**
 * active la gestion de cache
 *
 * @var bolean
 */
var $cache_activate;

/**
 * active la compression des fichiers de cache
 *
 * @var bolean
 */
var $cache_compression;

/**
 * privat
 *
 * @var bolean
 */
var $cache_isExpired;
/**
 * active/désactive le debug
 *
 * @var bolean
 */
var $debug;

/**
 * tableau contenant les informations de debugage
 *
 * @var unknown_type
 */
var $error;

/**
 * version
 *
 * @var unknown_type
 */
var $version;


/***************************************************************************/
/*                                      Méthodes                           */
/***************************************************************************/


/**
 * Constructeur
 *
 * @return void
 */
function LiteTemplate(){

	$this->version = '1.9';
    $this->tpl = '';
    $this->tplName = '';
    $this->time = microtime();
    // cache
    $this->cache_folder = '_cache/';
    $this->cache_life = '60'; // 86400 == 1 journée ; 604800 == 1 semaine ; 1814400 == 1 mois
    $this->cache_activate = false; 
    $this->cache_compression = false;
    //debug
    $this->debug = false;
    //privat
    $this->cache_isExpired = true;
    $this->error = array();
}


/**
 * stipule le fichier template à utiliser
 *
 * @param unknown_type $file
 * @return void
 */
function file($file){

    $this->tplName = $file;

    if($this->isExpiredCache()){

        $this->cache_isExpired = true;

        if (!$this->tpl = file_get_contents($file)){

            $this->error[] = 'Warning! problème lors de la récupération du fichier '.$file;
        }
    }
    else{
        $this->cache_isExpired = false;

    }
}



/**
 *Permet de passer un tableau array( key=>valeur,...) ou les clées correspondes aux balises dans le fichier tamplate qui serront remplacer par les valeures.
 *
 * @param array $tag_array
 * @return void
 */
function assign($tag_array){

    if(!$this->cache_activate or $this->cache_isExpired){
        foreach( $tag_array as $key => $value){

            $this->tpl = str_replace('{$'.$key.'}',str_replace('$','&#36;',$value),$this->tpl);
        }
    }
}


/**
 * Permet de réaliser des boucles les valeur (tableau) entre les balises. exemple {LOOP id=1}{$NOM}:{$PRENOM}{/LOOP}.
 *
 * @param unknown_type $tag
 * @param unknown_type $id
 * @param unknown_type $tag_array
 * @return void
 */
function assignTag($tag,$id,$tag_array){

    if(!$this->cache_activate or $this->cache_isExpired){
        if( $this->checkArray($tag_array) ){

            reset($tag_array);
            $num_key = count($tag_array); //nbre de key
            $num_value = count($tag_array[key($tag_array)]); //nbre de value
            $tmp = $this->findTag($tag,$id); //la chaine entre les balises

            for($i=0;$i<$num_value;$i++){

                $array[$i] = $tmp;
                reset($tag_array);
                for( $j=0;$j<$num_key;$j++){

                    $array[$i] = str_replace('{$'.key($tag_array).'}',str_replace('$','&#36;',$tag_array[key($tag_array)][$i]),$array[$i]);
    				next($tag_array);
                }
            }

            //on concatène les arrays
            for ($i=1;$i<count($array);$i++){

                $array[0] .= $array[$i];
            }

            $replace = '{'.$tag.' id='.$id.'}'.$tmp.'{/'.$tag.'}';
            $this->tpl = str_replace($replace,$array[0],$this->tpl);
        }
        else{

            $this->error[]="Warning! erreur dans la taille des tableaux de la balise $tag id=$id";
        }
    }
}



/**
 * Permet d'inclure un fichier html,php.
 *
 * @param unknown_type $id
 * @param unknown_type $file
 * @return void
 */
function assignInclude($id,$file=""){

    if(!$this->cache_activate or $this->cache_isExpired){
        if( empty($file) ){

            $filename = $this->findTag("INCLUDE",$id,"FILE") or exit("erreur sur la balise $id");

            $tmp = $this->getIncludeContents($filename);
            $this->tpl = str_replace("{INCLUDE id=$id file=$filename}",$tmp,$this->tpl);
        }
        else{

            $tmp = $this->getIncludeContents($file);
            $this->tpl = str_replace("{INCLUDE id=$id}",$tmp,$this->tpl);
        }
    }
}



/**
 * Permet d'inclure une liste déroulante.
 *
 * @param unknown_type $name
 * @param unknown_type $array
 * @param unknown_type $selected
 * @param unknown_type $htmlAttribut
 */
function htmlSelect($name,$array,$selected="",$htmlAttribut=""){

    if(!$this->cache_activate or $this->cache_isExpired){
    	//on test la balise avec l'option selected
        $select = $this->findTag("HTMLSELECT",$name,"SELECTED");

        if(!$select){
            //si on l'a pas trouvé on cherche sans l'option	selected
            if($this->findTag("HTMLSELECT",$name)){

    			$tmp = $this->creatHtmlSelect($name,$array,$selected,$htmlAttribut);
    			$this->tpl = str_replace("{HTMLSELECT id=$name}",$tmp,$this->tpl);
            }
            else{

            	$this->error[] = "Warning : Impossible de trouver HTMLSELEC $name";
            }

        }
        else{
    		$tmp = $this->creatHtmlSelect($name,$array,$select,$htmlAttribut);
        	$this->tpl = str_replace("{HTMLSELECT id=$name selected=$select}",$tmp,$this->tpl);
        }
    }
}



/**
 * Permet d'afficher le fichier apres les modifications éffectuer.
 *
 * @return le telmplate avec les modifications
 */
function view(){

    if(!$this->cache_activate or $this->cache_isExpired){
       	$this->assignAutoInclude();
       	if(!$this->debug){ $this->clearTag(); }
    }
    if(!$this->cache_activate){
        echo $this->tpl;
    }
    elseif($this->cache_isExpired){
        $this->putCache($this->returnTpl());
        echo $this->tpl;
    }
    else{
        echo $this->getCache();
    }
    $this->time = $this->microTimeDiff($this->time,microtime());
}


/**
 * permet de récupérer le contenus de la variable tpl.
 *
 * @return tpl variable
 */
function returnTpl(){

    return $this->tpl;
}


//expérimental - en phase de test !
/**
 * Permet d'inclure des fonctions contenues dans le dossier addon, par defaut. Méthode Expérimental
 *
 * @param unknown_type $dir
 */
function addOn($dir = 'addon/'){

    if (is_dir($dir)) {

        if ($dh = opendir($dir)) {

            while (($file = readdir($dh)) !== false) {

                if($file != "." && $file != ".."){

                    include_once $dir.$file;
                }
            }
            closedir($dh);
        }
        else{

            $this->error[] = 'Warning! le repertoire "'.$dir.'" n\'a pas réussit à être ouvert';
        }
    }
    else{
        $this->error[] = 'Warning! le repertoire "'.$dir.'" est introuvable';
    }

}


/**
 * permet de récupérer un fichier en cache. Retourne false en cas de timelife dépassé ou si le fichier cache n éxiste pas
 *
 * @param unknown_type $filename
 * @return le contenus du template ou false en cas d'échec
 */
function getCache($filename=''){

    $filename = (empty($filename))?$this->tplName:$filename;

    $filename_md5 = md5($filename);
    $path_file = $this->cache_folder.$filename_md5;

    //on test si le fichier existe dans le cache
    if(file_exists($path_file)){

        if(!$this->cache_compression){
               
                $handle = fopen ($path_file, "rb");
                $contents = fread ($handle, filesize ($path_file)+1);
                fclose ($handle);
        }
        else{
                
                $contents =  $this->getGzFile($path_file);
        }

        return $contents;

    }
    else{

        //pas de fichier en cache
        return false;
    }

}


/**
 * permet de mettre un fichier en cache.
 *
 * @param unknown_type $filename
 * @param unknown_type $contents
 */
function putCache($contents,$filename=''){

    $filename = (empty($filename))?$this->tplName:$filename;

    if(!is_dir($this->cache_folder)) {

		if(!mkdir($this->cache_folder, 0755)){

          $this->error[] = 'Warning! le repertoire de cache"'.$this->cache_folder.'" n\'a pu être crée';
        }
	} // by Xhark

    $filename_md5 = md5($filename);
    $path_file = $this->cache_folder.$filename_md5;

    if(!$this->cache_compression){

        $handle = fopen ($path_file, "w");

        if (fwrite($handle, $contents) === FALSE) {

            $this->error[] = "Warning :fwrite Impossible d'ecrire dans le fichier $path_file";
        }

        fclose ($handle);
    }
    else{

        $handle = gzopen ($path_file, "w");

        if (gzputs($handle, $contents) === FALSE) {

            $this->error[] = "Warning :gzputs Impossible d'écrire dans le fichier ".$path;
        }

        gzclose ($handle);
    }

}


/*
#FUNCTION#
string version() : .;;
#/FUNCTION#
*/

/**
 * Retourne les informations sur liteTemplate.
 * 
 * @param bolean $value
 * @return string/array
 */
function version($value="0"){
	
	if($value){
		return array('autor'=>'telnes',
					 'version'=>$this->version, 
					);
	}
	else{
    	return 'Page généré avec LiteTemplate'.$this->version.', un moteur de template - création telnes';
	}
}


/**
 * Retourne les messages d'erreur.
 *
 * @return array
 */
function getError(){

    return $this->error;
}


/***************************************************************************/
/*                                      Privat function                    */
/***************************************************************************/


/*
#FUNCTION#
file getInclude_contents( file ) : Permet de récupérer la sortie d'un fichier .;
viens du site http://www.nexen.net;;
#/FUNCTION#
*/

function getIncludeContents($filename) {
/*
*  fonction http://www.nexen.net/docs/php/annotee/function.include.php?lien=include
*  merci nexen
*/

    if (is_file($filename)) {

        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    else{

        $this->error[]="Warning! pas de fichier selectionné : $filename";
    }
}


//

function getGzFile($filename){

    if (is_file($filename)) {

        ob_start();
        readgzfile($filename);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    else{

        $this->error[]="Warning! pas de fichier selectionné : $filename";
    }
}

/*
#FUNCTION#
<b>PRIVAT</b> array findTag( string , int) : permet de récupérer une zone de txt entre 2 balises { BALISE id=1 } chaine de caractère { /BALISE }.;;
#/FUNCTION#
*/

function findTag($tag,$id,$option=""){

    //option peut etre égale a FILE

    if( empty($option) ){

        //retourne la chaine si il la trouvé et rien sinon
        @preg_match("/(\{".$tag." id=)(".$id.")(})(.*?)(\{\/".$tag."})/ism",$this->tpl,$result);
	    //return $result[4];
	        if(empty($result[4])){
	        	
	        	preg_match("/\{".$tag." id=(".$id.")}/ism",$this->tpl,$result);
	        	return $result[1];
	        	
	        }
	       return $result[4];

    }
    elseif($option == "FILE"){

        //retourne le nom du fichier si il la trouvé et rien sinon
        @preg_match("/\{".$tag." id=".$id." file=(.*?)}/ism",$this->tpl,$result);
        return $result[1];
    }
    elseif($option == "SELECTED"){

        //retourne le nom selected si il la trouvé et rien sinon
        @preg_match("/\{".$tag." id=".$id." selected=(.*?)}/ism",$this->tpl,$result);
        return $result[1];
    }
    else{

        return 0;
    }
}


/*
#FUNCTION#
<b>PRIVAT</b>. bolean checkArray(type array) : Test si il y a le meme nombre de d'élément dans les sous tableau array(key=>array(),key=>array...);;
#/FUNCTION#
*/

function checkArray($array){

    reset($array);
    $return = true;
    $num = count($array[key($array)]);

    for ($i = 0; $i < count($array); $i++) {

        if($num != count($array[key($array)])){

            $return=false;
        }

        next($array);
    }

  return $return;
}

/*
#FUNCTION#
<b>PRIVAT</b>. string clearTag(none) : supprime les balises templates non utiliser dans le template!;;
#/FUNCTION#
*/
function clearTag(){

    $tag = '[a-zA-Z0-9_]{1,}';
    $id = '[a-zA-Z0-9_]{1,}';

    //supprime les balises simples
    $this->tpl = preg_replace('/\{\$'.$tag.'\}/i','',$this->tpl);
    //supprime les balises dynamiques
    $this->tpl = preg_replace('/(\{'.$tag.' id=)('.$id.')(})(.*?)(\{\/'.$tag.'})/ism','',$this->tpl);
    $this->tpl = preg_replace('/(\{'.$tag.' id=)('.$id.')(})/ism','',$this->tpl);
    $this->tpl = preg_replace('/(\{'.$tag.' id=)('.$id.') (file=(.*?)})/ism','',$this->tpl);
}

/*
#FUNCTION#
<b>PRIVAT</b>. string microTimeDiff( microtime,microtime) : fait la différence entre deux temps de type microtime;;
#/FUNCTION#
*/
function microTimeDiff($time_begin,$time_end){
//contribution ZBrian
  
    $a=explode(' ',$time_begin);
	$b=explode(' ',$time_end);
	
	return $b[0]-$a[0]+$b[1]-$a[1];
}


/*
#FUNCTION#
<b>PRIVAT</b>. string creatHtmlSelect( string , array , string) : construit un html select en fonction du selected;;
#/FUNCTION#
*/
function creatHtmlSelect($name,$array,$selected,$attribut){
	
		$tmp = '<Select name="'.$name.'" '.$attribut.' >'."\n";
        
        foreach($array as $key=>$value){
        	
        	if( $key == $selected){
        		$tmp .= '<option value="'.$key.'" SELECTED >'.$value.'</option>'."\n";
			}
			else{
				$tmp .= '<option value="'.$key.'">'.$value.'</option>'."\n";	
			}	
        }
        $tmp .= '</select>';
		return $tmp;	
}

/*
#FUNCTION#
<b>Privat</b>. none assignAutoInclude(none) : parse automatique des {INCLUDE file=filename.php}
#/FUNCTION#
*/
function assignAutoInclude(){
	
	
	$patern = "@\{INCLUDE file=(.*)\}@";
	preg_match_all($patern,$this->tpl,$tmp);
	
	foreach($tmp[1] as $file){

        $tmp = $this->getIncludeContents($file);
        $this->tpl = str_replace("{INCLUDE file=$file}",$tmp,$this->tpl);
		
	}
	
}

//privat
// by Xhark
function isExpiredCache($filename=''){

    $filename = (empty($filename))?$this->tplName:$filename;
    
	$filename_md5 = md5($filename);
    $path_file = $this->cache_folder.$filename_md5;

    //on test si le fichier existe dans le cache
    if(file_exists($path_file)){
        clearstatcache();
       	$diff = time() - filemtime($path_file);

        // si la durée de vie du fichier cache est bonne (il n'est donc pas expiré) on renvoi false, le cache n'est pas expiré
        if( $diff < $this->cache_life)
			return false;
        else{
            //le temps de vie du fichier cache est dépassé
            return true;
        }
    }

	else{
        //pas de fichier en cache = délai dépassé
        return true;
    }

}
}//fin class



?>
