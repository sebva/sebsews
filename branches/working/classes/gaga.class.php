<?php
/**
* Classe Gagatemplate, c'est la classe de base du moteur de template, c'est elle qui fera appelle aux autres sous-classes.
* @author Gaétan Collaud (gaga26@gmail.com)
* @version 5.0
*/
class Gagatemplate
{
	/// variables envoyées au moteur de template
	protected $vars				= array();
	/// [public] dossier racine vers le site
	protected $_root			= './';
	/// [public] s'il faut afficher le rendu ou non
	protected $_display			= true;
	/// [public] active la mise en cache ou non
	protected $_cache			= false;
	/// [public] force la recompilation, même si elle n'est pas nécessaire
	protected $_forceCompile	= false;
	/// [public] temps de mise en cache, zéro pour infini
	protected $_cacheTime		= 0;
	/// [public] dossier des fichiers templates
	protected $_tplDir			= 'templates/';
	/// [public] dossier des fichiers mis en cache
	protected $_cacheDir		= 'cache/';
	/// [public] dossier des fichiers compilés
	protected $_compileDir		= 'compile/';
	/// informations sur les fichiers déjà compilé/mis en cache
	protected $infos			= array();
	/// si il y a de nouvelle information
	protected $infosNew			= false;
	/// informations de débugages
	protected $debug			= array('cache' => false, 'temps' => array());
	/// référence vers le parseur
	protected $refParser		= null;

	/// @deprecated use $tplDIr
	protected $_template_dir;
	/// @deprecated use $compileDir
	protected $_compile_dir;
	/// @deprecated use $cacheDIr
	protected $_cache_dir;
	/// @deprecated use $forceCompile
	protected $_compile;
	/// @deprecated use $cacheTime
	protected $_cache_time;

	/**
	* Le constructeur du moteur de templates. Définit le chemin racine, construit le parseur, récupère les infos.
	* @param string $root dossier racine du site, de préférence un lien absolut
	*/
	public function __construct($root = './'){
		$this->_root = $root;
		$this->getInfos();
		$this->createParser();

		//référence des propritétés dépréciées
		$this->_template_dir = &$this->_tplDir;
		$this->_cache_dir = &$this->_cacheDir;
		$this->_compile_dir = &$this->_compileDir;
		$this->_compile = &$this->_forceCompile;
		$this->_cache_time = &$this->_cacheTime;
	}

	/**
	* Construit le parseur. Utile pour les extentions.
	*/
	protected function createParser(){
		$this->refParser = GGParser::getInstance($this);
	}

	/**
	* Permet de déclarer une variable pour le template
	* @param string|array $n nom de la variable ou tableau contenant plusieur variable
	* @param all $v valeur de la variable si $n n'est pas un Arrray
	* @see assignArray()
	*/
	public function assign($n, $v=null){
		if(is_null($v)) $this->vars = array_merge($this->vars, $n);
		else $this->vars[$n] = $v;
	}

	/**
	* permet d'assigner un tableau au moteur de template. Utilisé en liens avec un <foreach> dans le template.
	* @param string $n nom du tableau
	* @param array $arr valeurs du tableau
	* @see assign()
	*/
	public function assignArray($n, $arr){
		if(isset($this->vars[$n]) && !is_array($this->vars[$n]))
			GGGlobal::error('Vous &eacute;crasez une variable par un array');
		krsort($arr);

		if(strpos($n, '.')) {
			$e = explode('.', $n);
			$b = '$this->vars';
			$c = count($e) -1;
			for ($i=0 ; $i<$c ; $i++) {
				$b .= '[\'' . $e[$i] . '\']';
				eval('$c_b_p = count(' . $b . ') - 1;');
				$b .= '[' . $c_b_p . ']';
			}
			$b .= '[\'' . $e[$c] . '\'][] = $arr;';
			eval($b);
		}else
			$this->vars[$n][] = $arr;
	}

