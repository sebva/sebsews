<?php
require_once 'gaga.class.php';
/**
 * Classe Gagatranslate. Elle étend la classe Gagatemplate. Elle permet de faire des traductions simplement est rapidement à la façon de GetText.
 * @author Gaétan Collaud (gaga26@gmail.com)
 * @version 1.0
 */
class Gagatranslate extends Gagatemplate
{
	/// [public] dossier contenant les fichiers de langues
	protected $_langDir		= 'lang/';
	/// [public] liste des langues présentes sur le site
	protected $_listLang	= array('fr');
	/// [public] la langue de l'utilisateur actuel
	protected $_lang		= 'fr';
	/// [public] Force la regénération des fichiers de langues et des fichiers compilés. A utiliser pendant le développement.
	protected $_forceLang	= false;
	/// Informations concernant les fichiers xml et les fichier de langue compilés
	private $tInfos			= array();
	/// Informations de débugage
	private $tDebug			= array('xml' => array(), 'compile' => array());
	/// Commentaire à l'intention du traducteur
	private $comment		= '';

	/**
	 * Crée le parseur.
	 */
	protected function createParser(){
		$this->refParser = GGTParser::getInstance($this);
		$this->tInfosOpen();
	}

	/**
	 * Crée les fichiers xml associé à un fichier template
	 * @param string $f le fichier template
	 * @see $listLang
	 */
	public function generateXML($f){
		$tpl = $this->_root.$this->_tplDir.$f;
		if(!is_file($tpl))
			GGGlobal::error('le template '.$f.' n\'existe pas');
		else{
			$c = file_get_contents($tpl);
			preg_match_all('`<gagatranslate comment="(.+)" />`isU', $c, $m);
			$this->comment = isset($m[1][0]) ? $m[1][0] : '';
			preg_match_all('`_\((.+)\)_`U', $c, $m);
			foreach($this->_listLang as $lg){
				$old = $this->openXMLFile($f, $lg);
				$xml = $this->callbackGenerateXML($m, $old, $lg);
				$this->writeXMLFile($f, $lg, $xml->asXML());
				$this->tInfos['xml'][GGGlobal::name($f)] = filemtime($tpl);
			}
		}
	}

	/**
	 * Callback de la méthode generateXML.
	 * @param array $m les textes à traduire trouvé dans le fichier template
	 * @param SimpleXMLElement $oldXml l'ancien fichier xml
	 * @param string $lg la langue en cours
	 * @return SimpleXMLElement le nouveau fichier xml
	 */
	private function callbackGenerateXML($m, $oldXml, $lg){
		$cle = &$m[1];
		$cleFlip = array_flip(array_map('GGTParser::keyName', $m[1]));
		$old = array(); //ancienne clé
		$newXml = $this->createNewXMLInstance($lg, $this->comment);

		foreach($oldXml->children() as $v)
			$old[GGTParser::keyName((string)$v['key'])] = (string)$v;

		foreach(@$cleFlip as $k=>$v)
		$newXml->addChild('translation', self::entity(isset($old[$k]) ? $old[$k] : $cle[$v]))->addAttribute('key', $k);
		return $newXml;
	}

	/**
	 * Remplace les caractères spéciaux par leur entitié
	 * @param String $t le texte à convertir
	 * @return String le texte convertit
	 */
	public static function entity($t){
		return str_replace('&', '&amp;', $t);
	}

	/**
	 * Ouvre l'ancien fichier xml sinon crée un nouveau
	 * @param string $f fichier template correspondant
	 * @param stirng $lg langue
	 * @return SimpleXMLElement le fichier xml
	 */
	private function openXMLFile($f, $lg){
		$xmlF = $this->_root.$this->_langDir.$lg.'/'.GGGlobal::name($f).'.xml';
		return is_file($xmlF) ? simplexml_load_file($xmlF) : $this->createNewXMLInstance($lg, $this->comment);
	}

