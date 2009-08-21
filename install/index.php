<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<title>Séb's EasyWebSite :: Installation</title>		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<link rel="stylesheet" type="text/css" href="style.css" media="all" />
		<link rel="icon" href="../favicon.png" />
		<link rel="Shortcut Icon" href="../favicon.png" />
	</head>
	<body>
	<div id="conteneur">
			<div id="header_extensible">
				
					<div id="header">				
					</div>				
				</div>
				
			<div id="contenu">
				<h2>Installation</h2>
				
				<?php if($_GET['step']==1||!isset($_GET['step'])){ ?>
					<p>Bienvenue dans Séb´s EasyWebSite !<br/>Pour commencer, nous avons besoin de quelques renseignements :</p>
					<h3>Configuration générale</h3>
					<form action="<?php echo preg_replace('#^(.*)\?.*$#',"$1",$_SERVER["REQUEST_URI"]); ?>?step=2" method="post">
						<p>
							<label>Répertoire dans lequel Séb's EasyWebSite est situé (Laisser vide si à la racine)</label><input type="text" name="repertoire" value="<?php echo preg_replace('#^(/.*)/install.*$#',"$1",$_SERVER["REQUEST_URI"]) ?>" /><br/>
							<label>Nom du template à utiliser (dossier situé dans /templates)</label>
							<select name="template">
								<? 	$rep = "../templates/";
									$dir = opendir($rep);
									while ($f = readdir($dir)) {
									   if(is_dir($rep.$f) && !preg_match('#^(_|\.)#' ,$f)) {
										  echo '<option value="'.$f.'">'.$f.'</option>';
									   }
									}
									closedir($dir); ?>
							</select>
							<br/>
							<label>Nom du site</label><input type="text" name="nom" /><br/>
							<label>Domaine des adresses e-mails (ce qui doit être ajouté après le @)</label><input type="text" name="emailDomaine" value="<?php echo $_SERVER['HTTP_HOST'] ?>" /><br/>
							<label>URL Rewriting (il est conseillé de l'activer, cependant certains serveurs ne le supportent pas)</label><select name="rewrite"><option value="on" selected="selected">Activé</option><option value="off">Desactivé</option></select>
						</p>
						<p><input type="submit" value="Suite >>" /></p>
					</form>
					
				<?php } else if($_GET['step']==2){ ?>
					<form action="<?php echo preg_replace('#^(.*)\?.*$#',"$1",$_SERVER["REQUEST_URI"]); ?>?step=3" method="post">
						<?php //Reprise des variables POST
						foreach($_POST as $cle => $element) { echo '<input type="hidden" name="'.$cle.'" value="'.stripslashes($element).'" />'."\n"; }	?>
						<h3>Services Google</h3>
						<p>Veuillez indiquer ici les codes Google correspondants (chaque champ vide désactive le service correspondant)</p>
						<p>
							<label>Code de suivi Google Analytics</label><input type="text" name="googleAnalyticsCode" /><br/>
							<label>Code de confirmation Google Webmaster Tools</label><input type="text" name="googleWebmasterToolsMetaTag" /><br/>
							<label>Domaine Google Apps</label><input type="text" name="googleAppsDomain" />
						</p>
						<p><input type="submit" value="Suite >>" /></p>
					</form>
					
				<?php } else if($_GET['step']==3){ ?>
					<form action="<?php echo preg_replace('#^(.*)\?.*$#',"$1",$_SERVER["REQUEST_URI"]); ?>?step=4" method="post">
						<?php //Reprise des variables POST
						foreach($_POST as $cle => $element) { echo '<input type="hidden" name="'.$cle.'" value="'.stripslashes($element).'" />'."\n"; }	?>
						<h3>MySQL</h3>
						<p>Introduisez les informations requises à la connexion à MySQL ci-dessous</p>
						<p>
							<label>Hôte</label><input type="text" name="mysqlHost" /><br/>
							<label>Utilisateur</label><input type="text" name="mysqlUser" /><br/>
							<label>Mot de passe</label><input type="password" name="mysqlPassword" /><br/>
							<label>Base de données</label><input type="text" name="mysqlDb" /><br/>
							<label>Table pour les pages</label><input type="text" name="mysqlTablePages" value="pages" /><br/>
							<label>Table pour les news</label><input type="text" name="mysqlTableNews" value="news" />
						</p>
						<p><input type="submit" value="Suite >>" /></p>
					</form>
					
				<?php } else if($_GET['step']==4){ ?>
					<p>Lecture des paramètres donnés...
					<?php $config="<?php\n".'$domaine = $_SERVER[\'HTTP_HOST\'];'."\n";
						foreach($_POST as $cle => $element)	{
							if($cle!='template')
								$config.='$'.$cle." = '".$element."';\n";
						}
						$config.='require(\'template.php\'); ?>'; ?>
					<span style="color:green;">OK !</span><br/>
					Écriture de la configuration en cours...
					<?php if(file_put_contents('../config.php', $config)===false){ echo '<span style="color:red;">Erreur !</span><br/>Veuillez copier ceci dans config.php à la racine de Séb\'s EasyWebSite (UTF-8 requis !) :<br/><textarea name="config" cols="45" rows="16" readonly="readonly">'.$config.'</textarea>';}
					else echo '<span style="color:green;">OK !</span>';?><br/>
					Écriture de la configuration template...
					<?php if(file_put_contents('../template.php', '<?php $template=\''.$_POST['template'].'\'; ?>')===false){ echo '<span style="color:red;">Erreur !</span><br/>Veuillez copier ceci dans template.php à la racine de Séb\'s EasyWebSite (UTF-8 requis !) :<br/><textarea name="config" cols="45" rows="16" readonly="readonly"><?php $template=\''.$_POST['template'].'\'; ?></textarea>';}
					else echo '<span style="color:green;">OK !</span>';?></p>
					<p><a href="index.php?step=5">Suite &gt;&gt;</a></p>
					
				<?php } else if($_GET['step']==5){ ?>
					<p>Lecture de la configuration...
					<?php require('../config.php'); ?><span style="color:green;">OK !</span><br/>					
						Connexion à MySQL...
						<?php
							if(mysql_connect($mysqlHost, $mysqlUser, $mysqlPassword)!==false) echo '<span style="color:green;">OK !</span>';
							else echo '<span style="color:red;">Erreur !</span>'; ?><br/>
						Changement de l'encodage MySQL pour UTF8...
							<?php if(mysql_set_charset ('UTF8')==true) echo '<span style="color:green;">OK !</span>';
							else echo '<span style="color:red;">Erreur !</span>'; ?><br/>
						Choix de la base de données...
						<?php if(mysql_select_db($mysqlDb)==true) echo '<span style="color:green;">OK !</span>';
							else echo '<span style="color:red;">Erreur !</span>'; ?><br/>
						Création de la table des news...
						<?php mysql_query('DROP TABLE IF EXISTS `'.$mysqlTableNews.'`');
						if(mysql_query('CREATE TABLE IF NOT EXISTS `'.$mysqlTableNews.'` (
  `id` smallint(2) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link` tinyint(1) NOT NULL,
  `auteur` mediumtext NOT NULL,
  `title` mediumtext NOT NULL,
  `text` text NOT NULL,
  `categorie` text NOT NULL,
  `categorieshort` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2')==true) echo '<span style="color:green;">OK !</span>';
else echo '<span style="color:red;">Erreur !</span>'; ?><br/>
Remplissage de la table des news...
<?php if(mysql_query("INSERT INTO `".$mysqlDb."`.`".$mysqlTableNews."` (
`id` ,
`date` ,
`link` ,
`auteur` ,
`title` ,
`text` ,
`categorie` ,
`categorieshort`
)
VALUES (
NULL ,
CURRENT_TIMESTAMP , '1', 'sebyx', 'Bienvenue dans Seb\'s EasyWebSite !', '<p>Seb\'s EasyWebSite est un système PHP/MySQL vous facilitant le gestion de votre site web !</p>\r\n<p>Il propose un module de news avec flux RSS ainsi qu\'un système de gestion des pages. L\'édition se fait à l\'aide de la console d\'administration, laquelle contient FCKEditor, un éditeur WYSIWYG.</p>\r\n<p>Le système inclut aussi quelques services Google préconfigurés.</p>\r\n<p>Bon Webmastering !</p>' , 'Général' , 'general'
)")) echo '<span style="color:green;">OK !</span>';
	else echo '<span style="color:red;">Erreur !</span>'; ?> <br/>
	Création de la table des pages...
	<?php mysql_query("DROP TABLE IF EXISTS `".$mysqlTablePages."`");
	if(mysql_query("CREATE TABLE IF NOT EXISTS `".$mysqlTablePages."` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `shorttitle` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` mediumtext NOT NULL,
  `text` text NOT NULL,
  `menuseul` tinyint(1) NOT NULL,
  `images` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shorttitle` (`shorttitle`(256))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3")==true) echo '<span style="color:green;">OK !</span>';
	else echo '<span style="color:red;">Erreur !</span>'; ?> <br/>
	Remplissage de la table des pages...
	<?php if(mysql_query("INSERT INTO `".$mysqlDb."`.`".$mysqlTablePages."` (
`id` ,
`shorttitle` ,
`date` ,
`title` ,
`text` ,
`menuseul`,
`images`
)
VALUES (
1 , 'index',
CURRENT_TIMESTAMP , 'Accueil', '<p>Bienvenue dans <strong>Séb\'s</strong> <span style=\"color: rgb(0, 0, 255);\"><strong>EasyWebSite</strong></span> !</p>', '0', ''
)"))echo '<span style="color:green;">Accueil OK ! </span>';
							else echo '<span style="color:red;">Erreur !</span>';
				if(mysql_query("INSERT INTO `".$mysqlDb."`.`".$mysqlTablePages."` (
`id` ,
`shorttitle` ,
`date` ,
`title` ,
`text` ,
`menuseul`,
`images`
)
VALUES (
2 , 'news',
CURRENT_TIMESTAMP , 'News', 'News', '1', ''
)"))echo '<span style="color:green;">News OK ! </span>';
							else echo '<span style="color:red;">Erreur !</span>'; ?><br/>
							
Fermeture de la connexion à MySQL...
<?php if(mysql_close()==true) echo '<span style="color:green;">OK !</span>';
							else echo '<span style="color:red;">Erreur !</span>'; ?></p>
<h3>Installation terminée !</h3><p><a href="http://<?php echo $domaine.$repertoire; ?>">Retour au site</a></p>		
				<?php } ?>
				<div id="footer">
					<p><a href="http://sebseasywebsite.sourceforge.net/">Powered by Séb's EasyWebSite</a></p>
				</div>
			</div>
		</div>
	</body>
</html>