	/**
	* Alias de assignArray()
	* @param string $n nom du tableau
	* @param array $arr valeurs du tableau
	* @deprecated use assignArray()
	*/
	public function assign_array($n, $arr){
		return $this->assignArray($n, $arr);
	}

	/**
	* Fonction permettant de parser un fichier template
	* @param String $f   fichier à  parser
	* @param boolean $s  surnom à  lui donner en cas de cache
	*/
	public function parse($f, $s=false){
		$d = microtime(true);
		$tpl = $this->_root.$this->_tplDir.$f;
		if(!$this->isCompile($tpl)){
			$this->refParser->parse($f);
			$this->infos[$tpl]['compile'] = filemtime($tpl);
			$this->infosNew = true;
		}

		if($this->_cache && !$this->_forceCompile){
			$cacheFile = $this->_root.$this->_cacheDir.GGGlobal::name($f, $s).'.ggTpl.txt';
			if(!$this->isCache($tpl, $s)){
				ob_start();
				$this->display($f, true);
				$c = ob_get_contents();
				ob_clean();
				if(is_bool(file_put_contents($cacheFile, $c)))
					GGGlobal::error('Le dossier des fichiers mis en cache n\'est pas ouvert en &eacute;criture (<i>'.$this->root.$this->cacheDir.'</i>)', true);
				$this->infos[$tpl]['cache'][$s==false ? 'base' : $s] = time();
				$this->infosNew = true;
				if($this->_display) echo $c;
			}else{
				if($this->_display) echo file_get_contents($cacheFile);
				$this->debug['cache'] = true;
			}
		}else{
			$this->display($f);
		}
		$this->debug['temps'][$f] = microtime(true)-$d;
		$this->setInfos();
	}

	/**
	* Fonction affichant (ou non) la page.
	* @param string $f le fichier template
	* @param boolean $force force l'affichage (dans le cas ou la propriété $display est à false).
	* @see $display
	*/
	protected function display($f, $force=false){
		$file = $this->refParser->getFileLink($f);
		if($this->_display || $f){
			foreach($this->vars as $k=>$v)
				${$k} = $v;
			include($file);
		}
	}

	/**
	* Indique si le fichier compilé existe
	* @param string $tpl lien absolu vers le fichier template
	* @return booléen si le fichier compilé existe
	*/
	private function isCompile($tpl){
		if($this->_forceCompile) return false;
		if(!isset($this->infos[$tpl])) return false;
		if($this->infos[$tpl]['compile']<filemtime($tpl)) return false;
		return true;
	}

	/**
	* Indique si le fichier cache existe
	* @param string $tpl lien absolu vers le fichier template
	* @return booléen si le fichier cache existe
	*/
	private function isCache($tpl, $s){
		if(!$s) $s = 'base';
		if(isset($this->infos[$tpl]['cache'][$s])){
			if($this->_cacheTime <= 0) return true;
			if($this->infos[$tpl]['cache'][$s]+$this->_cacheTime > time()) return true;
		}
		return false;
	}

	/**
	* Verifie si le fichier est encore en cache. Permet d'éviter des assignations inutiles
	* @param string $f le fichier template
	* @param sring $s le surnom du fichier
	* @param boolean $active si false, regardera si la propriété $cache est activée, si true, $cache ne sera pas pris en compte
	* @return boolean si le fichier est encore en cache
	*/
	public function isntInCache($f, $s=false, $active=false){
		$tpl = $this->_root.$this->_tplDir.$f;
		if($this->_cache || $active) return !$this->isCache($tpl, $s);
		return true;
	}

	/**
	* Accesseur aux différentes propriétés (commence par $_)
	* @param String $n nom de la propriété
	* @return all la valeur de la propriété
	*/
	public function __get($n){
		$p = '_'.$n;
		if(isset($this->$p)) return $this->$p;
		else GGGlobal::error('Propri&eacute;t&eacute; <b>'.$n.'</b> inexistante');
	}

