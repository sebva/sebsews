<?php  require 'header.php';?>
	<ul>
		<li><a href="page_index.php">Gestion des pages</a></li>
		<li><a href="news_index.php">Gestion des news</a></li>
		<li><a href="template_change.php">Changement du template</a></li>
		<?php if ($googleAppsDomain!=''){?><li><a href="https://www.google.com/a/<?php echo $googleAppsDomain?>/ServiceLogin">Google Apps</a></li><?php }?>
	</ul>
<?php require 'footer.php'; ?>