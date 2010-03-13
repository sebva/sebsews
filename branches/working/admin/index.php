<?php  require 'header.php';?>
	<ul>
		<li><a href="page.php">Gestion des pages</a></li>
		<li><a href="news.php">Gestion des news</a></li>
		<li><a href="settings.php">Préférences générales</a></li>
		<?php if ($googleAppsDomain!=''){?><li><a href="https://www.google.com/a/<?php echo $googleAppsDomain?>/ServiceLogin">Google Apps</a></li><?php }?>
	</ul>
<?php require 'footer.php'; ?>