	/**
	 * Crée une nouvelle instance SimpleXMLElement en fonction de la langue.
	 * @param string $lg la langue
	 * @return SimpleXMLElement l'instance SimpleXMLElement
	 */
	public function createNewXMLInstance($lg, $comment=''){
		return new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE Gagatemplate[ <!ENTITY amp "&#38;#38;"> ]>
<language value="'.$lg.'" comment="'.$comment.'"></language>',
			LIBXML_COMPACT);
	}

	/**
	 * Ecrit le fichier XML
	 * @param string $f le fichier template correspondant
	 * @param string $lg la langue
	 * @param string $c le contenu du fichier
	 */
	private function writeXMLFile($f, $lg, $c){
		$xmlF = $this->_root.$this->_langDir.$lg.'/'.GGGlobal::name($f).'.xml';
		$c = str_replace("><translation", ">\n\t<translation", $c);
		$c = str_replace("></language>", ">\n</language>", $c);
		if(!@file_put_contents($xmlF, $c))
			GGGlobal::error('le dossier des fichiers de langue n\'est pas ouvert en &eacute;criture ou n\'existe pas. ('.$this->_root.$this->_langDir.$lg.')');
		$this->tDebug['xml'][] = $xmlF;
	}

	/**
	 * Compile les langue d'un fichier template
	 * @param string $tpl le fichier template correspondant
	 */
	public function compileLang($tpl){
		$e = GGGlobal::name($tpl);
		$f = $e.'.xml';
		if(!$this->isXML($tpl)) $this->generateXML($tpl);
		$this->callbackCompileLang($e, $this->lang);
		$this->tInfos['compile'][$e][$this->lang] = filemtime($this->_root.$this->_langDir.$this->lang.'/'.$f);
		$this->tDebug['compile'][] = $tpl.'_'.$this->lang;
		$this->tInfosWrite();
	}

	/**
	 * Callback de la méthode compileLang.
	 * @param string $f le fichier sans extention
	 * @param string $lg la langue actuelle
	 */
	private function callbackCompileLang($f, $lg){
		$xml = simplexml_load_file($this->_root.$this->_langDir.$lg.'/'.$f.'.xml');
		$r = '<?php $GGTRSL = array(';
		$p = true;
		foreach($xml as $v){
			if(!$p) $r .= ', ';
			$p = false;
			$r .= '\''.$v['key'].'\' => \''.str_replace('\'', '\\\'', (string)$v).'\'';
		}
		$r .= ');';
		if(!file_put_contents($this->_root.$this->_compileDir.$f.'-'.$lg.'.ggTrsl.php', $r))
			GGGlobal::error('Le dossier des fichiers compil&eacute;s n\'est pas ouvert en &eacute;criture (<i>'.$this->_root.$this->_compileDir.'</i>)', true);
	}

	/**
	 * Récupère les informations depuis le fichier d'infos.
	 */
	protected function tInfosOpen(){
		$f = $this->_root.$this->_cacheDir.'infos.gagaTrsl';
		if(is_file($f)) $this->tInfos = unserialize(file_get_contents($f));
	}

	/**
	 * Ecrit les informations dans le fichier d'infos.
	 */
	protected function tInfosWrite(){
		$f = $this->_root.$this->_cacheDir.'infos.gagaTrsl';
		if(!file_put_contents($f, serialize($this->tInfos)))
			GGGlobal::error('Le dossier des fichiers mis en cache n\'est pas ouvert en &eacute;criture (<i>'.$this->root.$this->cacheDir.'</i>)', true);
	}

	/**
	 * Réécriture de la méthode display de la class Gagatemplate pour y incorporé les variables de langue.
	 * Créée aussi la variable indiquant la langue actuelle : $GG_langage
	 * @param string $f le fichier template
	 * @param boolean $force force l'affichage (dans le cas ou la propriété $display est à false).
	 */
	protected function display($f, $force=false){
		$this->isNbLang();
		if(!$this->isLangCompile($f) || !$this->isXml($f)) $this->compileLang($f);
		$GGfile = $this->refParser->getFileLink($f);
		if($this->_display || $f){
			include $this->_root.$this->_compileDir.GGGlobal::name($f, $this->_lang).'.ggTrsl.php';
			foreach($this->vars as $k=>$v)
				${$k} = $v;
			$GG_Language = $this->_lang;
			include($GGfile);
		}
	}

	/**
	 * Indique si le fichier compilé pour la langue est valable.
	 * @param string $tpl le fichier template
	 * @return boolean si le fichier compilé est encore valide
	 */
	protected function isLangCompile($tpl){
		$f = GGGlobal::name($tpl);
		if($this->_forceLang) return false;
		if(isset($this->tInfos['compile'][$f][$this->lang])){
			if($this->tInfos['compile'][$f][$this->lang]< filemtime($this->_root.$this->_langDir.$this->lang.'/'.$f.'.xml')) return false;
		}else return false;
		return true;
	}

	/**
	 * Indique si les fichiers xml sont à jour
	 * @param string $tpl le fichier template
	 * @return boolean si les fichiers xml sont à jour.
	 */
	protected function isXml($tpl){
		if($this->_forceLang) return false;
		$n = GGGlobal::name($tpl);
		if(isset($this->tInfos['xml'][$n])){
			if($this->tInfos['xml'][$n]<filemtime($this->_root.$this->_tplDir.$tpl)) return false;
		}else return false;
		return true;
	}

	/**
	 * Vérifie si la liste des langues est différente par rapport au dernier chargement. Si oui la compilation est forcée.
	 */
	protected function isNbLang(){
		if(@$this->tInfos['lang'] != $this->_listLang){
			$this->_forceLang = true;
			$this->tInfos['lang'] = $this->_listLang;
			$this->tInfosWrite();
		}
	}

	/**
	 * Retourne la variable de débugage de Gagatranslate
	 * @return array la variable de débugage
	 */
	public function getTDebug(){
		return $this->tDebug;
	}
}