	/**
	* Mutateur des différentes propriétés (commence par $_). Vérifie aussi si sont type est bon.
	* @param string $n le nomde de la propriété
	* @param all $v nouvelle valeur de la propriété
	*/
	public function __set($n, $v){
		$p = '_'.$n;
		if(!isset($this->$p))
			GGGlobal::error('Propri&eacute;t&eacute; <b>'.$n.'</b> inexistante');
		elseif(gettype($v) != gettype($this->$p) && $this->$p)
			GGGlobal::error('Propri&eacute;t&eacute; <b>'.$n.'</b> doit être <i>'.gettype($this->$p).'</i>');
		else
			$this->$p = $v;
	}

	/**
	* Récupère les informations sur les fichiers compilés et mis en cache.
	*/
	protected function getInfos(){
		$f = $this->root.$this->_cacheDir.'infos.gagaTpl';
		if(is_file($f)) $this->infos = unserialize(file_get_contents($f));
	}

	/**
	* Sauve les informations sur les fichiers compilés et mis en cache.
	*/
	protected function setInfos(){
		if($this->infosNew){
			$f = $this->root.$this->_cacheDir.'infos.gagaTpl';
			@unlink($f);
			if(!file_put_contents($f, serialize($this->infos)))
				GGGlobal::error('Le dossier des fichiers mis en cache n\'est pas ouvert en &eacute;criture (<i>'.$this->root.$this->cacheDir.'</i>)', true);
		}
	}

	/**
	* Supprime un fichier mis en cache
	* @param string $f le fichier template
	* @param string|boolean $s le surnom du fichier ou true pour supprimer pour tous les surnoms
	* @see cleanCacheDir()
	*/
	public function delCache($f, $s='base') {
		$f_tpl = $this->_root.$this->_tplDir.$f;
		if($s === true)
			unset($this->infos[$f_tpl]['cache']);
		elseif(isset($this->infos[$f_tpl]['cache'][$s]))
			unset($this->infos[$f_tpl]['cache'][$s]);
		$this->setInfos();
	}

	/**
	* Supprime tous les fichiers d'un dossier pour une certaine extentions
	* @param string $dir le dossier
	* @param string $ext l'extention
	* @return array un tableau avec la liste de tous les fichiers supprimés.
	*/
	private function clean($dir,$ext=false) {
		$l = $this->_root.$dir;
		$a = array();
		$d = opendir($l);
		while ($f = readdir($d)) {
			if ($f != '.' && $f != '..') {
				if (($ext == false) || strpos($f, $ext)) {
					$a[] = $f;
					@unlink($l.$f);
				}
			}
		}
		closedir($d);
		return $a;
	}

	/**
	* Alias de cleanCacheDir()
	* @deprecated use cleanCacheDir()
	*/
	public function clean_cache_dir(){
		return $this->cleanCacheDir();
	}

	/**
	* Alias de cleanCompilDir()
	* @deprecated use cleanCompileDir()
	*/
	public function clean_compile_dir(){
		return $this->cleanCompileDir();
	}

	/**
	* Supprime tous les fichiers mis en cache
	* @return array la liste des fichiers mis en cache supprimés.
	* @see cleanCacheDir()
	*/
	public function cleanCompileDir() {
		$this->infos = array();
		$this->setInfos();
		return $this->clean($this->_compileDir, '.ggTpl.php');
	}

	/**
	* Supprime tous les fichiesr compilés
	* @return array la liste des fichiers compilés supprimés
	* @see cleanCompileDir()
	* @see delCache()
	*/
	public function cleanCacheDir() {
		foreach($this->infos as $k => $v){
		foreach($v as $sk=>$sv){
			unset($this->infos[$k]['cache']);
		}
	}
	$this->setInfos();
		return $this->clean($this->_cacheDir, '.ggTpl.txt');
	}

