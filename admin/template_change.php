<?php  require 'header.php';
if(!isset($_POST['template'])){ ?>
<p>Vous pouvez choisir ici le template à utiliser. Pour chaque template, un répertoire doit être créé dans /templates</p>
<form action="template_change.php" method="post">
	<p>
		<label>Template à utiliser </label>
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
		<br/><input type="submit" value="Changer" />
	</p>
</form>
<?php } else { ?>
<p>Écriture de la configuration template...
					<?php if(file_put_contents('../template.php', '<?php $template=\''.$_POST['template'].'\'; ?>')===false){ echo '<span style="color:red;">Erreur !</span><br/>Veuillez copier ceci dans template.php à la racine de Séb\'s EasyWebSite (UTF-8 requis !) :<br/><textarea name="config" cols="45" rows="16" readonly="readonly"><?php $template=\''.$_POST['template'].'\'; ?></textarea>';}
					else echo '<span style="color:green;">OK !</span>';?></p>
					<?php } ?>
					<p><a href="index.php">Retour à l'accueil</a></p>
<?php  require 'footer.php'; ?>