/**
 * Classe GGTParser. Elle etend la classe GGParser. Elle ajoute quelques fonctionnalité de sorte à prendre en compte les textes à traduire.
 * @author Gaétan Collaud (gaga26@gmail.com)
 * @version 1.0
 */
class GGTParser extends GGParser
{
	/// Référence à l'instance de la classe GGTParser
	protected static $instance = null;
	/**
	 * Permet de récupéré l'instance GGParser
	 * @param Gagatemplate $gg référence vers l'objet de la classe Gagatemplate
	 * @return GGParser l'instance du parseur
	 */
	public static function getInstance(Gagatemplate &$gg){
		if(is_null(self::$instance)) self::$instance = new GGTParser();
		self::$instance->setRefGG($gg);
		return self::$instance;
	}

	/**
	 * Réécriture de la méhtode parseSup de GGParser pour y incorporé le remplacement des variables de langue.
	 * Recherche aussi le commentaire à l'intention du traducteur
	 * @param string $c référence du contenu du fichier en cour de compilation
	 */
	protected function parseSup(&$c){
		$c = preg_replace_callback('`_\((.+)\)_`U', array('GGTParser', 'callbackParseSup'), $c);
		$c = preg_replace('`<gagatranslate comment=".+" />\n`isU', '', $c);
	}

	/**
	 * Callback de la méthode parseSup
	 * @param array $m les élémentss trouvés
	 * @return string le texte de remplacement
	 */
	protected static function callbackParseSup($m){
		return '<?php echo $GGTRSL[\''.GGTParser::keyName($m[1]).'\']; ?>';
	}

	/**
	 * Renommage des clés des fichier de langue, pour éviter d'avoir des clés de 3kilomètre...
	 * @param string $t texte à traduire
	 * @return string la clé correspondant
	 */
	public static function keyName($t){
		$r = '';
		$arr = str_split($t);
		$max = ($c = count($arr))<30 ? $c : 30;
		for($i=0; $i<$max; ++$i){
			$c = &$arr[$i];
			$n = ord($c);
			if(	$n>47 && $n<58 ||	//chiffre
				$n>96 && $n<123)	//lettre
					$r .= $c;
		}
		return $r;
	}
}
?>