	/**
	* Permet d'afficher un popup de débugage du moteur de templates
	* @param string $url l'url vers la racine du site
	* @param int $dec nombre de décimal pour le temps
	*/
	public function debug($url, $dec=5){
		include 'gaga.debug.php';
	}
}

/**
* Classe GGParser, c'est elle qui va compiler les fichiers templates. Elle est appelée par la classe Gagatemplate.
* @author Gaétan Collaud (gaga26@gmail.com)
* @version 1.0
*/
class GGParser{
	/// instance du parseur (pour éviter qu'il n'y en aie plusieur)
	private static $instance = null;
	/// référence vers l'objet de la classe gagatemplate
	protected $gg;
	/// les balises à remplacer
	protected $bal= array(
		'var'           => array('{', '}', '[', ']'),                                   // vars
		'varAdd'        => array('var', 'name'),                                                // ajout de var
		'include'       => array('include', 'file', 'cache'),                   // include
		'cond'          => array('if', 'elseif', 'else', 'cond'),               // condition
		'foreach'       => array('foreach', 'var', 'as', 'foreachelse'),// boucle
		'func'          => array('function', 'name'),                                   // fonction
		'com'           => array('/#', '#/'));                                                  // commentaire

	/**
	* Permet de récupéré l'instance GGParser
	* @param Gagatemplate $gg référence vers l'objet de la classe Gagatemplate
	* @return GGParser l'instance du parseur
	*/
	public static function getInstance(Gagatemplate &$gg){
		if(is_null(self::$instance)) self::$instance = new GGParser();
		self::$instance->setRefGG($gg);
		return self::$instance;
	}

	/**
	* Parse un fichier en remplaçant toutes les balises par leur équivalent et l'écrit dans le dossier des fichiers compilés
	* @param string $f fichier template à parser
	*/
	public function parse($f){
		$c = $this->openFile($f);
		foreach($this->bal as $k=>$v){
			$k = 'parse'.ucfirst($k);
			$this->$k($c);
		}
		$this->parseSup($c);
		$this->writeFile($f, $c);
	}

	/**
	* Renvoie le lien vers le fichier compilé
	* @param string $f le fichier templates
	* @return string le lien vers le fichier compilé
	*/
	public function getFileLink($f){
		return $this->getCompileDir().GGGlobal::name($f).'.ggTpl.php';
	}

	/**
	* Ouvre le fichier template à compiler
	* @param string $f le fichier template
	* @return string le contenu du fichier
	*/
	protected function openFile($f){
		$file = $this->getTplDir().$f;
		if(is_file($file)) return file_get_contents($file);
		else GGGlobal::error('Fichier template introuvable : <i>'.$file.'</i>');
	}

	/**
	* Ecrit le fichier compilé dans le dossier des fichiers compilés
	* @param string $f le fichier template
	* @param string $c le contenu du fichier compilé
	*/
	protected function writeFile($f, $c){
		if(is_bool(file_put_contents($this->getFileLink($f), $c)))
			GGGlobal::error('Le dossier des fichiers compil&eacute;s n\'est pas ouvert en &eacute;criture (<i>'.$this->compileDir.'</i>)', true);
	}

