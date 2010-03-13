<?php //settings.php
require 'header.php';

switch($_GET['action'])
{
	default:
	case 'index':
		//Index ?>
			<ul>
				<li><a href="settings.php?action=template">Changer le template</a></li>
				<li><a href="settings.php?action=config">Changer la configuration</a></li>
				<li><a href="index.php">Retour à l'accueil de l'administration</a></li>
			</ul>			
			<?php
		//Fin index
		break;
		
	case 'config':
		//Configuration ?>
			<ul>
				<li><form action="settings.php?action=wipe" method="post">Remettre à zéro la configuration du site [Je suis sûr (irréversible) :<input type="checkbox" value="ok" name="confirm" />] <input type="submit" value="Remise à zéro" /></form></li>
				<li><a href="settings.php">Retour aux paramètres</a></li>
			</ul> <?php
		//Fin configuration
		break;
		
	case 'wipe':
		//RàZ
			echo '<p>';
			if($_POST['confirm'] == 'ok') {
				echo 'Suppression du fichier de configuration...';
				if(unlink('../config.php'))
					echo '<span style="color:green;">OK !</span>
							</p><p>Vous devez maintenant <a href="../install/">redémarrer l\'installation</a> pour continuer';
				else
					echo '<span style="color:red;">Erreur !</span>';
				echo '</p>';
				exit;
			} else {
				echo 'Vous n\'avez pas confirmé l\'action ! Arrêt <br/><span style="color:red;">Erreur !</span></p>';
				echo '<p><a href="settings.php">Retour aux paramètres</a></p>';
			}
		//Fin RàZ
		break;
	case 'template':
		//Template
			if(!isset($_POST['template'])){ ?>
			<p>Vous pouvez choisir ici le template à utiliser. Pour chaque template, un répertoire doit être créé dans /templates</p>
			<form action="settings.php?action=template" method="post">
				<p>
					<label>Template à utiliser </label>
					<select name="template">
						<? 	$rep = "../templates/";
							$dir = opendir($rep);
							while ($f = readdir($dir)) {
							   if(is_dir($rep.$f) && !preg_match('#^(_|\.|lightbox)#' ,$f)) {
								  echo '<option value="'.$f.'">'.$f.'</option>';
							   }
							}
							closedir($dir); ?>
					</select>
					<br/><input type="submit" value="Changer" />
				</p>
			</form>
			<?php } else { ?>
			<p>Écriture de la configuration template...
								<?php if(file_put_contents('../template.php', '<?php $template=\''.$_POST['template'].'\'; ?>')===false){ echo '<span style="color:red;">Erreur !</span><br/>Veuillez copier ceci dans template.php à la racine de Séb\'s EasyWebSite (UTF-8 requis !) :<br/><textarea name="config" cols="45" rows="16" readonly="readonly"><?php $template=\''.$_POST['template'].'\'; ?></textarea>';}
								else echo '<span style="color:green;">OK !</span>';?></p>
								<?php } ?>
								<p><a href="settings.php">Retour aux paramètres</a></p> <?php
		//Fin template
		break;

}
require 'footer.php'; ?>