	/**
	* Fonction qui pourra être utilisée par les extentions
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseSup(&$c){}

	/**
	* Parse les variables
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseVar(&$c){
		$c = preg_replace('`'.preg_quote($this->bal['var'][2]).'(\S+)'.preg_quote($this->bal['var'][3]).'`U', '[\'\1\']', $c);
		$c = preg_replace('`'.preg_quote($this->bal['var'][0]).'(\S+)'.preg_quote($this->bal['var'][1]).'`isU', '<?php echo $\1; ?>', $c);
	}

	/**
	* Parse les balises d'ajout de variable
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseVarAdd(&$c){
		$c = preg_replace('`\<'.preg_quote($this->bal['varAdd'][0]).' '.preg_quote($this->bal['varAdd'][1]).'\="(.+)"\>(.+)\</'.preg_quote($this->bal['varAdd'][0]).'\>`U', '<?php $\1 = \'\2\'; ?>', $c);
	}

	/**
	* Parse les inclusion
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseInclude(&$c){
		$c = preg_replace_callback('`<'.preg_quote($this->bal['include'][0]).' '.preg_quote($this->bal['include'][1]).'="(.+)"\s?('.preg_quote($this->bal['include'][2]).'="on")?\s?/?>`isU', array('GGParser', 'parseIncludeCallback'), $c);
	}

	/**
	* Callback de la méthode parseInclude()
	* @param array $m les entrées trouvées par la fonction parseInclude()
	* @return string le texte de remplacement
	*/
	private static function parseIncludeCallback($m){
		return isset($m[2]) ? '<?php $gaga_cache=$this->cache; $this->cache=true; $this->parse("'.str_replace('\\','\\\\',$m[1]).'"); $this->cache=$gaga_cache; ?>' : '<?php $this->parse("'.str_replace('\\','\\\\',$m[1]).'"); ?>';
	}

	/**
	* Parse les conditions
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseCond(&$c){
		$c = preg_replace(array(
				'`<'.preg_quote($this->bal['cond'][0]).' '.preg_quote($this->bal['cond'][3]).'="(.+)">`sU',
				'`</'.preg_quote($this->bal['cond'][0]).'>`sU',
				'`<'.preg_quote($this->bal['cond'][1]).' '.preg_quote($this->bal['cond'][3]).'="(.+)"\s?/?>`sU',
				'`<'.preg_quote($this->bal['cond'][2]).'\s?/?>`sU',
			),array(
				'<?php if(\1) { ?>',
				'<?php } ?>',
				'<?php }elseif(\1){ ?>',
				'<?php }else{ ?>'
			),
			$c);
	}

	/**
	* Parse les foreach (les boucles)
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseForeach(&$c){
		for($i=0; $i<10 && strpos($c, '</'.preg_quote($this->bal['foreach'][0])) !== FALSE; $i++){
			$c = preg_replace_callback(
				'`<('.preg_quote($this->bal['foreach'][0]).')\s*'.preg_quote($this->bal['foreach'][1]).'="(.+)"\s*'.preg_quote($this->bal['foreach'][2]).'="(.+)"\s*('.preg_quote($this->bal['foreach'][4]).'=".+")?\s*>((?:.(?!<'.preg_quote($this->bal['foreach'][0]).'>))*)</'.preg_quote($this->bal['foreach'][0]).'>`sU',
				array('GGParser', 'parseForeachCallback'), $c);
		}
		$c = str_replace('<'.$this->bal['foreach'][3].' />', '<?php }} else { if (true) {?>', $c);
	}

	static private function parseForeachCallback($m){
		if(!empty($m[4])){
			return $c = '<?php if(!empty('.$m[2].')){
	$'.$m[3].'gg[\'foreachCount\'] = 1;
	$'.$m[3].'gg[\'foreachTotal\'] = count('.$m[2].');
	foreach('.$m[2].' as $'.$m[3].') {
		$'.$m[3].'[\'foreachCount\'] = &$'.$m[3].'gg[\'foreachCount\'];
		$'.$m[3].'[\'foreachTotal\'] = &$'.$m[3].'gg[\'foreachTotal\'];
		$'.$m[3].'[\'foreachFirst\'] = $'.$m[3].'gg[\'foreachCount\']==1;
		$'.$m[3].'[\'foreachLast\'] = $'.$m[3].'gg[\'foreachCount\']==$'.$m[3].'gg[\'foreachTotal\'];?>
			'.$m[5].'
		<?php $'.$m[3].'gg[\'foreachCount\']++;
	}} ?>';
		}else{
			return $c = '<?php if(!empty('.$m[2].')){
	foreach('.$m[2].' as $'.$m[3].') {?>
		'.$m[5].'
	<?php }} ?>';
		}
	}

	/**
	* Parse les fonctions
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseFunc(&$c){
		$c = preg_replace_callback('`<'.preg_quote($this->bal['func'][0]).' '.preg_quote($this->bal['func'][1]).'="(\w+)"\s?(.*)?/?>`isU', array('GGParser', 'parseFuncCallback'), $c);
	}

	/**
	* Callback de la fonction parseFunc()
	* @param array $m les entrées trouvées par parseFunc()
	* @return string le texte de remplacement
	*/
	private static function parseFuncCallback($m){
		$args = '';
		if($nb = preg_match_all('`(string|int|var)="(.+)"`U', $m[2], $arr)){
			for($i=0; $i<$nb; $i++){
				$args .= $arr[1][$i] == 'string' ? '"'.$arr[2][$i].'", ' : $arr[2][$i].', ';
			}
		}
		$args = substr($args, 0, strlen($args)-2);
		if(!function_exists($m[1])) GGGlobal::error('La fonction <b>'.$m[1].'()</b> n\'existe pas !!!', true);
		return '<?php echo '.$m[1].'('.$args.'); ?>';
	}

	/**
	* Efface les commentaires du contenu
	* @param string $c référence du contenu du fichier en cour de compilation
	*/
	protected function parseCom(&$c){
		$c = preg_replace('`'.preg_quote($this->bal['com'][0]).'.+'.preg_quote($this->bal['com'][1]).'`U', null, $c);
	}

	/**
	* Mutateur de la propriété $gg (référence au moteur de template)
	* @param Gagatemplate $gg référence vers le moteur de template
	*/
	protected function setRefGG(Gagatemplate &$gg){
		$this->gg = $gg;
	}

	/**
	* Renvoie le lien vers le fichier compilé directement depuis la classe mère.
	* @return string le lien vers le fichier compilé
	*/
	private function getCompileDir(){
		return $this->gg->root.$this->gg->compileDir;
	}

	/**
	* Renvoie le lien vers le fichier cache directement depuis la classe mère.
	* @return string le lien vers le fichier cache
	*/
	private function getTplDir(){
		return $this->gg->root.$this->gg->tplDir;
	}
}

/**
* Classe abstraite GGGlobal. Elle engloble les fonctions global utile aux classe Gagatemplate et GGParser.
* @author Gaétan Collaud (gaga26@gmail.com)
* @version 1.0
* @abstract
*/
abstract class GGGlobal
{
	/// s'il faut afficher les erreurs
	static protected $errorDisplay  = true;
	/// le message d'erreur de base
	static protected $errorMsg              = '<hr /><b>Gagatemplate error:</b><br />';
	/// la liste des erreurs qui sont survenues, sinon false
	static protected $errors                = false;

	/**
	* Affiche un message d'erreur
	* @param string $t le message d'erreur
	* @param boolean $e s'il faut arrêter le script
	*/
	public static function error($t, $e=false){
		if(self::$errorDisplay){
			echo self::$errorMsg.$t;
			self::$errors .= $t."\n";
			if($e) exit;
		}
	}

	/**
	* Fonction de nommage des fichiers templates
	* @param string $f le nom du fichier template
	* @param string $s le surnom du fichier, sinon false
	* @return string le nom du fichier compilé/cache
	*/
	public static function name($f, $s=false){
		$f = str_replace('/', '-', substr($f, 0, strrpos($f, '.')));
		if($s) $f .= '-'.$s;
		return $f;
	}

	/// Mutateur de la propriété errorDisplay
	public static function setErrorDisplay(bool $v){
		self::$errorDisplay = $v;
	}

	/// Mutateur de la propriété ErrorMsg
	public static function setErrorMsg(string $m){
		self::$errorMsg = $m;
	}

	/// Accesseur à la propriété error
	public static function getErrors(){
		return $errors;
	}
}
?>